<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CourseControllerSaveTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
    }

    public final function test_save_course_without_authentication_returns_unauthenticated () : void
    {
    }

    public final function test_save_course_returns_unauthorized () : void
    {
    }

    public final function test_save_course_without_role_returns_unprocessable_content () : void
    {
    }

    public final function test_save_course_with_role_not_string_returns_unprocessable_content () : void
    {
    }

    public final function test_save_course_with_role_invalid_returns_unprocessable_content () : void
    {
    }

    public final function test_save_course_without_subject_returns_unprocessable_content () : void
    {
    }

    public final function test_save_course_with_subject_not_string_returns_unprocessable_content () : void
    {
    }

    public final function test_save_course_returns_not_found () : void
    {
    }

    public final function test_save_course_returns_created () : void
    {
        $user = User::factory()->create([
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '01/01/2000',
            'email' => 'mario.rossi@gmail.com',
        ]);
        $teacher = Teacher::factory()->create([
            'user_id' => $user->id,
            'role' => 'full',
            'subject' => 'Computer Science'
        ]);
        $route = route('courses.store');

        $response = $this
            ->actingAs(User::factory()->create(['role' => 'admin']))
            ->postJson($route,  [
                'name' => 'Algorithms & Data Structures',
                'sector' => 'INF-01',
                'starting_date' => '01/09/2023',
                'ending_date' => '01/06/2024',
                'cfu' => '12',
                'professor' => [
                    'role' => $teacher->role,
                    'subject' => $teacher->subject,
                    'user' => [
                        'first_name' => $teacher->user->first_name,
                        'last_name' => $teacher->user->last_name,
                        'email' => $teacher->user->email,
                        'birth_date' => $teacher->user->birth_date,
                    ]
                ],
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
