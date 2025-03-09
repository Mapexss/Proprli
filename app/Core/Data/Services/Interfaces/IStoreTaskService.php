<?php

namespace App\Core\Data\Services\Interfaces;

use App\Core\Domain\Exceptions\AssignedUserNotFoundException;
use App\Core\Domain\Exceptions\CreatorUserNotFoundException;
use App\Core\Domain\Exceptions\UnauthorizedAttachedTeamException;

interface IStoreTaskService
{
    /**
     * Stores a new task
     *
     * @param array<string, string|int> $payload
     * @throws CreatorUserNotFoundException
     * @throws AssignedUserNotFoundException
     * @throws UnauthorizedAttachedTeamException
     * @return void
     */
    public function storeTask(array $payload): void;
}
