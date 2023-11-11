<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerConfirmAccountTest extends TestCase
{
    use RefreshDatabase;

    private string $route;
    private string $confirmationCode;

    public function setUp() : void
    {
        parent::setUp();
        $this->confirmationCode = fake()->uuid();
    }

    public function test_confirm_account_with_not_existing_user_returns_not_found () : void
    {
        $route = 'api/v1/auth/confirm/user/1/code/ab123';

        $response = $this->patchJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Unknown user.');
    }

    public function test_confirm_account_with_invalid_confirmation_code_returns_conflict () : void
    {
        $user = User::factory()->createQuietly([
            'confirmation' => $this->confirmationCode
        ]);
        $route = "api/v1/auth/confirm/user/$user->id/code/ab123";

        $response = $this->patchJson($route);

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJsonPath('message', 'Invalid confirmation code.');
    }

    public function test_confirm_account_with_user_already_confirmed_returns_conflict () : void
    {
        $user = User::factory()->createQuietly([
            'confirmation' => null
        ]);
        $route = "api/v1/auth/confirm/user/$user->id/code/$this->confirmationCode";

        $response = $this->patchJson($route);

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJsonPath('message', 'Account already confirmed.');
    }

    public function test_confirm_account_with_valid_confirmation_code_returns_ok () : void
    {
        $user = User::factory()->createQuietly([
            'confirmation' => $this->confirmationCode
        ]);
        $route = "api/v1/auth/confirm/user/$user->id/code/$this->confirmationCode";

        $response = $this->patchJson($route);

        $userConfimedAccount = User::find($user->id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('message', 'Account confirmed successfully.');
        $this->assertNull($userConfimedAccount->confirmation);
    }
}
