<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Building;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(2),
            'status' => fake()->randomElement(TaskStatusEnum::allCases()),
            'building_id' => Building::factory(),
            'assigned_user_id' => User::factory(),
            'creator_user_id' => User::factory(),
        ];
    }
}
