<?php

namespace App\Core\Domain\Exceptions;

class InvalidTaskStatusException extends \Exception
{
    /**
     * InvalidTaskStatusException constructor.
     */
    public function __construct()
    {
        parent::__construct('An invalid status was provided');
    }
}
