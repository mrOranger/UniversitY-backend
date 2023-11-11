<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerConfirmAccountTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    public function test_confirm_account_with_not_existing_user_returns_unprocessable_content () : void
    {
        $route = 'api/v1/auth/confirm/user/1/code/ab123';

        $response = $this->patchJson($route);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'Not a valid response');
    }

    public function test_confirm_account_with_invalid_confirmation_code_returns_unprocessable_content () : void
    {

    }

    public function test_confirm_account_without_confirmation_code_returns_unprocessable_content () : void
    {

    }

    public function test_confirm_account_with_user_already_confirmed_returns_conflict () : void
    {

    }

    public function test_confirm_account_with_invalid_confirmation_code_returns_conflict () : void
    {

    }

    public function test_confirm_account_with_invalid_confirmation_code_returns_ok () : void
    {

    }
}
