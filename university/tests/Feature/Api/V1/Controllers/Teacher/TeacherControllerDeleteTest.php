<?php

namespace Tests\Feature\Api\V1\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeacherControllerDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
    }

    final public function test_delete_teacher_by_id_without_authentication_returns_unauthenticated(): void
    {
        $route = route('teachers.destroy', [
            'teacher' => 1,
        ]);

        $response = $this->deleteJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_delete_teacher_by_id_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $route = route('teachers.destroy', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($student)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_delete_teacher_by_id_returns_not_found(): void
    {
        $user = User::factory()->create(['role' => $this->roles->random()]);
        $route = route('teachers.destroy', [
            'teacher' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Teacher 1 does not exist.');
    }

    final public function test_delete_teacher_by_id_returns_ok(): void
    {
        $teacher = Teacher::factory()->create([
            'user_id' => User::factory()->create(['role' => $this->roles->random()])->id,
        ]);
        $user = User::factory()->create(['role' => $this->roles->random()]);
        $route = route('teachers.destroy', [
            'teacher' => $teacher->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $teacher->id);
        $response->assertJsonPath('data.role', $teacher->role);
        $response->assertJsonPath('data.subject', $teacher->subject);
        $response->assertJsonPath('data.user.id', $teacher->user->id);
        $response->assertJsonPath('data.user.first_name', $teacher->user->first_name);
        $response->assertJsonPath('data.user.last_name', $teacher->user->last_name);
        $response->assertJsonPath('data.user.email', $teacher->user->email);
        $response->assertJsonPath('data.user.birth_date', $teacher->user->birth_date);
    }
}
