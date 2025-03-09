<?php

namespace App\Core\Domain\Exceptions;

class AssignedUserNotFoundException extends \Exception
{
    /**
     * AssignedUserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Assigned user not found with provided params');
    }
}
