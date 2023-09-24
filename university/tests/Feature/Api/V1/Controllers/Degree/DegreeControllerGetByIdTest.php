<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DegreeControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/degrees/';
    }

    final public function test_get_degree_by_id_without_authentication_returns_unauthenticated(): void
    {
        $degree = Degree::factory()->create();

        $response = $this->getJson($this->test_url.$degree->id);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_get_degree_by_id_as_student_returns_unauthorized(): void
    {
        $degree = Degree::factory()->create();
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url.$degree->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_degree_by_id_as_professor_returns_unauthorized(): void
    {
        $degree = Degree::factory()->create();
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($this->test_url.$degree->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_degree_by_id_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url.'0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Degree 0 does not exist.');
    }

    final public function test_get_degree_by_id_as_admin_returns_ok(): void
    {
        $degree = Degree::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url.$degree->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $degree->id);
        $response->assertJsonPath('data.name', $degree->name);
        $response->assertJsonPath('data.code', $degree->code);
        $response->assertJsonPath('data.course_type', $degree->course_type);
    }

    final public function test_get_degree_by_id_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url.'0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Degree 0 does not exist.');
    }

    final public function test_get_degree_by_id_as_employee_returns_ok(): void
    {
        $degree = Degree::factory()->create();
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url.$degree->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $degree->id);
        $response->assertJsonPath('data.name', $degree->name);
        $response->assertJsonPath('data.code', $degree->code);
        $response->assertJsonPath('data.course_type', $degree->course_type);
    }
}
