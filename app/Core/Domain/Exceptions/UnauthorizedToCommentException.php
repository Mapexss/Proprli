<?php

namespace App\Core\Domain\Exceptions;

class UnauthorizedToCommentException extends \Exception
{
    /**
     * UnauthorizedToCommentException constructor.
     */
    public function __construct()
    {
        parent::__construct("Users cannot comment in tasks from other teams");
    }
}
