<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Building;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected readonly Building $building;
    protected readonly Task $task;
    protected readonly User $creatorUser;
    protected readonly User $assignedUser;
    protected readonly array $allTasksStatus;

    /**
     * Set up comment test cases
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $team = Team::factory()->has(User::factory()->count(2), 'members')->create();
        
        $this->building = Building::factory()->create();
        $this->allTasksStatus = TaskStatusEnum::allCases();
        
        $this->creatorUser = $team->members->first();
        $this->assignedUser = $team->members->last();

        $this->task = Task::factory()->create([
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(4),
            'status' => $this->faker->randomElement($this->allTasksStatus),
            'building_id' => $this->building->id,
            'creator_user_id' => $this->creatorUser->id,
            'assigned_user_id' => $this->assignedUser->id,
        ]);

        $this->assertNotNull($this->task->id);
        $this->assertDatabaseHas('tasks', ['id' => $this->task->id]);
    }

    /**
     * Get comment payload
     * 
     * @param array<string, string|int> $overrides
     * @return array<string, string|int>
     */
    protected function getCommentPayload(array $overrides = []): array
    {
        return array_merge([
            'content'         => $this->faker->sentence(2),
            'creator_user_id' => $this->creatorUser->id,
        ], $overrides);
    }

    /**
     * Test to store a new comment
     * 
     * @return void
     */
    public function test_should_store_a_new_comment(): void
    {
        $response = $this->postJson(route('comments.store', $this->task->id), $this->getCommentPayload());
        
        $response->assertCreated();

        $this->assertDatabaseCount('comments', 1);
    }

    /**
     * Test to get 422 after trying to store a comment with an unknown creator
     * 
     * @return void
     */
    public function test_should_store_comment_with_unknown_creator_returns_validation_error(): void
    {
        $response = $this->postJson(route('comments.store', $this->task->id), $this->getCommentPayload(['creator_user_id' => 99999]));

        $response->assertUnprocessable();

        $this->assertDatabaseEmpty('comments');
    }

    /**
     * Test to get 422 after trying to store a comment in an unknown task
     * 
     * @return void
     */
    public function test_should_store_comment_in_unknown_task_returns_not_found(): void
    {
        $response = $this->postJson(route('comments.store', 99999), $this->getCommentPayload(['task_id' => 99999]));

        $response->assertNotFound();
        $this->assertDatabaseCount('comments', 0);
    }

    /**
     * Test to get 401 after trying to store a comment with a different team member
     * 
     * @return void
     */
    public function test_should_store_comment_with_different_team_member_returns_unauthorized(): void
    {
        $otherTeam = Team::factory()->has(User::factory()->count(2), 'members')->create();
        $otherTask = Task::factory()->create([
            'name'             => $this->faker->sentence(2),
            'description'      => $this->faker->sentence(4),
            'status'           => $this->faker->randomElement($this->allTasksStatus),
            'building_id'      => $this->building->id,
            'creator_user_id'  => $otherTeam->members->first()->id,
            'assigned_user_id' => $otherTeam->members->last()->id,
        ]);

        $response = $this->postJson(route('comments.store', $otherTask->id), $this->getCommentPayload());
        $response->assertUnauthorized();
        $this->assertDatabaseCount('comments', 0);
    }
}