<?php

namespace App\Http\Responses\V1;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class InfoResponse implements Responsable
{

    private int $statusCode;
    private string $message;

    public function __construct(string $message, int $statusCode)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function toResponse($request) : JsonResponse
    {
        return response()->json([
            'message' => $this->message
        ], $this->statusCode);
    }
}
