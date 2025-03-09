<?php

namespace App\Core\Domain\Builders;

use App\Core\Domain\Entities\TaskEntity;

class TaskEntityBuilder
{
    private ?int $id = null;
    private string $name = '';
    private string $description = '';
    private string $status = '';
    private ?int $buildingId = null;
    private ?int $assignedUserId = null;
    private ?int $creatorUserId = null;
    private array $comments = [];

    /**
      * Set the task id
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
     * Set the task name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the task description
     *
     * @param ?string $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Sets the task status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the task building id
     *
     * @param ?int $buildingId
     * @return $this
     */
    public function setBuildingId(?int $buildingId): self
    {
        $this->buildingId = $buildingId;

        return $this;
    }

    /**
     * Sets the task assigned user id
     *
     * @param ?int $assignedUserId
     * @return $this
     */
    public function setAssignedUserId(?int $assignedUserId): self
    {
        $this->assignedUserId = $assignedUserId;

        return $this;
    }

    /**
     * Sets the task creator user ID
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
     * Sets the task comments
     *
     * @param array $comments
     * @return $this
     */
    public function setComments(array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Creates a new TaskEntity with stored builder values
     *
     * @return TaskEntity
     */
    public function build(): TaskEntity
    {
        return new TaskEntity(
            id: $this->id,
            name: $this->name,
            description: $this->description,
            status: $this->status,
            buildingId: $this->buildingId,
            assignedUserId: $this->assignedUserId,
            creatorUserId: $this->creatorUserId,
            comments: $this->comments
        );
    }
}
