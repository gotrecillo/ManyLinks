<?php

namespace Tests\GraphQL\Mutations\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\PassportTestCase;

class RegisterTest extends PassportTestCase
{
    use DatabaseTransactions;

    public function test_registers_a_users()
    {
        $response = $this->post('/graphql', [
            'query' => $this->getQuery(),
            'variables' => $this->getVariables()
        ]);

        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('register', $parsedResponse->data);
        $this->assertObjectHasAttribute('token', $parsedResponse->data->register);
        $this->assertInternalType('string', $parsedResponse->data->register->token);
        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ["email" => "foo@foo.foo"]);
    }

    public function test_cant_register_a_user_if_email_exists_or_username_exists()
    {
        User::create(['name' => 'Foo', 'password' => bcrypt('password'), 'email' => 'foo@foo.foo']);

        $response = $this->post('/graphql', [
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
        $response = $this->post('/graphql', [
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


    public function getQuery()
    {
        return
            'mutation($email: String, $password: String, $passwordConfirmation: String, $name: String) {
                register(email: $email, password: $password, passwordConfirmation: $passwordConfirmation, name: $name){
                    token
                }
            }';
    }

    public function getVariables()
    {
        return [
            'email' => 'foo@foo.foo',
            'password' => 'password',
            'passwordConfirmation' => 'password',
            'name' => 'Foo',
        ];
    }
}
