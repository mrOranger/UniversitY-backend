<?php

namespace App\Http\Responses\V1\Auth;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class LoginResponse implements Responsable
{
    final public function toResponse($loginRequest) : JsonResponse
    {
        return response()->json([
            'message' => 'Login successful'
        ]);
    }
}
