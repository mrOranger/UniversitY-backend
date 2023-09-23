<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DegreeControllerGetAll extends TestCase
{

    use RefreshDatabase;

    public function test_get_all_degrees_without_authentication_returns_unauthenticated () : void
    {

    }

    public function test_get_all_degrees_as_student_returns_unauthorized () : void
    {

    }

    public function test_get_all_degrees_as_professor_returns_unauthorized () : void
    {

    }

    public function test_get_all_degrees_as_admin_returns_empty_response () : void
    {

    }

    public function test_get_all_degrees_as_admin_returns_one_course () : void
    {

    }

    public function test_get_all_degrees_as_admin_returns_many_degrees () : void
    {

    }
    public function test_get_all_degrees_as_employee_returns_empty_response () : void
    {

    }

    public function test_get_all_degrees_as_employee_returns_one_course () : void
    {

    }

    public function test_get_all_degrees_as_employee_returns_many_degrees () : void
    {

    }
}
