<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DegreeControllerGetAllTest extends TestCase
{

    use RefreshDatabase;

    private string $test_url;

    public function setUp() : void
    {
        parent::setUp();
        $this->test_url = 'api/v1/degrees';
    }

    public function test_get_all_degrees_without_authentication_returns_unauthenticated () : void
    {
        $response = $this->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_get_all_degrees_as_student_returns_unauthorized () : void
    {
        $student = User::factory()->create([ 'role' => 'student' ]);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_all_degrees_as_professor_returns_unauthorized () : void
    {
        $professor = User::factory()->create([ 'role' => 'professor' ]);

        $response = $this
            ->actingAs($professor)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_all_degrees_as_admin_returns_empty_response () : void
    {
        $admin = User::factory()->create([ 'role' => 'admin' ]);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data', []);
    }

    public function test_get_all_degrees_as_admin_returns_one_course () : void
    {

    }

    public function test_get_all_degrees_as_admin_returns_many_degrees () : void
    {

    }
    public function test_get_all_degrees_as_employee_returns_empty_response () : void
    {

    }

    public function test_get_all_degrees_as_employee_returns_one_course () : void
    {

    }

    public function test_get_all_degrees_as_employee_returns_many_degrees () : void
    {

    }
}
