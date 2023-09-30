<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    final public function test_delete_degree_by_id_without_authentication_returns_unauthenticated(): void
    {
        $route = route('students.destroy', [
            'student' => 1
        ]);

        $response = $this->deleteJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }
    final public function test_delete_degree_by_id_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $route = route('students.destroy', [
            'student' => 1
        ]);

        $response = $this
            ->actingAs($student)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }
    final public function test_delete_degree_by_id_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $route = route('students.destroy', [
            'student' => 1
        ]);

        $response = $this
            ->actingAs($professor)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }
    final public function test_delete_degree_by_id_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.destroy', [
            'student' => 1
        ]);

        $response = $this
            ->actingAs($admin)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }
    final public function test_delete_degree_by_id_as_admin_returns_ok(): void
    {
        $degree = Degree::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master'
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id
        ]);
        $route = route('students.destroy', [
            'student' => $student->id
        ]);

        $response = $this
            ->actingAs($admin)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.degree.name', $degree->name);
        $response->assertJsonPath('data.degree.code', $degree->code);
        $response->assertJsonPath('data.degree.course_type', $degree->course_type);
    }

    final public function test_delete_degree_by_id_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.destroy', [
            'student' => 1
        ]);

        $response = $this
            ->actingAs($employee)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }
    final public function test_delete_degree_by_id_as_employee_returns_ok(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master'
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id
        ]);
        $route = route('students.destroy', [
            'student' => $student->id
        ]);

        $response = $this
            ->actingAs($employee)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.degree.name', $degree->name);
        $response->assertJsonPath('data.degree.code', $degree->code);
        $response->assertJsonPath('data.degree.course_type', $degree->course_type);
    }
}
