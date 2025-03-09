<?php

namespace Tests\Unit;

use App\Core\Data\Services\FetchTasksService;
use App\Core\Domain\Entities\TaskEntity;
use App\Core\Domain\Repositories\TaskRepository;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class FetchTasksServiceTest extends TestCase
{
    use WithFaker;

    private readonly MockObject $taskRepositoryMock;
    private readonly FetchTasksService $fetchTasksService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();

        $this->taskRepositoryMock = $this->createMock(TaskRepository::class);

        $this->fetchTasksService = new FetchTasksService((object) $this->taskRepositoryMock);
    }

    public function test_should_store_a_new_task_with_provided_params(): void
    {
        $payload = [
            'status' => $this->faker()->randomElement(TaskStatusEnum::AllCases()),
            'assigned_user_id' => $this->faker()->randomNumber(2),
            'building_id' => $this->faker()->randomNumber(2),
        ];

        $fakeFetchedTasks = [new TaskEntity(
            id: $this->faker()->randomNumber(2),
            name: $this->faker()->sentence(2),
            description: $this->faker()->paragraph(2),
            status: $this->faker()->randomElement(TaskStatusEnum::AllCases()),
            buildingId: $this->faker()->randomNumber(2),
            assignedUserId: $this->faker()->randomNumber(2),
            creatorUserId: $this->faker()->randomNumber(2)
        )];

        $this->taskRepositoryMock->expects($this->once())
            ->method('fetch')
            ->with($payload)
            ->willReturn($fakeFetchedTasks);

        $this->assertEquals($fakeFetchedTasks, $this->fetchTasksService->fetchTasks($payload));
    }
}
