<?php

namespace Tests\Feature\Api\V1\Student;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    final public function test_update_student_wihtout_authentication_returns_unauthenticated(): void
    {
        $route = route('students.update', [
            'student' => 1,
        ]);
        $response = $this->putJson($route, []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_update_student_as_student_return_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $route = route('students.update', [
            'student' => 1,
        ]);
        $response = $this
            ->actingAs($student)
            ->putJson($route, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_update_student_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $route = route('students.update', [
            'student' => 1,
        ]);
        $response = $this
            ->actingAs($professor)
            ->putJson($route, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_update_student_as_admin_with_bachelor_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 'as',
                'master_final_mark' => null,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'LM-101',
                    'course_type' => 'master',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The bachelor final mark field must be a number.');
        $response->assertJsonPath('errors.bachelor_final_mark.0', 'The bachelor final mark field must be a number.');
    }

    final public function test_update_student_as_admin_with_bachelor_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 65,
                'master_final_mark' => 110,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'LM-101',
                    'course_type' => 'master',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The bachelor final mark field must be at least 66.');
        $response->assertJsonPath('errors.bachelor_final_mark.0', 'The bachelor final mark field must be at least 66.');
    }

    final public function test_update_student_as_admin_with_bachelor_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 116,
                'master_final_mark' => null,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'LM-18',
                    'course_type' => 'master',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The bachelor final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.bachelor_final_mark.0', 'The bachelor final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_admin_with_master_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 'NaN',
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must be a number.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must be a number.');
    }

    final public function test_update_student_as_admin_with_master_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 65,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must be at least 66.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must be at least 66.');
    }

    final public function test_update_student_as_admin_with_master_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 112,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_admin_with_phd_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 'NaN',
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must be a number.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must be a number.');
    }

    final public function test_update_student_as_admin_with_phd_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 62,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must be at least 66.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must be at least 66.');
    }

    final public function test_update_student_as_admin_with_phd_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 198,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_admin_without_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The outside prescribed time field is required.');
        $response->assertJsonPath('errors.outside_prescribed_time.0', 'The outside prescribed time field is required.');
    }

    final public function test_update_student_as_admin_with_not_boolean_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => 'notABoolean',
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The outside prescribed time field must be true or false.');
        $response->assertJsonPath('errors.outside_prescribed_time.0', 'The outside prescribed time field must be true or false.');
    }

    final public function test_update_student_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $route = route('students.update', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_update_student_as_admin_returns_ok(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->putJson($route, [
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'user' => $user,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    final public function test_update_student_as_employee_with_bachelor_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 65,
                'master_final_mark' => 110,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'LM-101',
                    'course_type' => 'master',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The bachelor final mark field must be at least 66.');
        $response->assertJsonPath('errors.bachelor_final_mark.0', 'The bachelor final mark field must be at least 66.');
        $employee = User::factory()->create(['role' => 'employee']);
    }

    final public function test_update_student_as_employee_with_bachelor_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 116,
                'master_final_mark' => null,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'LM-18',
                    'course_type' => 'master',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The bachelor final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.bachelor_final_mark.0', 'The bachelor final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_employee_with_master_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 'NaN',
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must be a number.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must be a number.');
    }

    final public function test_update_student_as_employee_with_master_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 65,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must be at least 66.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must be at least 66.');
    }

    final public function test_update_student_as_employee_with_master_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 112,
                'phd_final_mark' => null,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The master final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.master_final_mark.0', 'The master final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_employee_with_phd_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 'NaN',
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must be a number.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must be a number.');
    }

    final public function test_update_student_as_employee_with_phd_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 62,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must be at least 66.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must be at least 66.');
    }

    final public function test_update_student_as_employee_with_phd_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 198,
                'outside_prescribed_time' => true,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The phd final mark field must not be greater than 110.');
        $response->assertJsonPath('errors.phd_final_mark.0', 'The phd final mark field must not be greater than 110.');
    }

    final public function test_update_student_as_employee_without_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The outside prescribed time field is required.');
        $response->assertJsonPath('errors.outside_prescribed_time.0', 'The outside prescribed time field is required.');
    }

    final public function test_update_student_as_employee_with_not_boolean_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => 'notABoolean',
                'degree' => [
                    'name' => 'Computer Science',
                    'code' => 'PH-18',
                    'course_type' => 'phd',
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The outside prescribed time field must be true or false.');
        $response->assertJsonPath('errors.outside_prescribed_time.0', 'The outside prescribed time field must be true or false.');
    }

    final public function test_update_student_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $route = route('students.update', [
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '1996-05-04',
                    'email' => 'email@example.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_update_student_as_employee_returns_ok(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'LM-101',
            'course_type' => 'master',
        ]);
        $user = User::factory()->create();
        $student = Student::factory()->create([
            'bachelor_final_mark' => 89,
            'master_final_mark' => null,
            'phd_final_mark' => null,
            'outside_prescribed_time' => true,
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $route = route('students.update', [
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($employee)
            ->putJson($route, [
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
                'user' => $user,
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.user.first_name', $user->first_name);
        $response->assertJsonPath('data.user.last_name', $user->last_name);
        $response->assertJsonPath('data.user.birth_date', $user->birth_date);
        $response->assertJsonPath('data.user.email', $user->email);
        $response->assertJsonPath('data.bachelor_final_mark', 98);
        $response->assertJsonPath('data.master_final_mark', 109);
        $response->assertJsonPath('data.phd_final_mark', 109);
        $response->assertJsonPath('data.outside_prescribed_time', true);
        $response->assertJsonPath('data.degree.name', $degree->name);
        $response->assertJsonPath('data.degree.code', $degree->code);
        $response->assertJsonPath('data.degree.course_type', $degree->course_type);
    }
}
