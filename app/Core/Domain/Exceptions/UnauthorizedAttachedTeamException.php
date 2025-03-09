<?php

namespace App\Core\Domain\Exceptions;

class UnauthorizedAttachedTeamException extends \Exception
{
    /**
     * UnauthorizedAttachedTeamException constructor.
     */
    public function __construct()
    {
        parent::__construct("Users from different teams cannot assign tasks to each other");
    }
}
