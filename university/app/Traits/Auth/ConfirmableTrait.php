<?php

namespace App\Traits\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

trait ConfirmableTrait
{
    final public function generateConfirmToken (string $userId) : string
    {
        $confirmationInfo = [
            'user_id' => $userId,
            'created_at' => Carbon::today()
        ];

        return Crypt::encrypt($confirmationInfo);
    }
}
