<?php

namespace App\Services\Http\Controllers\Api\V1\Auth\Implementation;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Auth\LoginResponse;
use App\Http\Responses\V1\Auth\LogoutResponse;
use App\Http\Responses\V1\Auth\RegisterResponse;
use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;

final class AuthService implements AuthServiceInterface
{
    public function login(LoginRequest $loginRequest) : LoginResponse
    {
        return new LoginResponse();
    }
    public function logout (LogoutRequest $logoutRequest) : LogoutResponse
    {
        return new LogoutResponse();
    }
    public function register(RegisterRequest $registerRequest) : RegisterResponse
    {
        return new RegisterResponse();
    }
}
