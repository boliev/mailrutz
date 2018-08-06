<?php

namespace App\Exceptions;

use Exception;

class BadTimeFormatException extends Exception
{
    public $code = 400;
    public $message = 'The time field must be in \'Y-m-d H:i:s\' format';
}
