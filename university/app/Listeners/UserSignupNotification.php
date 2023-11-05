<?php

namespace App\Listeners;

use App\Events\UserSignup;
use App\Mail\UserRegistered;
use Illuminate\Support\Facades\Mail;

class UserSignupNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserSignup $event): void
    {
        Mail::to($event->user)->send(new UserRegistered($event->user));
    }
}
