<?php

namespace Tests\Feature\Api\V1\Controllers\Degree;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DegreeControllerSaveTest extends TestCase
{
    use RefreshDatabase;
    private string $test_url;
    public function setUp() : void
    {
        parent::setUp();
        $this->test_url = 'api/v1/degrees';
    }
    public final function test_save_degree_without_authentication_returns_unauthenticated () : void
    {

    }
    public final function test_save_degree_as_student_returns_unauthorized () : void
    {

    }
    public final function test_save_degree_as_professor_returns_unauthorized () : void
    {

    }
    public final function test_save_degree_as_admin_returns_name_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_name_string_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_name_max_length () : void
    {

    }
    public final function test_save_degree_as_admin_returns_name_already_exists () : void
    {

    }
    public final function test_save_degree_as_admin_returns_code_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_code_string_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_code_max_length () : void
    {

    }
    public final function test_save_degree_as_admin_returns_course_type_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_course_type_string_required () : void
    {

    }
    public final function test_save_degree_as_admin_returns_course_type_not_valid () : void
    {

    }
    public final function test_save_degree_as_employee_returns_name_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_name_string_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_name_max_length () : void
    {

    }
    public final function test_save_degree_as_employee_returns_name_already_exists () : void
    {

    }
    public final function test_save_degree_as_employee_returns_code_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_code_string_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_code_max_length () : void
    {

    }
    public final function test_save_degree_as_employee_returns_course_type_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_course_type_string_required () : void
    {

    }
    public final function test_save_degree_as_employee_returns_course_type_not_valid () : void
    {

    }
}
