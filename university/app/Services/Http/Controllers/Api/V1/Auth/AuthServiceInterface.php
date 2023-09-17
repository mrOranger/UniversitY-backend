<?php

namespace App\Services\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Auth\LoginResponse;
use App\Http\Responses\V1\Auth\LogoutResponse;
use App\Http\Responses\V1\Auth\RegisterResponse;

interface AuthServiceInterface
{
    public function login(LoginRequest $loginRequest) : LoginResponse;
    public function logout (LogoutRequest $logoutRequest) : LogoutResponse;
    public function register(RegisterRequest $registerRequest) : RegisterResponse;
}
