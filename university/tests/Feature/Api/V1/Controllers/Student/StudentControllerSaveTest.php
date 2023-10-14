<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Degree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerSaveTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/students/';
    }

    final public function test_save_student_without_authentication_returns_unauthenticated(): void
    {
        $route = route('students.store');
        $response = $this->postJson($route, []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_save_student_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $route = route('students.store');
        $response = $this
            ->actingAs($student)
            ->postJson($route, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_save_student_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $route = route('students.store');
        $response = $this
            ->actingAs($professor)
            ->postJson($route, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_save_student_as_admin_with_bachelor_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_bachelor_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_bachelor_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_master_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_master_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_master_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_phd_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_phd_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_phd_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_without_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_with_not_boolean_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
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

    final public function test_save_student_as_admin_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'PH-18',
            'course_type' => 'phd',
        ]);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Associated user does not exists.');
    }

    final public function test_save_student_as_admin_returns_created(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'PH-18',
            'course_type' => 'phd',
        ]);
        $route = route('students.store');

        $response = $this
            ->actingAs($admin)
            ->postJson($route, [
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'birth_date' => $user->birth_date,
                    'email' => $user->email,
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    final public function test_save_student_as_employee_with_bachelor_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
                'first_name' => 'Mario',
                'last_name' => 'Rossi',
                'birth_date' => '1996-05-04',
                'email' => 'email@example.com',
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

    final public function test_save_student_as_employee_with_bachelor_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_master_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_master_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_master_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_phd_final_mark_not_numeric_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_phd_final_mark_less_than_66_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_phd_final_mark_greater_than_110_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_without_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_with_not_boolean_outside_prescribed_time_returns_unprocessable_content(): void
    {
        $employee = User::factory()->create(['role' => 'admin']);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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

    final public function test_save_student_as_employee_returns_not_found(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'PH-18',
            'course_type' => 'phd',
        ]);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
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
        $response->assertJsonPath('message', 'Associated user does not exists.');
    }

    final public function test_save_student_as_employee_returns_created(): void
    {
        $employee = User::factory()->create(['role' => 'employee']);
        $user = User::factory()->create();
        $degree = Degree::factory()->create([
            'name' => 'Computer Science',
            'code' => 'PH-18',
            'course_type' => 'phd',
        ]);
        $route = route('students.store');

        $response = $this
            ->actingAs($employee)
            ->postJson($route, [
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'birth_date' => $user->birth_date,
                    'email' => $user->email,
                ],
                'bachelor_final_mark' => 98,
                'master_final_mark' => 109,
                'phd_final_mark' => 109,
                'outside_prescribed_time' => true,
                'degree' => $degree,
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
