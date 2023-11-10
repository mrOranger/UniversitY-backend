<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerConfirmAccountTest extends TestCase
{
    private string $route;

    public function test_confirm_account_without_user_returns_unprocessable_content () : void
    {

    }

    public function test_confirm_account_with_not_existing_user_returns_unprocessable_content () : void
    {

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
