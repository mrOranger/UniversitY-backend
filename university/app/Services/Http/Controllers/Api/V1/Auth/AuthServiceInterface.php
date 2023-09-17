<?php
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\LogoutRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;

interface AuthServiceInterface {
    public function login(LoginRequest $loginRequest);
    public function logout (LogoutRequest $logoutRequest);
    public function register(RegisterRequest $registerRequest);
}
