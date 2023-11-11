<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CourseControllerGetAllTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->route = route('courses.index');
        $this->roles = collect(['admin', 'employee', 'professor']);
    }

    final public function test_get_all_courses_without_authentication_returns_unauthenticated(): void
    {
        $response = $this->getJson($this->route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_get_all_courses_as_student_returns_unauthorized(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => 'student']))
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_all_courses_returns_empty_response(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    final public function test_get_all_courses_returns_one_course(): void
    {
        $course = Course::factory()->createQuietly();

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->getJson($this->route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $course->id);
        $response->assertJsonPath('data.0.name', $course->name);
        $response->assertJsonPath('data.0.sector', $course->sector);
        $response->assertJsonPath('data.0.starting_date', $course->starting_date);
        $response->assertJsonPath('data.0.ending_date', $course->ending_date);
        $response->assertJsonPath('data.0.cfu', $course->cfu);
        $response->assertJsonPath('data.0.professor.id', $course->professor->id);
        $response->assertJsonPath('data.0.professor.role', $course->professor->role);
        $response->assertJsonPath('data.0.professor.subject', $course->professor->subject);
        $response->assertJsonPath('data.0.professor.user.first_name', $course->professor->user->first_name);
        $response->assertJsonPath('data.0.professor.user.last_name', $course->professor->user->last_name);
        $response->assertJsonPath('data.0.professor.user.email', $course->professor->user->email);
        $response->assertJsonPath('data.0.professor.user.birth_date', $course->professor->user->birth_date);
    }

    final public function test_get_all_courses_many_courses(): void
    {
        $courses = Course::factory(100)->createQuietly();

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->getJson($this->route);

        $response->assertJsonCount(100, 'data');
        $courses->each(function (Course $course, int $index) use ($response) {
            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonPath('data.'.$index.'.id', $course->id);
            $response->assertJsonPath('data.'.$index.'.name', $course->name);
            $response->assertJsonPath('data.'.$index.'.sector', $course->sector);
            $response->assertJsonPath('data.'.$index.'.starting_date', $course->starting_date);
            $response->assertJsonPath('data.'.$index.'.ending_date', $course->ending_date);
            $response->assertJsonPath('data.'.$index.'.cfu', $course->cfu);
            $response->assertJsonPath('data.'.$index.'.professor.id', $course->professor->id);
            $response->assertJsonPath('data.'.$index.'.professor.role', $course->professor->role);
            $response->assertJsonPath('data.'.$index.'.professor.subject', $course->professor->subject);
            $response->assertJsonPath('data.'.$index.'.professor.user.first_name', $course->professor->user->first_name);
            $response->assertJsonPath('data.'.$index.'.professor.user.last_name', $course->professor->user->last_name);
            $response->assertJsonPath('data.'.$index.'.professor.user.email', $course->professor->user->email);
            $response->assertJsonPath('data.'.$index.'.professor.user.birth_date', $course->professor->user->birth_date);
        });
    }
}
