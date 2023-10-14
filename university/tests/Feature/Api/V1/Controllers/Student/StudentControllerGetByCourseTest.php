<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Course;
use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerGetByCourseTest extends TestCase
{
    use RefreshDatabase;

    private string $route;
    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['employee', 'admin']);
        $this->route = route('students.get-students-by-course', [
            'course' => 1
        ]);
    }

    public final function test_get_all_students_by_course_without_authentication_returns_unauthenticated(): void
    {
        $response = $this->getJson($this->route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public final function test_get_all_students_by_course_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public final function test_get_all_students_by_course_as_professor_returns_unauthorized(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public final function test_get_all_students_by_course_returns_not_found(): void
    {
        $admin = User::factory()->create(['role' => $this->roles->random()]);
        $this->route = route('students.get-students-by-course', [
            'course' => 1
        ]);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Course 1 does not exist.');
    }

    public final function test_get_all_students_by_course_returns_empty_response(): void
    {
        $admin = User::factory()->create(['role' => $this->roles->random()]);
        $course = Course::factory()->create();
        $this->route = route('students.get-students-by-course', [
            'course' => $course->id
        ]);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonPath('data', []);
    }

    public final function test_get_all_students_by_course_returns_one_student(): void
    {
        $course = Course::factory()->create();
        $degree = Degree::factory()->create();
        $user = User::factory()->create();
        $student = Student::factory()->create([
            'degree_id' => $degree->id,
            'user_id' => $user->id
        ]);
        $admin = User::factory()->create(['role' => $this->roles->random()]);
        $student->courses()->attach($course);
        $this->route = route('students.get-students-by-course', [
            'course' => $course->id
        ]);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->route);

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

    public final function test_get_all_students_by_course_returns_many_students(): void
    {
        $course = Course::factory()->create();
        $degree = Degree::factory()->create();
        $user = User::factory()->create();
        $students = Student::factory(100)->create([
            'degree_id' => $degree->id,
            'user_id' => $user->id
        ]);
        $admin = User::factory()->create(['role' => $this->roles->random()]);
        $students->each(function (Student $student) use ($course) {
            $student->courses()->attach($course);
        });

        $this->route = route('students.get-students-by-course', [
            'course' => $course->id
        ]);

        $response = $this
            ->actingAs($admin)
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $students->each(function (Student $student, int $index) use ($response) {
            $response->assertJsonPath('data.' . $index . '.id', $student->id);
            $response->assertJsonPath('data.' . $index . '.bachelor_final_mark', $student->bachelor_final_mark);
            $response->assertJsonPath('data.' . $index . '.master_final_mark', $student->master_final_mark);
            $response->assertJsonPath('data.' . $index . '.phd_final_mark', $student->phd_final_mark);
            $response->assertJsonPath('data.' . $index . '.outside_prescribed_time', $student->outside_prescribed_time);
            $response->assertJsonPath('data.' . $index . '.degree.id', $student->degree->id);
            $response->assertJsonPath('data.' . $index . '.degree.name', $student->degree->name);
            $response->assertJsonPath('data.' . $index . '.degree.code', $student->degree->code);
            $response->assertJsonPath('data.' . $index . '.degree.course_type', $student->degree->course_type);
            $response->assertJsonPath('data.' . $index . '.user.first_name', $student->user->first_name);
            $response->assertJsonPath('data.' . $index . '.user.last_name', $student->user->last_name);
            $response->assertJsonPath('data.' . $index . '.user.birth_date', $student->user->birth_date);
            $response->assertJsonPath('data.' . $index . '.user.email', $student->user->email);
        });
    }
}
