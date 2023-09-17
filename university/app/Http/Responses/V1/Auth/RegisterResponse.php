<?php

namespace App\Http\Responses\V1\Auth;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class RegisterResponse implements Responsable
{
    final public function toResponse($registerRequest) : JsonResponse
    {
        return response()->json([
            'message' => 'Register successful'
        ]);
    }
}
