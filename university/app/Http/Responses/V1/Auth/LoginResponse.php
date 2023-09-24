<?php

namespace App\Http\Responses\V1\Auth;

use App\Http\Responses\V1\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class LoginResponse extends Response implements Responsable
{
    private string $token;

    private int $tokenExpiresAt;

    private int $status;

    public function __construct(string $message, string $token, int $tokenExpiresAt, int $status)
    {
        parent::__construct($message);
        $this->token = $token;
        $this->status = $status;
        $this->tokenExpiresAt = $tokenExpiresAt;
    }

    final public function toResponse($loginRequest): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'token' => $this->token,
            'expires_at' => $this->tokenExpiresAt,
        ], $this->status);
    }
}
