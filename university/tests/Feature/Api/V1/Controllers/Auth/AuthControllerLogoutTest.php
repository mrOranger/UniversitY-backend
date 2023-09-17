<?php

namespace Tests\Feature\Api\V1\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerLogoutTest extends TestCase
{
    use RefreshDatabase;
    private string $test_url;
    public function setUp() : void
    {
        parent::setUp();
        $this->test_url = 'api/v1/auth/logout';
    }
    public final function test_logout_for_unauthenticated_user_returns_unauthenticated () : void
    {
        $response = $this->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public final function test_logout_for_authenticated_user_returns_ok () : void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('message', 'Logout successfull.');
    }
}
