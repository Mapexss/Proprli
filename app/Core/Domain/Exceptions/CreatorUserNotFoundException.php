<?php

namespace App\Core\Domain\Exceptions;

class CreatorUserNotFoundException extends \Exception
{
    /**
     * CreatorUserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Creator user not found with provided params');
    }
}
