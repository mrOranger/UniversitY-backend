<?php

namespace App\Services\Http\Controllers\Api\V1\Auth\Implementation;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
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
    public function login(LoginRequest $loginRequest): Response
    {
        $validatedData = $loginRequest->validated();
        $user = User::where('email', $validatedData['email'])->first();
        if (! Hash::check($validatedData['password'], $user->password)) {
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

    public function logout(Request $request): Response
    {
        $request->user()->tokens()->delete();

        return new InfoResponse('Logout successfull.', SynfonyResponse::HTTP_OK);
    }

    public function register(RegisterRequest $registerRequest): Response
    {
        $validatedRequest = $registerRequest->validated();

        $user = User::create([
            'first_name' => $validatedRequest['first_name'],
            'last_name' => $validatedRequest['last_name'],
            'birth_date' => $validatedRequest['birth_date'],
            'email' => $validatedRequest['email'],
            'password' => Hash::make($validatedRequest['password']),
            'role' => $validatedRequest['role'],
        ]);

        return new RegisterResponse('Register successfull.', SynfonyResponse::HTTP_OK, $user);
    }

    public function confirmAccount(string $userId, string $confirmationCode): Response
    {
        $user = User::find($userId);

        if ($user == null) {
            throw new ResourceNotFoundException('Unknown user.');
        }

        $userConfirmationCode = $user->confirmation;

        if ($userConfirmationCode == null) {
            throw new ResourceConflictException('Account already confirmed.');
        }

        if ($confirmationCode == $userConfirmationCode) {
            $user->update(['confirmation' => null]);

            return new InfoResponse('Account confirmed successfully.', SynfonyResponse::HTTP_OK);
        }

        throw new ResourceConflictException('Invalid confirmation code.');
    }
}
