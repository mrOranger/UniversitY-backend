<?php

namespace App\Services\Http\Controllers\Api\V1\Auth\Implementation;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Auth\LoginResponse;
use App\Http\Responses\V1\Auth\RegisterResponse;
use App\Http\Responses\V1\InfoResponse;
use App\Http\Responses\V1\Response;
use App\Models\User;
use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as SynfonyResponse;

final class AuthService implements AuthServiceInterface
{
    public function login(LoginRequest $loginRequest) : Response
    {
        $validatedData = $loginRequest->validated();
        $user = User::where('email', $validatedData['email'])->first();
        if(!Hash::check($validatedData['password'], $user->password)) {
            return new InfoResponse('Invalid password.', SynfonyResponse::HTTP_UNAUTHORIZED);
        }
        $device = substr($loginRequest->userAgent() ?? '', 0, 255);
        return new LoginResponse(
            'Login successfull.',
            $user->createToken($device)->plainTextToken,
            config('app.token_expires_at'),
            SynfonyResponse::HTTP_OK
        );
    }
    public function logout (Request $request) : Response
    {
        $request->user()->currentAccessToken()->delete();
        return new InfoResponse('Logout successfull', SynfonyResponse::HTTP_OK);
    }
    public function register(RegisterRequest $registerRequest) : Response
    {
        return new RegisterResponse('Register successfull');
    }
}
