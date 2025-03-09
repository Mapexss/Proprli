<?php

namespace App\Core\Domain\Repositories\Interfaces;

use App\Core\Domain\Entities\UserEntity;

interface IUserRepository
{
    /**
     * Tries to fetch an user that matches the provided id
     *
     * @param int $id
     * @return ?UserEntity
     */
    public function getById(int $id): ?UserEntity;
}
