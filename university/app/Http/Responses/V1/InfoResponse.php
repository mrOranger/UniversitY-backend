<?php

namespace App\Http\Responses\V1;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class InfoResponse extends Response implements Responsable
{
    private int $statusCode;

    public function __construct(string $message, int $statusCode)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->statusCode);
    }
}
