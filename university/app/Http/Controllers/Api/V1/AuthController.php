<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ConfirmAccountRequest;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Response;
use App\Services\Http\Controllers\Api\V1\Auth\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthServiceInterface $authenticationService;

    public function __construct(AuthServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    final public function login(LoginRequest $loginRequest): Response
    {
        return $this->authenticationService->login($loginRequest);
    }

    final public function logout(Request $logoutRequest): Response
    {
        return $this->authenticationService->logout($logoutRequest);
    }

    final public function register(RegisterRequest $registerRequest): Response
    {
        return $this->authenticationService->register($registerRequest);
    }

    final public function confirmAccount (string $userId, string $confirmationCode) : Response
    {
        return $this->authenticationService->confirmAccount($userId, $confirmationCode);
    }
}
