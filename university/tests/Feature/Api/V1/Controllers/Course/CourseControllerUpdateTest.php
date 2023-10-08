<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Collection $roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->roles = collect(['professor', 'admin', 'employee']);
    }

    public final function test_update_course_without_authentication_returns_unauthenticated () : void
    {
    }

    public final function test_update_course_returns_unauthorized () : void
    {
    }

    public final function test_update_course_without_role_returns_unprocessable_content () : void
    {
    }

    public final function test_update_course_with_role_not_string_returns_unprocessable_content () : void
    {
    }

    public final function test_update_course_with_role_invalid_returns_unprocessable_content () : void
    {
    }

    public final function test_update_course_without_subject_returns_unprocessable_content () : void
    {
    }

    public final function test_update_course_with_subject_not_string_returns_unprocessable_content () : void
    {
    }

    public final function test_update_course_returns_course_not_found () : void
    {
    }

    public final function test_update_course_returns_teacher_not_found () : void
    {
    }

    public final function test_update_course_returns_user_not_found () : void
    {
    }

    public final function test_update_course_returns_created () : void
    {
    }
}
