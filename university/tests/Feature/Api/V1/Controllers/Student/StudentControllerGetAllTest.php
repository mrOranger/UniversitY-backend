<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerGetAllTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/students';
    }

    final public function test_get_all_students_without_authentication_returns_unauthenticated(): void
    {
        $response = $this->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_get_all_students_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->createQuietly(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_all_students_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->createQuietly(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_all_students_as_admin_returns_empty_response(): void
    {
        $admin = User::factory()->createQuietly(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonPath('data', []);
    }

    final public function test_get_all_students_ad_admin_returns_one_student(): void
    {
        $degree = Degree::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $student = Student::factory()->createQuietly([
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $admin = User::factory()->createQuietly(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $student->id);
        $response->assertJsonPath('data.0.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.0.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.0.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.0.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.0.degree.id', $degree->id);
        $response->assertJsonPath('data.0.degree.name', $degree->name);
        $response->assertJsonPath('data.0.degree.code', $degree->code);
        $response->assertJsonPath('data.0.degree.course_type', $degree->course_type);
        $response->assertJsonPath('data.0.user.first_name', $user->first_name);
        $response->assertJsonPath('data.0.user.last_name', $user->last_name);
        $response->assertJsonPath('data.0.user.birth_date', $user->birth_date);
        $response->assertJsonPath('data.0.user.email', $user->email);
    }

    final public function test_get_all_students_ad_admin_returns_many_students(): void
    {
        $degree = Degree::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $students = Student::factory(100)->createQuietly([
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $admin = User::factory()->createQuietly(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $students->each(function (Student $student, int $index) use ($response) {
            $response->assertJsonPath('data.'.$index.'.id', $student->id);
            $response->assertJsonPath('data.'.$index.'.bachelor_final_mark', $student->bachelor_final_mark);
            $response->assertJsonPath('data.'.$index.'.master_final_mark', $student->master_final_mark);
            $response->assertJsonPath('data.'.$index.'.phd_final_mark', $student->phd_final_mark);
            $response->assertJsonPath('data.'.$index.'.outside_prescribed_time', $student->outside_prescribed_time);
            $response->assertJsonPath('data.'.$index.'.degree.id', $student->degree->id);
            $response->assertJsonPath('data.'.$index.'.degree.name', $student->degree->name);
            $response->assertJsonPath('data.'.$index.'.degree.code', $student->degree->code);
            $response->assertJsonPath('data.'.$index.'.degree.course_type', $student->degree->course_type);
            $response->assertJsonPath('data.'.$index.'.user.first_name', $student->user->first_name);
            $response->assertJsonPath('data.'.$index.'.user.last_name', $student->user->last_name);
            $response->assertJsonPath('data.'.$index.'.user.birth_date', $student->user->birth_date);
            $response->assertJsonPath('data.'.$index.'.user.email', $student->user->email);
        });
    }

    final public function test_get_all_students_as_employee_returns_empty_response(): void
    {
        $employee = User::factory()->createQuietly(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonPath('data', []);
    }

    final public function test_get_all_students_ad_employee_returns_one_student(): void
    {
        $degree = Degree::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $student = Student::factory()->createQuietly([
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $employee = User::factory()->createQuietly(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $student->id);
        $response->assertJsonPath('data.0.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.0.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.0.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.0.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.0.degree.id', $degree->id);
        $response->assertJsonPath('data.0.degree.name', $degree->name);
        $response->assertJsonPath('data.0.degree.code', $degree->code);
        $response->assertJsonPath('data.0.degree.course_type', $degree->course_type);
        $response->assertJsonPath('data.0.user.first_name', $user->first_name);
        $response->assertJsonPath('data.0.user.last_name', $user->last_name);
        $response->assertJsonPath('data.0.user.birth_date', $user->birth_date);
        $response->assertJsonPath('data.0.user.email', $user->email);

    }

    final public function test_get_all_students_ad_employee_returns_many_students(): void
    {
        $degree = Degree::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $students = Student::factory(100)->createQuietly([
            'degree_id' => $degree->id,
            'user_id' => $user->id,
        ]);
        $employee = User::factory()->createQuietly(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $students->each(function (Student $student, int $index) use ($response) {
            $response->assertJsonPath('data.'.$index.'.id', $student->id);
            $response->assertJsonPath('data.'.$index.'.bachelor_final_mark', $student->bachelor_final_mark);
            $response->assertJsonPath('data.'.$index.'.master_final_mark', $student->master_final_mark);
            $response->assertJsonPath('data.'.$index.'.phd_final_mark', $student->phd_final_mark);
            $response->assertJsonPath('data.'.$index.'.outside_prescribed_time', $student->outside_prescribed_time);
            $response->assertJsonPath('data.'.$index.'.degree.id', $student->degree->id);
            $response->assertJsonPath('data.'.$index.'.degree.name', $student->degree->name);
            $response->assertJsonPath('data.'.$index.'.degree.code', $student->degree->code);
            $response->assertJsonPath('data.'.$index.'.degree.course_type', $student->degree->course_type);
            $response->assertJsonPath('data.'.$index.'.user.first_name', $student->user->first_name);
            $response->assertJsonPath('data.'.$index.'.user.last_name', $student->user->last_name);
            $response->assertJsonPath('data.'.$index.'.user.birth_date', $student->user->birth_date);
            $response->assertJsonPath('data.'.$index.'.user.email', $student->user->email);
        });
    }
}
