<?php

namespace App\Exceptions;

use Exception;

class NotEnoughPermissionException extends Exception
{
    public function __construct()
    {
        parent::__construct(trans('You do not have enough permissions to perform this action.'));
    }
}
