<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Building;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Building::factory()->count(2)->create();

        Team::factory()
            ->count(2)
            ->has(User::factory()->count(2), 'members')
            ->create();
    }
}
