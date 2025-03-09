<?php

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(false)->index();
            $table->string('description', 255)->nullable(false);
            $table->enum('status', array_column(TaskStatusEnum::cases(), 'value'))->nullable(false)->default(TaskStatusEnum::OPEN->value)->index();
            $table->foreignId('building_id')->nullable(false)->constrained('buildings')->cascadeOnDelete()->index();
            $table->foreignId('assigned_user_id')->nullable(false)->constrained('users')->cascadeOnDelete();
            $table->foreignId('creator_user_id')->nullable(false)->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
