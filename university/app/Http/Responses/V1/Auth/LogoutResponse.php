<?php

namespace App\Http\Responses\V1\Auth;
use App\Http\Responses\V1\Response;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class LogoutResponse extends Response implements Responsable
{
    final public function toResponse($logoutRequest) : JsonResponse
    {
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
