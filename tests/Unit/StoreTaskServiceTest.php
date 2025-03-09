<?php

namespace Tests\Unit;

use App\Core\Domain\Repositories\TaskRepository;
use App\Core\Domain\Repositories\UserRepository;
use App\Core\Data\Services\StoreTaskService;
use App\Core\Domain\Entities\TaskEntity;
use App\Core\Domain\Entities\UserEntity;
use App\Core\Domain\Exceptions\AssignedUserNotFoundException;
use App\Core\Domain\Exceptions\CreatorUserNotFoundException;
use App\Core\Domain\Exceptions\UnauthorizedAttachedTeamException;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class StoreTaskServiceTest extends TestCase
{
    use WithFaker;

    private readonly MockObject $userRepositoryMock;
    private readonly MockObject $taskRepositoryMock;
    private readonly StoreTaskService $storeTaskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();

        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->taskRepositoryMock = $this->createMock(TaskRepository::class);

        $this->storeTaskService = new StoreTaskService(
            userRepository: (object) $this->userRepositoryMock,
            taskRepository: (object) $this->taskRepositoryMock
        );
    }

    public function test_should_store_a_new_task_with_provided_params(): void
    {
        $payload = [
            'name' => $this->faker()->sentence(2),
            'description' => $this->faker()->paragraph(4),
            'status' => $this->faker()->randomElement(array_column(TaskStatusEnum::cases(), 'value')),
            'building_id' => $this->faker()->randomNumber(2),
            'creator_user_id' => $this->faker()->randomNumber(2),
            'assigned_user_id' => $this->faker()->randomNumber(2),
        ];

        $fakeTeamId = $this->faker()->randomNumber(2);
        $fakeCreatorUser = new UserEntity($payload['creator_user_id'], $fakeTeamId);
        $fakeAssignedUser = new UserEntity($payload['assigned_user_id'], $fakeTeamId);

        $this->userRepositoryMock->expects($this->exactly(2))
            ->method('getById')
            ->willReturnMap([
                [$payload['creator_user_id'], $fakeCreatorUser],
                [$payload['assigned_user_id'], $fakeAssignedUser],
            ]);

        $this->taskRepositoryMock->expects($this->once())
            ->method('store')
            ->with($this->callback(function (TaskEntity $task) use ($payload) {
                return $task instanceof TaskEntity &&
                    $task->name === $payload['name'] &&
                    $task->description === $payload['description'] &&
                    $task->status === $payload['status'] &&
                    $task->buildingId === $payload['building_id'] &&
                    $task->assignedUserId === $payload['assigned_user_id'] &&
                    $task->creatorUserId === $payload['creator_user_id'];
            }));

        $this->storeTaskService->storeTask($payload);
    }

    public function test_should_throw_when_the_creator_user_is_not_found(): void
    {
        $payload = [
            'name' => $this->faker()->sentence(2),
            'description' => $this->faker()->paragraph(4),
            'status' => $this->faker()->randomElement(TaskStatusEnum::allCases()),
            'building_id' => $this->faker()->randomNumber(2),
            'creator_user_id' => $this->faker()->randomNumber(2),
            'assigned_user_id' => $this->faker()->randomNumber(2),
        ];

        $this->userRepositoryMock->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        $this->expectException(CreatorUserNotFoundException::class);

        $this->storeTaskService->storeTask($payload);
    }

    public function test_should_throw_when_the_assigned_user_is_not_found(): void
    {
        $payload = [
            'name' => $this->faker()->sentence(2),
            'description' => $this->faker()->paragraph(4),
            'status' => $this->faker()->randomElement(TaskStatusEnum::allCases()),
            'building_id' => $this->faker()->randomNumber(2),
            'creator_user_id' => $this->faker()->randomNumber(2),
            'assigned_user_id' => $this->faker()->randomNumber(2),
        ];

        $fakeTeamId = $this->faker()->randomNumber(2);
        $fakeCreatorUser = new UserEntity($payload['creator_user_id'], $fakeTeamId);

        $this->userRepositoryMock->expects($this->exactly(2))
            ->method('getById')
            ->willReturnMap([
                [$payload['creator_user_id'], $fakeCreatorUser],
                [$payload['assigned_user_id'], null],
            ]);

        $this->expectException(AssignedUserNotFoundException::class);

        $this->storeTaskService->storeTask($payload);
    }

    public function test_should_throw_when_a_user_tries_to_store_a_task_to_a_non_team_member(): void
    {
        $payload = [
            'name' => $this->faker()->sentence(2),
            'description' => $this->faker()->paragraph(4),
            'status' => $this->faker()->randomElement(TaskStatusEnum::allCases()),
            'building_id' => $this->faker()->randomNumber(2),
            'creator_user_id' => $this->faker()->randomNumber(2),
            'assigned_user_id' => $this->faker()->randomNumber(2),
        ];

        $fakeCreatorUserTeamId = $this->faker()->randomNumber(1);
        $fakeCreatorUser = new UserEntity($payload['creator_user_id'], $fakeCreatorUserTeamId);

        $fakeAssignedUserTeamId = $this->faker()->randomNumber(2);
        $fakeAssignedUser = new UserEntity($payload['assigned_user_id'], $fakeAssignedUserTeamId);

        $this->userRepositoryMock->expects($this->exactly(2))
            ->method('getById')
            ->willReturnMap([
                [$payload['creator_user_id'], $fakeCreatorUser],
                [$payload['assigned_user_id'], $fakeAssignedUser],
            ]);

        $this->expectException(UnauthorizedAttachedTeamException::class);

        $this->storeTaskService->storeTask($payload);
    }
}
