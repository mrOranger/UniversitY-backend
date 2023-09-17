<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerRegistertTest extends TestCase
{
    use RefreshDatabase;

    public final function test_register_without_credentials_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_email_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_first_name_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_last_name_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_birth_date_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_password_returns_unprocessable_content () : void
    {

    }
    public final function test_register_without_password_confirm_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_email_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_known_email_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_first_name_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_last_name_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_birth_date_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_min_length_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_max_length_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_letters_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_numbers_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_symbols_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_not_valid_password_confirm_returns_unprocessable_content () : void
    {

    }
    public final function test_register_with_valid_credentials_returns_okauy () : void
    {

    }
}
