<?php

namespace Tests\Feature\Email;

use App\Mail\UserRegistered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserRegisteredTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_email_is_sent_if_user_signup_correctly(): void
    {
        $route = route('auth.register', [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'email' => 'email@example.com',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $this->postJson($route);
        Mail::assertSent(UserRegistered::class, function (UserRegistered $email) {
            return $email->from('no-reply@university.com') &&
                    $email->to('email@example.com') &&
                    $email->subject('User Registered');
        });
    }

    public function test_email_is_not_sent_if_user_does_not_signup_correctly(): void
    {
        $route = route('auth.register', [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'birth_date' => '1996-05-04',
            'password' => 'ThisIsAValidPassword1!',
            'password_confirmation' => 'ThisIsAValidPassword1!',
            'role' => 'admin',
        ]);

        $this->postJson($route);
        Mail::assertNotSent(UserRegistered::class);
    }
}
