<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseControllerDeleteTest extends TestCase
{
    use RefreshDatabase;
    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
    }
    final public function test_delete_course_by_id_without_authentication_returns_unauthenticated(): void
    {
    }
    final public function test_delete_course_by_id_returns_unauthorized(): void
    {
    }

    final public function test_delete_course_by_id_returns_not_found(): void
    {
    }

    final public function test_delete_course_by_id_returns_ok(): void
    {
    }
}
