<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CourseControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['admin', 'employee', 'professor']);
    }

    final public function test_delete_course_by_id_without_authentication_returns_unauthenticated(): void
    {
        $this->route = route('courses.destroy', [
            'course' => 1,
        ]);

        $response = $this->deleteJson($this->route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_delete_course_by_id_as_student_returns_unauthorized(): void
    {
        $this->route = route('courses.destroy', [
            'course' => 1,
        ]);

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => 'student']))
            ->deleteJson($this->route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_delete_course_by_id_returns_not_found(): void
    {
        $this->route = route('courses.destroy', [
            'course' => 1,
        ]);

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->deleteJson($this->route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Course 1 does not exist.');
    }

    final public function test_delete_course_by_id_returns_ok(): void
    {
        $course = Course::factory()->createQuietly();
        $this->route = route('courses.destroy', [
            'course' => $course->id,
        ]);

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->deleteJson($this->route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $course->id);
        $response->assertJsonPath('data.name', $course->name);
        $response->assertJsonPath('data.sector', $course->sector);
        $response->assertJsonPath('data.starting_date', $course->starting_date);
        $response->assertJsonPath('data.ending_date', $course->ending_date);
        $response->assertJsonPath('data.cfu', $course->cfu);
        $response->assertJsonPath('data.professor.id', $course->professor->id);
        $response->assertJsonPath('data.professor.role', $course->professor->role);
        $response->assertJsonPath('data.professor.subject', $course->professor->subject);
        $response->assertJsonPath('data.professor.user.first_name', $course->professor->user->first_name);
        $response->assertJsonPath('data.professor.user.last_name', $course->professor->user->last_name);
        $response->assertJsonPath('data.professor.user.email', $course->professor->user->email);
        $response->assertJsonPath('data.professor.user.birth_date', $course->professor->user->birth_date);
    }
}
