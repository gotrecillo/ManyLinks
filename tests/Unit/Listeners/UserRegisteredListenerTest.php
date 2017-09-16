<?php

namespace Tests\Unit\Listeners;

use Mail;
use ManyLinks\Listeners\UserRegistered as UserRegisteredListener;
use ManyLinks\Events\UserRegistered as UserRegisteredEvent;
use ManyLinks\Mail\EmailConfirmation;
use ManyLinks\Models\User;
use Tests\TestCase;

class UserRegisteredListenerTest extends TestCase
{

    public function test_sends_a_confirmation_email()
    {
        Mail::fake();

        $user = factory(User::class)->make();

        $listener = new UserRegisteredListener();

        $listener->handle(new UserRegisteredEvent($user));


        Mail::assertSent(EmailConfirmation::class, function (EmailConfirmation $mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->email === $user->email;
        });

    }
}
