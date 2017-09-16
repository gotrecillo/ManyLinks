<?php

namespace Tests\GraphQL\Mutations\Auth;

use Event;
use ManyLinks\Events\UserRegistered;
use ManyLinks\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\PassportTestCase;

class RegisterTest extends PassportTestCase
{
    public function test_registers_a_users()
    {
        $response = $this->getRegisterResponse();

        $parsedResponse = $this->getParsedContent($response);

        $this->assertUserHasBeenCreated($parsedResponse);

        $this->assertDatabaseHas('users', ["email" => "foo@foo.foo"]);
    }

    public function test_cant_register_a_user_if_email_exists_or_username_exists()
    {
        User::create(['name' => 'Foo', 'password' => bcrypt('password'), 'email' => 'foo@foo.foo']);

        $response = $this->post('/api', [
            'query' => $this->getQuery(),
            'variables' => $this->getVariables()
        ]);

        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('errors', $parsedResponse);
        $this->assertObjectHasAttribute('message', $parsedResponse->errors[0]);
        $this->assertObjectHasAttribute('validation', $parsedResponse->errors[0]);
        $this->assertObjectHasAttribute('name', $parsedResponse->errors[0]->validation);
        $this->assertObjectHasAttribute('email', $parsedResponse->errors[0]->validation);
        $this->assertEquals('The name has already been taken.', $parsedResponse->errors[0]->validation->name[0]);
        $this->assertEquals('The email has already been taken.', $parsedResponse->errors[0]->validation->email[0]);
        $response->assertStatus(200);
    }

    public function test_cant_register_a_user_if_passwords_doesnt_match()
    {
        $response = $this->post('/api', [
            'query' => $this->getQuery(),
            'variables' => [
                'email' => 'foo@foo.foo',
                'password' => 'password',
                'passwordConfirmation' => 'passwordX',
                'name' => 'Foo',
            ],
        ]);

        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('errors', $parsedResponse);
        $this->assertObjectHasAttribute('message', $parsedResponse->errors[0]);
        $this->assertObjectHasAttribute('validation', $parsedResponse->errors[0]);
        $this->assertObjectHasAttribute('passwordConfirmation', $parsedResponse->errors[0]->validation);
        $this->assertEquals('The password confirmation and password must match.',
            $parsedResponse->errors[0]->validation->passwordConfirmation[0]);
        $response->assertStatus(200);
    }

    public function test_user_needs_email_verification_upon_registering()
    {
        $response = $this->getRegisterResponse();

        $parsedResponse = $this->getParsedContent($response);

        $this->assertUserHasBeenCreated($parsedResponse);

        $user = User::whereEmail('foo@foo.foo')->first();

        $this->assertSame(false, $user->confirmed);
        $this->assertTrue(Uuid::isValid($user->confirmation_code));
        $this->assertInternalType('string', $user->confirmation_code);
    }

    public function test_dispatchs_user_registered_event()
    {
        Event::fake();

        $this->getRegisterResponse();

        Event::assertDispatched(UserRegistered::class);
    }

    public function test_register_user_event_gets_listnened()
    {
        $listener = \Mockery::spy(\ManyLinks\Listeners\UserRegistered::class);
        app()->instance(\ManyLinks\Listeners\UserRegistered::class, $listener);

        $reponse = $this->getRegisterResponse();

        $parsedResponse = $this->getParsedContent($reponse);

        $listener->shouldHaveReceived('handle')->with(\Mockery::on(function ($event) {
            return $event->user->id === User::whereEmail('foo@foo.foo')->first()->id;
        }))->once();

        $this->assertUserHasBeenCreated($parsedResponse);
    }

    private function assertUserHasBeenCreated($parsedResponse)
    {
        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('register', $parsedResponse->data);
        $this->assertObjectHasAttribute('token', $parsedResponse->data->register);
        $this->assertSame(null, $parsedResponse->data->register->token);
        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
    }

    private function getRegisterResponse()
    {
        return $this->getGraphQLResponse('/api');
    }

    protected function getQuery()
    {
        return
            'mutation($email: String, $password: String, $passwordConfirmation: String, $name: String) {
                register(email: $email, password: $password, passwordConfirmation: $passwordConfirmation, name: $name){
                    token,
                }
            }';
    }

    protected function getVariables()
    {
        return [
            'email' => 'foo@foo.foo',
            'password' => 'password',
            'passwordConfirmation' => 'password',
            'name' => 'Foo',
        ];
    }
}
