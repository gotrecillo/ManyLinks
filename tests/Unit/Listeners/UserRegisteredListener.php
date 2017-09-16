<?php

namespace Tests\Unit\Listeners;

use ManyLinks\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRegisteredListener extends TestCase
{
    use DatabaseTransactions;

    public function testExample()
    {
        User::create(['name' => 'Foo', 'password' => bcrypt('password'), 'email' => 'foo@foo.foo']);

        $this->assertTrue(true);
    }
}
