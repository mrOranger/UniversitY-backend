<?php

namespace App\Services\Http\Controllers\Api\V1\Auth\Implementation;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;

final class AuthService implements AuthServiceInterface
{
    public function login(LoginRequest $loginRequest)
    {

    }
    public function logout (LogoutRequest $logoutRequest)
    {

    }
    public function register(RegisterRequest $registerRequest)
    {

    }
}
