<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerLoginTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp() : void
    {
        parent::setUp();
        $this->test_url = 'api/v1/auth/login';
    }

    public final function test_login_without_email_and_password_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_without_email_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => $user->password
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_without_password_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'email' => $user->email
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_email_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'email' => 'notavalidemail'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_unknown_email_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'email' => 'unknown@email.com'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_password_min_length_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => 'Paw1!'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_password_max_length_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => Str::random(300)
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_password_letters_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => 'P1!1111111'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_password_numbers_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => 'Password!!'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_not_valid_password_symbols_returns_unprocessable_content () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'password' => 'Password12'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public final function test_login_with_valid_email_and_password_returns_okau () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson($this->test_url, [
            'email' => $user->email,
            'password' => $user->password
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
