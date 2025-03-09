<?php

namespace App\Core\Data\Services\Interfaces;

use App\Core\Domain\Entities\TaskEntity;

interface IFetchTasksService
{
    /**
     * Fetch tasks along with their comments
     *
     * @param array<string, string|int> $filters
     * @return TaskEntity[]
     */
    public function fetchTasks(array $filters): array;
}
