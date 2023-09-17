<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Auth\LoginResponse;
use App\Http\Responses\V1\Auth\LogoutResponse;
use App\Http\Responses\V1\Auth\RegisterResponse;
use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;

class AuthController extends Controller
{
    private AuthServiceInterface $authenticationService;

    public function __construct(AuthServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public final function login (LoginRequest $loginRequest) : LoginResponse
    {
        return $this->authenticationService->login($loginRequest);
    }

    public final function logout (LogoutRequest $logoutRequest) : LogoutResponse
    {
        return $this->authenticationService->logout($logoutRequest);
    }

    public final function register (RegisterRequest $registerRequest) : RegisterResponse
    {
        return $this->authenticationService->register($registerRequest);
    }
}
