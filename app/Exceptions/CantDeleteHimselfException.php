<?php

namespace App\Exceptions;

use Exception;

class CantDeleteHimselfException extends Exception
{
    public function __construct()
    {
        parent::__construct(trans('You can not delete yourself.'));
    }
}
