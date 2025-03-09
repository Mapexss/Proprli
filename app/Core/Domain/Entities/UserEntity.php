<?php

namespace App\Core\Domain\Entities;

class UserEntity
{
    /**
     * UserEntity contructor
     *
     * @param int $id
     * @param int $teamId
     */
    public function __construct(
        public readonly int $id,
        public readonly int $teamId
    ) {
    }
}
