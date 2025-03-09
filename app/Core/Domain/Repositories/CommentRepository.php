<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Repositories\Interfaces\ICommentRepository;
use App\Core\Domain\Entities\CommentEntity;
use App\Models\Comment;

class CommentRepository implements ICommentRepository
{
    /**
     * Stores a new comment
     *
     * @param CommentEntity $commentEntity
     * @return void
     */
    public function store(CommentEntity $commentEntity): void
    {
        Comment::create([
            'content' => $commentEntity->content,
            'task_id' => $commentEntity->taskId,
            'creator_user_id' => $commentEntity->creatorUserId,
        ]);
    }
}
