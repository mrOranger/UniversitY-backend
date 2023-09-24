<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function getMessage() : string
    {
        return 'Resource Not Found.';
    }
}
