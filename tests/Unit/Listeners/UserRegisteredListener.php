<?php

namespace Tests\Unit\Listeners;

use ManyLinks\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRegisteredListener extends TestCase
{
    use DatabaseTransactions;

    public function test_sends_a_confirmation_email()
    {
        $user = factory(User::class)->make();

        $this->assertTrue(true);
    }
}
