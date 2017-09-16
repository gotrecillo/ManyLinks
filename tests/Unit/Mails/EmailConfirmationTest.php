<?php

namespace Tests\Unit\Mails;

use ManyLinks\Mail\EmailConfirmation;
use ManyLinks\Models\User;
use Tests\TestCase;

class EmailConfirmationTest extends TestCase
{

    public function test_mail_can_be_render()
    {
        $user = factory(User::class)->make();

        $mailable = new EmailConfirmation($user);

        $content = $mailable->build()->render();

        $this->assertInternalType('string', $content);
    }
}
