<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerLogoutTest extends TestCase
{
    use RefreshDatabase;

    public final function test_logout_without_email_and_password_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_without_email_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_without_password_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_email_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_unknown_email_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_password_min_length_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_password_max_length_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_password_letters_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_password_numbers_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_not_valid_password_symbols_returns_unprocessable_content () : void
    {

    }
    public final function test_logout_with_valid_email_and_password_returns_okau () : void
    {

    }
}
