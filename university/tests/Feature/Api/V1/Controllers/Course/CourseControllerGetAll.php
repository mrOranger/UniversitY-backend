<?php

namespace Tests\Feature\Api\V1\Controllers\Course;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseControllerGetAll extends TestCase
{
    public final function test_get_all_courses_without_authentication_returns_unauthenticated () : void
    {

    }

    public final function test_get_all_courses_as_student_returns_unauthorized () : void
    {

    }

    public final function test_get_all_courses_as_professor_returns_unauthorized () : void
    {

    }

    public final function test_get_all_courses_as_admin_returns_empty_response () : void
    {

    }

    public final function test_get_all_courses_as_admin_returns_one_course () : void
    {

    }

    public final function test_get_all_courses_as_admin_returns_many_courses () : void
    {

    }
    public final function test_get_all_courses_as_employee_returns_empty_response () : void
    {

    }

    public final function test_get_all_courses_as_employee_returns_one_course () : void
    {

    }

    public final function test_get_all_courses_as_employee_returns_many_courses () : void
    {

    }
}
