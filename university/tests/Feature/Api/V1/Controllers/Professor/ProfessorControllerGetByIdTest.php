<?php

namespace Tests\Feature\Api\V1\Controllers\Professor;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfessorControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_professor_without_authentication_returns_unauthenticatd () : void
    {
        $route = route('professors.show', [
            'professor' => 1
        ]);

        $response = $this->getJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_get_professor_as_student_returns_unauthorized () : void
    {
        $route = route('professors.show', [
            'professor' => 1
        ]);
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_professor_as_professor_returns_unauthorized () : void
    {
        $route = route('professors.show', [
            'professor' => 1
        ]);
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_professor_as_admin_returns_not_found_response () : void
    {
        $route = route('professors.show', [
            'professor' => 1
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Professor 1 not found.');
    }

    public function test_get_professor_as_admin_returns_ok_response () : void
    {
        $professor = Professor::factory()->create();
        $route = route('professors.show', [
            'professor' => $professor->id
        ]);
        \Illuminate\Support\Facades\Log::info($professor);
        \Illuminate\Support\Facades\Log::info($route);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $professor->id);
        $response->assertJsonPath('data.level', $professor->level);
        $response->assertJsonPath('data.subject', $professor->subject);
        $response->assertJsonPath('data.user_id', $professor->user_id);
    }

    public function test_get_professor_as_employee_returns_not_found_response () : void
    {
        $route = route('professors.show', [
            'professor' => 1
        ]);
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Professor 1 not found.');
    }

    public function test_get_professor_as_employee_returns_ok_response () : void
    {
        $professor = Professor::factory()->create();
        $route = route('professors.show', [
            'professor' => $professor->id
        ]);
        $employee = User::factory()->create(['role' => 'employee']);

        $response = $this
            ->actingAs($employee)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('data.id', $professor->id);
        $response->assertJsonPath('data.level', $professor->level);
        $response->assertJsonPath('data.subject', $professor->subject);
        $response->assertJsonPath('data.user_id', $professor->user_id);
    }
}
