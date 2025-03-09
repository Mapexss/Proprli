<?php

namespace App\Core\Data\Services;

use App\Core\Domain\Repositories\CommentRepository;
use App\Core\Domain\Repositories\TaskRepository;
use App\Core\Domain\Repositories\UserRepository;
use App\Core\Domain\Builders\CommentEntityBuilder;
use App\Core\Domain\Exceptions\CreatorUserNotFoundException;
use App\Core\Domain\Exceptions\TaskNotFoundException;
use App\Core\Domain\Exceptions\UnauthorizedToCommentException;
use App\Core\Data\Services\Interfaces\IStoreCommentService;

class StoreCommentService implements IStoreCommentService
{
    /**
     * StoreCommentService contructor
     *
     * @param UserRepository $userRepository
     * @param TaskRepository $taskRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TaskRepository $taskRepository,
        private readonly CommentRepository $commentRepository
    ) {
    }

    /**
     * Stores a new comment
     *
     * @param array<string, string|int> $payload
     * @throws CreatorUserNotFoundException
     * @throws TaskNotFoundException
     * @throws UnauthorizedToCommentException
     * @return void
     */
    public function storeComment(array $payload): void
    {
        $userWhoWillComment = $this->userRepository->getById($payload['creator_user_id']);

        if (!$userWhoWillComment) {
            throw new CreatorUserNotFoundException();
        }

        $taskThatWillBeCommented = $this->taskRepository->getById($payload['task_id']);

        if (!$taskThatWillBeCommented) {
            throw new TaskNotFoundException();
        }

        $userAssignedToTheTask = $this->userRepository->getById($taskThatWillBeCommented->assignedUserId);

        $canUserComment = $taskThatWillBeCommented->canComment($userWhoWillComment, $userAssignedToTheTask);

        if (!$canUserComment) {
            throw new UnauthorizedToCommentException();
        }

        $commentEntityBuilder = new CommentEntityBuilder();
        $commentToStore = $commentEntityBuilder->setContent($payload['content'])
            ->setTaskId($payload['task_id'])
            ->setCreatorUserId($payload['creator_user_id'])
            ->build();

        $this->commentRepository->store($commentToStore);
    }
}
