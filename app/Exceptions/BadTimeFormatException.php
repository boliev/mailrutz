<?php

namespace App\Exceptions;

class BadTimeFormatException extends HTTPException
{
    public $code = 400;
    public $message = 'The time field must be in \'Y-m-d H:i:s\' format';
}
