<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CourseControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
        $course = Course::factory()->createQuietly();
        $this->route = route('courses.update', [
            'course' => $course->id,
        ]);
    }

    final public function test_update_course_without_authentication_returns_unauthenticated(): void
    {
        $response = $this
            ->putJson($this->route, [
                'name' => 'Algorithms & Data Structures',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    final public function test_update_course_returns_unauthorized(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => 'student']))
            ->putJson($this->route, [
                'name' => 'Algorithms & Data Structures',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonPath('message', 'Unauthorized.');
    }

    final public function test_update_course_without_name_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'sector' => 'INF-01',
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The name field is required.');
        $response->assertJsonPath('errors.name.0', 'The name field is required.');
    }

    final public function test_update_course_with_name_not_string_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 123,
                'sector' => 'INF-01',
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The name field must be a string.');
        $response->assertJsonPath('errors.name.0', 'The name field must be a string.');
    }

    final public function test_update_course_without_sector_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The sector field is required.');
        $response->assertJsonPath('errors.sector.0', 'The sector field is required.');
    }

    final public function test_update_course_with_sector_not_string_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 123,
                'starting_date' => '2023-01-09',
                'ending_date' => '2024-01-06',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The sector field must be a string.');
        $response->assertJsonPath('errors.sector.0', 'The sector field must be a string.');
    }

    final public function test_update_course_without_starting_date_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'ending_date' => '2024-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The starting date field is required.');
        $response->assertJsonPath('errors.starting_date.0', 'The starting date field is required.');
    }

    final public function test_update_course_with_starting_date_invalid_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'starting_date' => '2023-90-90',
                'ending_date' => '2024-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The starting date field must be a valid date.');
        $response->assertJsonPath('errors.starting_date.0', 'The starting date field must be a valid date.');
    }

    final public function test_update_course_with_starting_date_invalid_format_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'ending_date' => '2022-01-01',
                'starting_date' => '2023/01/01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The starting date field must match the format Y-m-d.');
        $response->assertJsonPath('errors.starting_date.0', 'The starting date field must match the format Y-m-d.');
    }

    final public function test_update_course_without_ending_date_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'starting_date' => '2024-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The ending date field is required.');
        $response->assertJsonPath('errors.ending_date.0', 'The ending date field is required.');
    }

    final public function test_update_course_with_ending_date_invalid_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'ending_date' => '1900-01-90',
                'starting_date' => '2023-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The ending date field must be a valid date.');
        $response->assertJsonPath('errors.ending_date.0', 'The ending date field must be a valid date.');
    }

    final public function test_update_course_with_ending_date_invalid_format_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'ending_date' => '2022/01/01',
                'starting_date' => '2023-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The ending date field must match the format Y-m-d.');
        $response->assertJsonPath('errors.ending_date.0', 'The ending date field must match the format Y-m-d.');
    }

    final public function test_update_course_with_ending_date_before_starting_date_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'ending_date' => '2022-01-01',
                'starting_date' => '2023-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The ending date field must be a date after or equal to starting date.');
        $response->assertJsonPath('errors.ending_date.0', 'The ending date field must be a date after or equal to starting date.');
    }

    final public function test_update_course_without_cfu_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-01',
                'ending_date' => '2024-01-01',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '01/01/2000',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The cfu field is required.');
        $response->assertJsonPath('errors.cfu.0', 'The cfu field is required.');
    }

    final public function test_update_course_with_cfu_invalid_returns_unprocessable_content(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Computer Science',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-01',
                'ending_date' => '2024-01-01',
                'cfu' => 'as',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('message', 'The cfu field must be a number.');
        $response->assertJsonPath('errors.cfu.0', 'The cfu field must be a number.');
    }

    final public function test_update_course_with_not_existing_professor_returns_not_found(): void
    {
        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Algorithms & Data Structures',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-01',
                'ending_date' => '2024-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => 'full',
                    'subject' => 'Computer Science',
                    'user' => [
                        'first_name' => 'Mario',
                        'last_name' => 'Rossi',
                        'birth_date' => '2000-01-01',
                        'email' => 'mario.rossi@gmail.com',
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonPath('message', 'Professor does not exist.');
    }

    final public function test_update_course_returns_ok(): void
    {
        $user = User::factory()->createQuietly([
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '2000-01-01',
            'email' => 'mario.rossi@gmail.com',
        ]);
        $teacher = Teacher::factory()->createQuietly([
            'user_id' => $user->id,
            'role' => 'full',
            'subject' => 'Computer Science',
        ]);

        $response = $this
            ->actingAs(User::factory()->createQuietly(['role' => $this->roles->random()]))
            ->putJson($this->route, [
                'name' => 'Algorithms & Data Structures',
                'sector' => 'INF-01',
                'starting_date' => '2023-01-01',
                'ending_date' => '2024-01-01',
                'cfu' => '12',
                'professor' => [
                    'role' => $teacher->role,
                    'subject' => $teacher->subject,
                    'user' => [
                        'first_name' => $teacher->user->first_name,
                        'last_name' => $teacher->user->last_name,
                        'email' => $teacher->user->email,
                        'birth_date' => $teacher->user->birth_date,
                    ],
                ],
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
