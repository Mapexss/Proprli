<?php

namespace App\Core\Data\Services;

use App\Core\Domain\Repositories\TaskRepository;
use App\Core\Domain\Entities\TaskEntity;
use App\Core\Data\Services\Interfaces\IFetchTasksService;

class FetchTasksService implements IFetchTasksService
{
    /**
     * FetchTasksService contructor
     *
     * @param TaskRepository $TaskRepository
     */
    public function __construct(
        private readonly TaskRepository $TaskRepository
    ) {
    }

    /**
     * Fetch tasks along with their comments (based on provided filters)
     *
     * @param array<string, string|int> $filters
     * @return TaskEntity[]
     */
    public function fetchTasks(array $filters): array
    {
        return $this->TaskRepository->fetch($filters);
    }
}
