<?php

namespace App\Listeners;

use App\Events\UserSignup;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserSignupNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserSignup $event): void
    {
        $randomUuid = fake('it_IT')->uuid();
        $event->user->update([
            'confirmation' => $randomUuid
        ]);
        Mail::to($event->user)->send(new UserRegistered($event->user, $randomUuid));
    }
}
