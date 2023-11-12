<?php

namespace Tests\Feature\Jobs;

use App\Jobs\CheckUserConfirmationJob;
use App\Models\User;
use App\Traits\Auth\ConfirmableTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckUserConfirmationJobsTest extends TestCase
{
    use ConfirmableTrait;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_job_is_dispatched_when_user_is_registerd(): void
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

        Queue::assertPushed(function (CheckUserConfirmationJob $job) {
            return $job->user->first_name == 'Mario' &&
                    $job->user->last_name == 'Rossi' &&
                    $job->user->birth_date == '1996-05-04';
        });
    }

    public function test_job_delete_user_if_did_not_confirmed_account(): void
    {
        $user = User::factory()->createQuietly();
        $user->update([
            'confirmation' => $this->generateConfirmToken($user->id),
        ]);

        (new CheckUserConfirmationJob($user))->handle();

        $this->assertNull(User::find($user->id));
    }

    public function test_job_does_not_delete_user_if_confimed_account(): void
    {
        $user = User::factory()->createQuietly([
            'confirmation' => null,
        ]);

        (new CheckUserConfirmationJob($user))->handle();

        $this->assertNotNull(User::find($user->id));
    }
}
