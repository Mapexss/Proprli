<?php

namespace App\Core\Domain\Repositories;

use App\Core\Domain\Repositories\Interfaces\IUserRepository;
use App\Core\Domain\Entities\UserEntity;
use App\Models\User;

class UserRepository implements IUserRepository
{
    /**
     * Tries to fetch an user that matches the provided id
     *
     * @param int $id
     * @return ?UserEntity
     */
    public function getById(int $id): ?UserEntity
    {
        /** @var ?User */
        $user = User::where('id', $id)->first(['id', 'team_id']);

        if (!$user) {
            return null;
        }

        return new UserEntity(id: $user->id, teamId:$user->team_id);
    }

}
