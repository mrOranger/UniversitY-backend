<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DegreeControllerSaveTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/degrees';
    }

    final public function test_save_degree_without_authentication_returns_unauthenticated(): void
    {
        $response = $this->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_save_degree_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_save_degree_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_save_degree_as_admin_returns_name_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field is required.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_name_string_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field must be a string.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_name_max_length(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => Str::random(300),
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field must not be greater than 255 characters.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_name_already_exists(): void
    {
        Degree::factory()->create(['name' => 'Computer Science']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name has already been taken.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_code_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_code_string_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field must be a string.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_code_max_length(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => Str::random(300),
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field must not be greater than 255 characters.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_course_type_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_admin_returns_course_type_string_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
                'course_type' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field must be a string.');
    }

    final public function test_save_degree_as_admin_returns_course_type_not_valid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
                'course_type' => 'diploma',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field must be bachelor, master or phd.');
    }

    final public function test_save_degree_as_employee_returns_name_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field is required.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_name_string_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field must be a string.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_name_max_length(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => Str::random(300),
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name field must not be greater than 255 characters.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_name_already_exists(): void
    {
        Degree::factory()->create(['name' => 'Computer Science']);
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.name.0', 'The name has already been taken.');
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_code_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field is required.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_code_string_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field must be a string.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_code_max_length(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => Str::random(300),
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.code.0', 'The code field must not be greater than 255 characters.');
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_course_type_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field is required.');
    }

    final public function test_save_degree_as_employee_returns_course_type_string_required(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
                'course_type' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field must be a string.');
    }

    final public function test_save_degree_as_employee_returns_course_type_not_valid(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->postJson($this->test_url, [
                'name' => 'Computer Science',
                'code' => 'LM-31',
                'course_type' => 'diploma',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.course_type.0', 'The course type field must be bachelor, master or phd.');
    }
}
