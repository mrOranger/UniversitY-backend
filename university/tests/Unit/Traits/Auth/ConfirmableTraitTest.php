<?php

namespace Tests\Unit\Traits\Auth;

use App\Traits\Auth\ConfirmableTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ConfirmableTraitTest extends TestCase
{
    use ConfirmableTrait;

    private string $userId;
    private Carbon $createdAt;

    public function setUp () : void
    {
        parent::setUp();
        $this->userId = (string)rand(0, 100);
        $this->createdAt = Carbon::today();
    }

    public function test_generate_confirm_token_creates_string_with_valid_user_id () : void
    {
        $confirmToken = $this->generateConfirmToken($this->userId);

        $decrpytedToken = Crypt::decrypt($confirmToken);

        $this->assertEquals($decrpytedToken['user_id'], $this->userId);
    }

    public function test_generate_confirm_token_creates_string_with_valid_created_at () : void
    {
        $confirmToken = $this->generateConfirmToken($this->userId);

        $decrpytedToken = Crypt::decrypt($confirmToken);

        $this->assertEquals($decrpytedToken['created_at'], $this->createdAt);
    }
}
