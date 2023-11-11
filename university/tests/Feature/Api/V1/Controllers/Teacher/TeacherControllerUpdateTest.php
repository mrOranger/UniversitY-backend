<?php

namespace Tests\Feature\Api\V1\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeacherControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
    }

    final public function test_update_teacher_without_authentication_returns_unauthenticated(): void
    {
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);
        $response = $this->putJson($route, []);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_update_teacher_returns_unauthorized(): void
    {
        $user = User::factory()->createQuietly(['role' => 'student']);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_update_teacher_without_role_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The role field is required.');
        $response->assertJsonPath('errors.role.0', 'The role field is required.');
    }

    final public function test_update_teacher_with_role_not_string_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 1234,
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.role.0', 'The role field must be a string.');
        $response->assertJsonPath('errors.role.1', 'The selected role is invalid.');
    }

    final public function test_update_teacher_with_role_invalid_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'student',
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The selected role is invalid.');
        $response->assertJsonPath('errors.role.0', 'The selected role is invalid.');
    }

    final public function test_update_teacher_without_subject_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'researcher',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The subject field is required.');
        $response->assertJsonPath('errors.subject.0', 'The subject field is required.');
    }

    final public function test_update_teacher_with_subject_not_string_returns_unprocessable_content(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'researcher',
                'subject' => 1234,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The subject field must be a string.');
        $response->assertJsonPath('errors.subject.0', 'The subject field must be a string.');
    }

    final public function test_update_teacher_returns_teacher_not_found(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        User::factory()->createQuietly([
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '01/01/2000',
            'email' => 'mario.rossi@gmail.com',
        ]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'associate',
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Teacher 1 does not exists.');
    }

    final public function test_update_teacher_returns_user_not_found(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $route = route('teachers.update', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'associate',
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Associated user does not exists.');
    }

    final public function test_update_teacher_returns_createQuietlyd(): void
    {
        $user = User::factory()->createQuietly(['role' => $this->roles->random()]);
        $teacher = Teacher::factory()->createQuietly([
            'role' => 'associate',
            'subject' => 'Artificial Intelligence',
            'user_id' => User::factory()->createQuietly([
                'first_name' => 'Mario',
                'last_name' => 'Rossi',
                'birth_date' => '01/01/2000',
                'email' => 'mario.rossi@gmail.com',
            ])->id,
        ]);
        $route = route('teachers.update', [
            'teacher' => $teacher->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson($route, [
                'user' => [
                    'first_name' => 'Mario',
                    'last_name' => 'Rossi',
                    'birth_date' => '01/01/2000',
                    'email' => 'mario.rossi@gmail.com',
                ],
                'role' => 'associate',
                'subject' => 'Artificial Intelligence',
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
