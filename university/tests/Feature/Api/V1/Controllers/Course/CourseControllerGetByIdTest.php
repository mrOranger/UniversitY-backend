<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseControllerGetByIdTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
    }
    public final function test_get_course_by_id_without_authentication_returns_unauthenticated(): void
    {
    }
    public final function test_get_course_by_id_returns_unauthorized(): void
    {
    }

    public final function test_get_course_by_id_returns_not_found(): void
    {
    }

    public final function test_get_course_by_id_returns_ok(): void
    {
    }
}
