<?php

namespace App\Http\Responses\V1\Auth;
use App\Http\Responses\V1\Response;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class RegisterResponse extends Response implements Responsable
{
    private string $message;
    private int $statusCode;
    private User $user;

    public function __construct(string $message, int $statusCode, User $user)
    {
        $this->user = $user;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
    final public function toResponse($registerRequest) : JsonResponse
    {
        return response()->json([
            'message' => $this->message,
            'user' => $this->user
        ], $this->statusCode);
    }
}
