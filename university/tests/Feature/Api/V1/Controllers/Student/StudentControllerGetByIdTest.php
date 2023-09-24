<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;
    private string $test_url;
    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/students/';
    }
    final public function test_get_student_by_id_without_authentication_returns_unauthenticated(): void
    {
        $degree = Degree::factory()->create();
        $student = Student::factory()->create([
            'degree_id' => $degree->id
        ]);

        $response = $this->getJson($this->test_url . $student->id);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }
    final public function test_get_student_by_id_as_student_returns_unauthorized(): void
    {
        $degree = Degree::factory()->create();
        Student::factory()->create([
            'degree_id' => $degree->id
        ]);
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url . $student->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }
    final public function test_get_student_by_id_as_professor_returns_unauthorized(): void
    {
        $degree = Degree::factory()->create();
        Student::factory()->create([
            'degree_id' => $degree->id
        ]);
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($this->test_url . $professor->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }
    final public function test_get_student_by_id_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url.'0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 0 does not exist.');
    }
    final public function test_get_student_by_id_as_admin_returns_ok(): void
    {
        $degree = Degree::factory()->create();
        $student = Student::factory()->create([
            'degree_id' => $degree->id
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url.$student->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $student->id);
        $response->assertJsonPath('data.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.degree.id', $degree->id);
        $response->assertJsonPath('data.degree.name', $degree->name);
        $response->assertJsonPath('data.degree.code', $degree->code);
        $response->assertJsonPath('data.degree.course_type', $degree->course_type);
    }
    final public function test_get_student_by_id_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url.'0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 0 does not exist.');
    }
    final public function test_get_student_by_id_as_employee_returns_ok(): void
    {
        $degree = Degree::factory()->create();
        $student = Student::factory()->create([
            'degree_id' => $degree->id
        ]);
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url.$student->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $student->id);
        $response->assertJsonPath('data.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.degree.id', $degree->id);
        $response->assertJsonPath('data.degree.name', $degree->name);
        $response->assertJsonPath('data.degree.code', $degree->code);
        $response->assertJsonPath('data.degree.course_type', $degree->course_type);
    }
}
