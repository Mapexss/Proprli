<?php

namespace App\Core\Domain\Repositories\Interfaces;

use App\Core\Domain\Entities\CommentEntity;

interface ICommentRepository
{
    /**
     * Stores a new comment
     *
     * @param CommentEntity $commentEntity
     * @return void
     */
    public function store(CommentEntity $commentEntity): void;
}
