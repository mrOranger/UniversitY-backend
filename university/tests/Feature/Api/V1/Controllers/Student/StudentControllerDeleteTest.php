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

    final public function test_delete_user_by_id_without_authentication_returns_unauthenticated(): void
    {
        $route = route('students.destroy', [
            'student' => 1,
        ]);

        $response = $this->deleteJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_delete_student_by_id_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->createQuietly(['role' => 'student']);
        $route = route('students.destroy', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($student)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_delete_student_by_id_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->createQuietly(['role' => 'professor']);
        $route = route('students.destroy', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($professor)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_delete_student_by_id_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->createQuietly(['role' => 'admin']);
        $route = route('students.destroy', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($admin)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_delete_student_by_id_as_admin_returns_ok(): void
    {
        $degree = Degree::factory()->createQuietly();
        $admin = User::factory()->createQuietly(['role' => 'admin']);
        $user = User::factory()->createQuietly();
        $degree = Degree::factory()->createQuietly([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->createQuietly([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.destroy', [
            'student' => $student->id,
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
        $response->assertJsonPath('data.user.first_name', $user->first_name);
        $response->assertJsonPath('data.user.last_name', $user->last_name);
        $response->assertJsonPath('data.user.birth_date', $user->birth_date);
        $response->assertJsonPath('data.user.email', $user->email);
    }

    final public function test_delete_student_by_id_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->createQuietly(['role' => 'employee']);
        $route = route('students.destroy', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($employee)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_delete_student_by_id_as_employee_returns_ok(): void
    {
        $employee = User::factory()->createQuietly(['role' => 'employee']);
        $user = User::factory()->createQuietly();
        $degree = Degree::factory()->createQuietly([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->createQuietly([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.destroy', [
            'student' => $student->id,
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
        $response->assertJsonPath('data.user.first_name', $user->first_name);
        $response->assertJsonPath('data.user.last_name', $user->last_name);
        $response->assertJsonPath('data.user.birth_date', $user->birth_date);
        $response->assertJsonPath('data.user.email', $user->email);
    }
}
