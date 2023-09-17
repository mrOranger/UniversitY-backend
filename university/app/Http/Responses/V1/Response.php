<?php

namespace App\Http\Responses\V1;
use Illuminate\Contracts\Support\Responsable;

abstract class Response implements Responsable
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
