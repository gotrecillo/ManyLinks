<?php

namespace Tests\Feature;

use ManyLinks\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ConfirmEmailTest extends TestCase
{
    public function test_user_can_confirm_its_email()
    {
        $this->withoutExceptionHandling();

        $code = Uuid::uuid4();

        $email = 'foo@foo.foo';

        User::create([
            'name' => 'Foo',
            'password' => bcrypt('password'),
            'email' => $email,
            'confirmation_code' => $code
        ]);

        $encodedEmail = urlencode($email);

        $response = $this->get("/auth/email-confirmation/{$code}?email={$encodedEmail}");

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'confirmed' => '1',
            'confirmation_code' => null
        ]);

        $response->assertRedirect('/');
    }

    public function test_it_checks_the_email_passed_as_argument_to_avoid_possible_conflicts()
    {
        $this->withoutExceptionHandling();

        $code = Uuid::uuid4();

        $email = 'foo@foo.foo';

        User::create([
            'name' => 'Foo',
            'password' => bcrypt('password'),
            'email' => $email,
            'confirmation_code' => $code
        ]);

        $encodedEmail = urlencode('anotheremail@foo.foo');

        $response = $this->get("/auth/email-confirmation/{$code}?email={$encodedEmail}");

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'confirmed' => '0',
            'confirmation_code' => $code
        ]);

        $response->assertRedirect('/auth/email-confirmation/error');
    }

}
