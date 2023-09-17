<?php

namespace App\Services\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Response;

interface AuthServiceInterface
{
    public function login(LoginRequest $loginRequest) : Response;
    public function logout (LogoutRequest $logoutRequest) : Response;
    public function register(RegisterRequest $registerRequest) : Response;
}
