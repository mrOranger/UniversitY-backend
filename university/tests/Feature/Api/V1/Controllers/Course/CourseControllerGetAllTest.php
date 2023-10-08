<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseControllerGetAllTest extends TestCase
{

    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
    }

    public final function test_get_all_courses_without_authentication_returns_unauthenticated(): void
    {
    }

    public final function test_get_all_courses_as_student_returns_unauthorized(): void
    {
    }

    public final function test_get_all_courses_returns_empty_response(): void
    {
    }

    public final function test_get_all_courses_returns_one_teacher(): void
    {
    }

    public final function test_get_all_courses_many_teachers(): void
    {
    }
}
