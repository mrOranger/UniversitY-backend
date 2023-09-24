<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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
        $admin = User::factory()->create([ 'role' => 'admin' ]);
        $degree = Degree::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $degree->id);
        $response->assertJsonPath('data.0.name', $degree->name);
        $response->assertJsonPath('data.0.code', $degree->code);
        $response->assertJsonPath('data.0.course_type', $degree->course_type);
    }

    public function test_get_all_degrees_as_admin_returns_many_degrees () : void
    {
        $admin = User::factory()->create([ 'role' => 'admin' ]);
        $degrees = Degree::factory(100)->create();

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $degrees->each(function (Degree $degree, int $index) use ($response) {
            $response->assertJsonPath('data.' . $index . '.id', $degree->id);
            $response->assertJsonPath('data.' . $index . '.name', $degree->name);
            $response->assertJsonPath('data.' . $index . '.code', $degree->code);
            $response->assertJsonPath('data.' . $index . '.course_type', $degree->course_type);
        });
    }
    public function test_get_all_degrees_as_employee_returns_empty_response () : void
    {
        $employee = User::factory()->create([ 'role' => 'employee' ]);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data', []);
    }

    public function test_get_all_degrees_as_employee_returns_one_course () : void
    {
        $employee = User::factory()->create([ 'role' => 'employee' ]);
        $degree = Degree::factory()->create();

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $degree->id);
        $response->assertJsonPath('data.0.name', $degree->name);
        $response->assertJsonPath('data.0.code', $degree->code);
        $response->assertJsonPath('data.0.course_type', $degree->course_type);
    }

    public function test_get_all_degrees_as_employee_returns_many_degrees () : void
    {
        $employee = User::factory()->create([ 'role' => 'employee' ]);
        $degrees = Degree::factory(100)->create();

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $degrees->each(function (Degree $degree, int $index) use ($response) {
            $response->assertJsonPath('data.' . $index . '.id', $degree->id);
            $response->assertJsonPath('data.' . $index . '.name', $degree->name);
            $response->assertJsonPath('data.' . $index . '.code', $degree->code);
            $response->assertJsonPath('data.' . $index . '.course_type', $degree->course_type);
        });
    }
}
