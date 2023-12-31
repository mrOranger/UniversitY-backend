<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerLoginTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/auth/login';
    }

    final public function test_login_without_email_and_password_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.email.0', 'The email field is required.');
        $response->assertJsonPath('errors.password.0', 'The password field is required.');
    }

    final public function test_login_without_email_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'password' => $user->password,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The email field is required.');
    }

    final public function test_login_without_password_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field is required.');
    }

    final public function test_login_with_not_valid_email_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly([
            'password' => 'ThisIsAValidPassword1!',
        ]);

        $response = $this->postJson($this->test_url, [
            'email' => 'notavalidemail',
            'password' => $user->password,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The email field must be a valid email address.');
    }

    final public function test_login_with_unknown_email_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly([
            'password' => 'ThisIsAValidPassword1!',
        ]);

        $response = $this->postJson($this->test_url, [
            'email' => 'unknown@email.com',
            'password' => $user->password,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The selected email is invalid.');
    }

    final public function test_login_with_not_valid_password_min_length_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'Paw1!',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must be at least 8 characters.');
    }

    final public function test_login_with_not_valid_password_letters_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'P1!1111111',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one uppercase and one lowercase letter.');
    }

    final public function test_login_with_not_valid_password_numbers_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'Password!!',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one number.');
    }

    final public function test_login_with_not_valid_password_symbols_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'Password12',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The password field must contain at least one symbol.');
    }

    final public function test_login_with_invalid_password_matching_returns_unauthorized(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'ThisIsNotThwRightPassword1!',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Invalid password.');
    }

    final public function test_login_with_valid_credentials_returns_okay(): void
    {
        $user = User::factory()->createQuietly([
            'password' => Hash::make('ThisIsAValidPassword1!'),
        ]);

        $response = $this->postJson($this->test_url, [
            'email' => $user->email,
            'password' => 'ThisIsAValidPassword1!',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('message', 'Login successfull.');
        $response->assertJsonStructure([
            'message', 'token', 'expires_at',
        ]);
    }
}
