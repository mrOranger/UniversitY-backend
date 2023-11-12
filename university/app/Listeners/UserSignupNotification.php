<?php

namespace App\Listeners;

use App\Events\UserSignup;
use App\Jobs\CheckUserConfirmationJob;
use App\Mail\UserRegistered;
use App\Traits\Auth\ConfirmableTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserSignupNotification
{
    use ConfirmableTrait;

    /**
     * Handle the event.
     */
    public function handle(UserSignup $event): void
    {
        $event->user->update([
            'confirmation' => $this->generateConfirmToken($event->user->id),
        ]);
        CheckUserConfirmationJob::dispatch($event->user)->delay(Carbon::now()->addDays(7));
        Mail::to($event->user)->send(new UserRegistered($event->user, $event->user->confirmation));
    }
}
