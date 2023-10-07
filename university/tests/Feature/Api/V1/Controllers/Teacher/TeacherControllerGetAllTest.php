<?php

namespace Tests\Feature\Api\V1\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeacherControllerGetAllTest extends TestCase
{
    use RefreshDatabase;

    private string $test_url;
    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->test_url = 'api/v1/teachers';
        $this->roles = collect(['professor', 'admin', 'employee']);
    }

    public final function test_get_all_teachers_without_authentication_returns_unauthenticated(): void
    {
        $response = $this->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public final function test_get_all_teachers_as_student_returns_unauthorized(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public final function test_get_all_teachers_returns_empty_response(): void
    {
        $user = User::factory()->create(['role' => $this->roles->random()]);

        $response = $this
            ->actingAs($user)
            ->getJson($this->test_url);

            $response->assertStatus(Response::HTTP_OK);
            $response->assertJsonCount(0, 'data');
            $response->assertJsonPath('data', []);
    }

    public final function test_get_all_teachers_returns_one_teacher(): void
    {
        $teacher = Teacher::factory()->create([
            'user_id' => User::factory()->create(['role' => 'professor'])->id
        ]);
        $user = User::factory()->create(['role' => $this->roles->random()]);

        $response = $this
            ->actingAs($user)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $teacher->id);
        $response->assertJsonPath('data.0.role', $teacher->role);
        $response->assertJsonPath('data.0.subject', $teacher->subject);
        $response->assertJsonPath('data.0.user.id', $teacher->user->id);
        $response->assertJsonPath('data.0.user.first_name', $teacher->user->first_name);
        $response->assertJsonPath('data.0.user.last_name', $teacher->user->last_name);
        $response->assertJsonPath('data.0.user.email', $teacher->user->email);
        $response->assertJsonPath('data.0.user.birth_date', $teacher->user->birth_date);
    }

    public final function test_get_all_teachers_many_teachers(): void
    {
        $teachers = Teacher::factory(100)->create([
            'user_id' => User::factory()->create(['role' => 'professor'])->id
        ]);
        $user = User::factory()->create(['role' => $this->roles->random()]);

        $response = $this
            ->actingAs($user)
            ->getJson($this->test_url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $teachers->each(function (Teacher $teacher, int $index) use ($response) {
            $response->assertJsonPath('data.'. $index .'.id', $teacher->id);
            $response->assertJsonPath('data.'. $index .'.role', $teacher->role);
            $response->assertJsonPath('data.'. $index .'.subject', $teacher->subject);
            $response->assertJsonPath('data.'. $index .'.user.id', $teacher->user->id);
            $response->assertJsonPath('data.'. $index .'.user.first_name', $teacher->user->first_name);
            $response->assertJsonPath('data.'. $index .'.user.last_name', $teacher->user->last_name);
            $response->assertJsonPath('data.'. $index .'.user.email', $teacher->user->email);
            $response->assertJsonPath('data.'. $index .'.user.birth_date', $teacher->user->birth_date);
        });
    }
}
