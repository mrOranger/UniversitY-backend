<?php

namespace App\Services\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\V1\Auth\ConfirmAccountRequest;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Responses\V1\Response;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function login(LoginRequest $loginRequest): Response;

    public function logout(Request $request): Response;

    public function register(RegisterRequest $registerRequest): Response;

    public function confirmAccount(string $userId, string $confirmationCode) : Response;
}
