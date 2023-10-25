<?php

namespace App\Exceptions;

use Exception;

class OneOnOneAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct(trans('An one on one already exists between those two users.'));
    }
}
