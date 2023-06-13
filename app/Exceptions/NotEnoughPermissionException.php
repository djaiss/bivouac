<?php

namespace App\Exceptions;

use Exception;

class NotEnoughPermissionException extends Exception
{
    public function __construct($message = 'You do not have enough permissions to perform this action.')
    {
        parent::__construct($message);
    }
}
