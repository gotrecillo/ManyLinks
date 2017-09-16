<?php

namespace ManyLinks\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use ManyLinks\Models\User;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function build()
    {
        return $this->markdown('emails.auth.email-confirmation');
    }
}
