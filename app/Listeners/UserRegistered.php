<?php

namespace ManyLinks\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use ManyLinks\Mail\EmailConfirmation;
use ManyLinks\Models\User;

class UserRegistered
{
    public function handle($event)
    {
        \Mail::to($event->user->email)->send(new EmailConfirmation($event->user));
    }
}
