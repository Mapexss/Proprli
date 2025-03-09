<?php

namespace App\Core\Data\Services\Interfaces;

use App\Core\Domain\Exceptions\CreatorUserNotFoundException;
use App\Core\Domain\Exceptions\TaskNotFoundException;
use App\Core\Domain\Exceptions\UnauthorizedToCommentException;

interface IStoreCommentService
{
    /**
     * Stores a new comment
     *
     * @param array<string, string|int> $payload
     * @throws CreatorUserNotFoundException
     * @throws TaskNotFoundException
     * @throws UnauthorizedToCommentException
     * @return void
     */
    public function storeComment(array $payload): void;
}
