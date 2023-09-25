<?php

namespace App\Exceptions;

use Exception;

class ResourceConflictException extends Exception
{
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
