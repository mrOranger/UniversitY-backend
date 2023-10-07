<?php

namespace Tests\Feature\Api\V1\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeacherControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;
    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/teachers/';
        $this->roles = collect(['professor', 'admin', 'employee']);
    }
    final public function test_get_teacher_by_id_without_authentication_returns_unauthenticated(): void
    {
        $teacher = Teacher::factory()->create([
            'user_id' => User::factory()->create(['role' => $this->roles->random()])->id
        ]);

        $response = $this->getJson($this->test_url . $teacher->id);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }
    final public function test_get_teacher_by_id_returns_unauthorized(): void
    {
        $teacher = Teacher::factory()->create([
            'user_id' => User::factory()->create(['role' => $this->roles->random()])->id
        ]);
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url . $teacher->id);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_get_teacher_by_id_as_admin_returns_not_found(): void
    {
        $user = User::factory()->create(['role' => $this->roles->random()]);

        $response = $this
            ->actingAs($user)
            ->getJson($this->test_url.'0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Teacher 0 does not exist.');
    }

    final public function test_get_teacher_by_id_as_admin_returns_ok(): void
    {
        $teacher = Teacher::factory()->create([
            'user_id' => User::factory()->create(['role' => $this->roles->random()])->id
        ]);
        $user = User::factory()->create(['role' => $this->roles->random()]);

        $response = $this
            ->actingAs($user)
            ->getJson($this->test_url.$teacher->id);

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
