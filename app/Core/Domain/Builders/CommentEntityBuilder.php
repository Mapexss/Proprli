<?php

namespace App\Core\Domain\Builders;

use App\Core\Domain\Entities\CommentEntity;

class CommentEntityBuilder
{
    private ?int $id = null;
    private string $content = '';
    private ?int $taskId = null;
    private ?int $creatorUserId = null;

    /**
     * Sets the comment id
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the comment content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Sets the comment task id
     *
     * @param ?int $taskId
     * @return $this
     */
    public function setTaskId(?int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Sets the comment creator user id
     *
     * @param ?int $creatorUserId
     * @return $this
     */
    public function setCreatorUserId(?int $creatorUserId): self
    {
        $this->creatorUserId = $creatorUserId;

        return $this;
    }

    /**
     * Creates a new CommentEntity with stored builder values
     *
     * @return CommentEntity
     */
    public function build(): CommentEntity
    {
        return new CommentEntity(
            id: $this->id,
            content: $this->content,
            taskId: $this->taskId,
            creatorUserId: $this->creatorUserId
        );
    }
}
