<?php

namespace App\Core\Domain\Exceptions;

class TaskNotFoundException extends \Exception
{
    /**
     * TaskNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Task not found with provided params');
    }
}
