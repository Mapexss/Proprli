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

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected readonly Building $building;
    protected readonly array $allTasksStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->allTasksStatus = TaskStatusEnum::allCases();
        $this->building = Building::factory()->create();
    }

    /**
     * Get task payload for team
     * 
     * @param Team $team
     * @param array<string, string|int> $overrides
     * @return array<string, string|int>
     */
    protected function getTaskPayloadForTeam(Team $team, array $overrides = []): array
    {
        return array_merge([
            'name'             => $this->faker->sentence(1),
            'description'      => $this->faker->sentence(2),
            'status'           => $this->faker->randomElement($this->allTasksStatus),
            'building_id'      => $this->building->id,
            'creator_user_id'  => $team->members->first()->id,
            'assigned_user_id' => $team->members->last()->id,
        ], $overrides);
    }

    /**
     * Test should fetch tasks
     * 
     * @return void
     */
    public function test_should_fetch_tasks(): void
    {        
        $team = Team::factory()->has(User::factory()->count(2), 'members')->create();
        $creator = $team->members->first();
        $assignee = $team->members->last();

        Task::factory()->create([
            'name'             => 'Nome Teste 1',
            'description'      => 'Descrição aleatória 1',
            'status'           => TaskStatusEnum::OPEN->value,
            'building_id'      => $this->building->id,
            'creator_user_id'  => $creator->id,
            'assigned_user_id' => $assignee->id,
        ]);
        Task::factory()->create([
            'name'             => 'Nome Teste 2',
            'description'      => 'Descrição aleatória 2',
            'status'           => TaskStatusEnum::COMPLETED->value,
            'building_id'      => $this->building->id,
            'creator_user_id'  => $creator->id,
            'assigned_user_id' => $assignee->id,
        ]);
        Task::factory()->create([
            'name'             => 'Nome Teste 3',
            'description'      => 'Descrição aleatória 3',
            'status'           => TaskStatusEnum::OPEN->value,
            'building_id'      => $this->building->id,
            'creator_user_id'  => $creator->id,
            'assigned_user_id' => $assignee->id,
        ]);

        $queryParams = [
            'status'           => TaskStatusEnum::OPEN->value,
            'assigned_user_id' => $assignee->id,
        ];

        $response = $this->getJson(route('tasks.index', $queryParams));
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'status',
                    'buildingId',
                    'creatorUserId',
                    'assignedUserId',
                    'comments',
                ],
            ],
        ]);

        $response->assertJsonFragment(['name' => 'Nome Teste 1', 'status' => TaskStatusEnum::OPEN->value]);
        $response->assertJsonFragment(['name' => 'Nome Teste 3', 'status' => TaskStatusEnum::OPEN->value]);
    }

    /**
     * Test should store a new task
     * 
     * @return void
     */
    public function test_should_store_a_new_task(): void
    {
        $team = Team::factory()->has(User::factory()->count(2), 'members')->create();
        $payload = $this->getTaskPayloadForTeam($team);

        $response = $this->postJson(route('tasks.store', $this->building->id), $payload);
        $response->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'name'             => $payload['name'],
            'description'      => $payload['description'],
            'status'           => $payload['status'],
            'building_id'      => $payload['building_id'],
            'creator_user_id'  => $payload['creator_user_id'],
            'assigned_user_id' => $payload['assigned_user_id'],
        ]);
    }

    /**
     * Test should get 422 after trying to store a task with an unknown creator
     * 
     * @return void
     */
    public function test_should_store_task_with_unknown_creator_returns_validation_error(): void
    {
        $team = Team::factory()->has(User::factory()->count(1), 'members')->create();
        $payload = $this->getTaskPayloadForTeam($team, ['creator_user_id' => 9999]);

        $response = $this->postJson(route('tasks.store', $this->building->id), $payload);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test should get 422 after trying to store a task with an unknown assign
     * 
     * @return void
     */
    public function test_should_get_422_after_trying_to_store_a_task_with_an_unknown_assign(): void
    {
        $team = Team::factory()->has(User::factory()->count(2), 'members')->create();
        $payload = $this->getTaskPayloadForTeam($team, ['assigned_user_id' => 9999]);
        
        $response = $this->postJson(route('tasks.store', $this->building->id), $payload);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('tasks', 0);
    }

    /**
     * Test should get 422 after trying to store a task with different building
     * 
     * @return void
     */
    public function test_should_get_401_after_trying_to_store_a_task_with_different_team_members(): void
    {
        $team1 = Team::factory()->has(User::factory()->count(2), 'members')->create();
        $team2 = Team::factory()->has(User::factory()->count(2), 'members')->create();

        $creator = $team1->members->first();
        $assigned = $team2->members->first();

        $payload = [
            'name'             => $this->faker->sentence(1),
            'description'      => $this->faker->sentence(2),
            'status'           => $this->faker->randomElement($this->allTasksStatus),
            'building_id'      => $this->building->id,
            'creator_user_id'  => $creator->id,
            'assigned_user_id' => $assigned->id,
        ];

        $response = $this->postJson(route('tasks.store', $this->building->id), $payload);

        $response->assertUnauthorized();
        $this->assertDatabaseCount('tasks', 0);
    }
}