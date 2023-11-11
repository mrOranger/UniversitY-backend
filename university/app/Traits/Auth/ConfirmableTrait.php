<?php

namespace App\Traits\Auth;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

trait ConfirmableTrait
{
    final public function generateConfirmToken (User $user) : string
    {
        $confirmationInfo = [
            'user_id' => $user->id,
            'created_at' => Carbon::now()
        ];

        return Crypt::encrypt($confirmationInfo, env('app.token_confirmation'));
    }
}
