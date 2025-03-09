<?php

namespace App\Core\Domain\Entities;

use App\Core\Domain\Exceptions\InvalidTaskStatusException;
use App\Enums\TaskStatusEnum;

class TaskEntity
{
    /**
     * TaskEntity contructor
     *
     * @param ?int $id
     * @param string $name
     * @param string $description
     * @param string $status
     * @param int $buildingId
     * @param int $assignedUserId
     * @param int $creatorUserId
     * @param CommentEntity[] $comments
     */
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $status,
        public readonly int $buildingId,
        public readonly int $assignedUserId,
        public readonly int $creatorUserId,
        public readonly array $comments = []
    ) {
        if (!TaskStatusEnum::tryFrom($this->status)) {
            throw new InvalidTaskStatusException();
        }
    }

    /**
     * Checks if the task can be commented by the provided user
     *
     * @param UserEntity $userWhoWillComent
     * @param UserEntity $userAssignedToTheTask
     * @return boolean
     */
    public function canComment(UserEntity $userWhoWillComent, UserEntity $userAssignedToTheTask): bool
    {
        return $this->assignedUserId === $userWhoWillComent->id
            || $this->creatorUserId === $userWhoWillComent->id
            || $userWhoWillComent->teamId === $userAssignedToTheTask->teamId;
    }
}
