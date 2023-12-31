<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerRegisterTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->test_url = 'api/v1/auth/register';
    }

    final public function test_register_without_credentials_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.first_name.0', 'The first name field is required.');
        $response->assertJsonPath('errors.last_name.0', 'The last name field is required.');
        $response->assertJsonPath('errors.birth_date.0', 'The birth date field is required.');
        $response->assertJsonPath('errors.email.0', 'The email field is required.');
        $response->assertJsonPath('errors.password.0', 'The password field is required.');
        $response->assertJsonPath('errors.password_confirmation.0', 'The password confirmation field is required.');
        $response->assertJsonPath('errors.role.0', 'The role field is required.');
    }

    final public function test_register_without_email_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.email.0', 'The email field is required.');
    }

    final public function test_register_without_first_name_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.first_name.0', 'The first name field is required.');
    }

    final public function test_register_without_last_name_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.last_name.0', 'The last name field is required.');
    }

    final public function test_register_without_birth_date_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.birth_date.0', 'The birth date field is required.');
    }

    final public function test_register_without_password_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.password.0', 'The password field is required.');
    }

    final public function test_register_without_password_confirm_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.password_confirmation.0', 'The password confirmation field is required.');
    }

    final public function test_register_with_not_valid_email_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'notavalidaemail',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The email field must be a valid email address.');
    }

    final public function test_register_with_not_valid_first_name_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 1234,
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The first name field must be a string.');
    }

    final public function test_register_with_not_valid_last_name_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 1234,
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The last name field must be a string.');
    }

    final public function test_register_with_not_valid_birth_date_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => 'birt-date',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The birth date field must be a valid date.');
    }

    final public function test_register_with_not_valid_password_min_length_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'Paswo1!',
            'password_confirmation' => 'Paswo1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must be at least 8 characters.');
    }

    final public function test_register_with_not_valid_password_letters_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'T123123123122312!',
            'password_confirmation' => 'T123123123122312!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one uppercase and one lowercase letter.');
    }

    final public function test_register_with_not_valid_password_numbers_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword!',
            'password_confirmation' => 'ThisIsAValidPassword!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one number.');
    }

    final public function test_register_with_not_valid_password_symbols_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1',
            'password_confirmation' => 'ThisIsAValidPassword1',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one symbol.');
    }

    final public function test_register_with_not_valid_password_confirm_matching_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPasswor1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password confirmation field must match password.');
    }

    final public function test_register_with_password_compromised_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The given password has appeared in a data leak. Please choose a different password.');
    }

    final public function test_register_with_not_valid_role_returns_unprocessable_content(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'undefined',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The selected role is invalid.');
    }

    final public function test_register_with_valid_credentials_returns_ok(): void
    {
        $response = $this->postJson($this->test_url, [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['message', 'user']);
        $response->assertJsonPath('message', 'Register successfull.');
        $response->assertJsonPath('user.first_name', 'Mario');
        $response->assertJsonPath('user.last_name', 'Rossi');
        $response->assertJsonPath('user.birth_date', '1996-05-04');
        $response->assertJsonPath('user.email', 'email@example.com');
    }
}
