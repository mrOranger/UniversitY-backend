<?php

namespace App\Http\Responses\V1\Auth;
use App\Http\Responses\V1\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class RegisterResponse extends Response implements Responsable
{
    final public function toResponse($registerRequest) : JsonResponse
    {
        return response()->json([
            'message' => 'Register successful'
        ]);
    }
}
