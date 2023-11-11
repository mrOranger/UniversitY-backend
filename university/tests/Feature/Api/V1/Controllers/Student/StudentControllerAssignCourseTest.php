<?php

namespace Tests\Feature\Api\V1\Controllers\Student;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentControllerAssignCourseTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['admin', 'employee']);
    }

    final public function test_assign_student_to_course_returns_unauthenticated(): void
    {
        $route = route('students.assign-course', [
            'course' => 1,
            'student' => 1,
        ]);

        $response = $this->patchJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_assign_student_to_course_as_student_returns_unauthorized(): void
    {
        $user = User::factory()->createQuietly(['role' => 'student']);
        $route = route('students.assign-course', [
            'course' => 1,
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_assign_student_to_course_as_professor_returns_unauthorized(): void
    {
        $user = User::factory()->createQuietly(['role' => 'professor']);
        $route = route('students.assign-course', [
            'course' => 1,
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_assign_not_existing_student_to_not_existing_course_returns_not_found(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('students.assign-course', [
            'course' => 1,
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_assign_not_existing_student_to_course_returns_not_found(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $course = Course::factory()->createQuietly();
        $route = route('students.assign-course', [
            'course' => $course->id,
            'student' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Student 1 does not exist.');
    }

    final public function test_assign_student_to_not_existing_course_returns_not_found(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $student = Student::factory()->createQuietly();
        $route = route('students.assign-course', [
            'course' => 1,
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Course 1 does not exist.');
    }

    final public function test_assign_student_to_course_returns_ok(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $student = Student::factory()->createQuietly();
        $course = Course::factory()->createQuietly();
        $route = route('students.assign-course', [
            'course' => $course->id,
            'student' => $student->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson($route);

        $response->assertStatus(Response::HTTP_OK);
        \Illuminate\Support\Facades\Log::info($response->baseResponse);
        $response->assertJsonPath('data.id', $student->id);
        $response->assertJsonPath('data.bachelor_final_mark', $student->bachelor_final_mark);
        $response->assertJsonPath('data.master_final_mark', $student->master_final_mark);
        $response->assertJsonPath('data.phd_final_mark', $student->phd_final_mark);
        $response->assertJsonPath('data.outside_prescribed_time', $student->outside_prescribed_time);
        $response->assertJsonPath('data.degree.id', $student->degree->id);
        $response->assertJsonPath('data.degree.name', $student->degree->name);
        $response->assertJsonPath('data.degree.code', $student->degree->code);
        $response->assertJsonPath('data.degree.course_type', $student->degree->course_type);
        $response->assertJsonPath('data.user.first_name', $student->user->first_name);
        $response->assertJsonPath('data.user.last_name', $student->user->last_name);
        $response->assertJsonPath('data.user.birth_date', $student->user->birth_date);
        $response->assertJsonPath('data.user.email', $student->user->email);
        $student->courses()->each(function (Course $course, int $index) use ($response) {
            $response->assertJsonPath('data.courses.'.$index.'.id', $course->id);
            $response->assertJsonPath('data.courses.'.$index.'.name', $course->name);
            $response->assertJsonPath('data.courses.'.$index.'.sector', $course->sector);
            $response->assertJsonPath('data.courses.'.$index.'.starting_date', $course->starting_date);
            $response->assertJsonPath('data.courses.'.$index.'.ending_date', $course->ending_date);
            $response->assertJsonPath('data.courses.'.$index.'.cfu', $course->cfu);
        });
    }
}
