<?php

namespace Tests\Feature\Api\V1\Controllers\Professor;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfessorControllerGetAllTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_professors_without_authentication_returns_unauthenticatd () : void
    {
        $route = route('professors.index');

        $response = $this->getJson($route);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    public function test_get_all_professors_as_student_returns_unauthorized () : void
    {
        $route = route('professors.index');
        $student = User::factory()->create(['role' => 'student']);

        $response = $this
            ->actingAs($student)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_all_professors_as_professor_returns_unauthorized () : void
    {
        $route = route('professors.index');
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    public function test_get_all_professors_as_admin_returns_empty_response () : void
    {
        $route = route('professors.index');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function test_get_all_professors_as_admin_returns_one_professor_response () : void
    {
        $route = route('professors.index');
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = Professor::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $professor->id);
        $response->assertJsonPath('data.0.level', $professor->level);
        $response->assertJsonPath('data.0.subject', $professor->subject);
        $response->assertJsonPath('data.0.user_id', $professor->user_id);
    }

    public function test_get_all_professors_as_admin_returns_many_professors_response () : void
    {
        $route = route('professors.index');
        $admin = User::factory()->create(['role' => 'admin']);
        $professors = Professor::factory(100)->create();

        $response = $this
            ->actingAs($admin)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $professors->each(function (Professor $professor, int $index) use ($response) {
            $response->assertJsonPath('data.' . $index . '.id', $professor->id);
            $response->assertJsonPath('data.' . $index . '.level', $professor->level);
            $response->assertJsonPath('data.' . $index . '.subject', $professor->subject);
            $response->assertJsonPath('data.' . $index . '.user_id', $professor->user_id);
        });
    }
    public function test_get_all_professors_as_employee_returns_empty_response () : void
    {
        $route = route('professors.index');
        $employee = User::factory()->create(['role' => 'employee']);
        $professor = Professor::factory()->create();

        $response = $this
            ->actingAs($employee)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $professor->id);
        $response->assertJsonPath('data.0.level', $professor->level);
        $response->assertJsonPath('data.0.subject', $professor->subject);
        $response->assertJsonPath('data.0.user_id', $professor->user_id);
    }

    public function test_get_all_professors_as_employee_returns_one_professor_response () : void
    {
        $route = route('professors.index');
        $employee = User::factory()->create(['role' => 'employee']);
        $professor = Professor::factory()->create();

        $response = $this
            ->actingAs($employee)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $professor->id);
        $response->assertJsonPath('data.0.level', $professor->level);
        $response->assertJsonPath('data.0.subject', $professor->subject);
        $response->assertJsonPath('data.0.user_id', $professor->user_id);
    }

    public function test_get_all_professors_as_employee_returns_many_professors_response () : void
    {
        $route = route('professors.index');
        $employee = User::factory()->create(['role' => 'employee']);
        $professors = Professor::factory(100)->create();

        $response = $this
            ->actingAs($employee)
            ->getJson($route);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(100, 'data');
        $professors->each(function (Professor $professor, int $index) use ($response) {
            $response->assertJsonPath('data.' . $index . '.id', $professor->id);
            $response->assertJsonPath('data.' . $index . '.level', $professor->level);
            $response->assertJsonPath('data.' . $index . '.subject', $professor->subject);
            $response->assertJsonPath('data.' . $index . '.user_id', $professor->user_id);
        });
    }
}
