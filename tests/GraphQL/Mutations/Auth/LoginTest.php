<?php

namespace Tests\GraphQL\Mutations\Auth;

use ManyLinks\Models\User;
use Tests\PassportTestCase;

class LoginTest extends PassportTestCase
{
    public function test_gives_error_when_credentials_dont_match()
    {
        $response = $this->getLoginResponse();
        $this->assertResponseHasErrorMessage($response, 'Invalid credentials');
    }

    public function test_changes_access_token_for_correct_credentials()
    {
        User::create([
            'name' => 'Foo',
            'password' => bcrypt('password'),
            'email' => 'foo@foo.foo',
            'confirmed' => true
        ]);

        $response = $this->getLoginResponse();
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('login', $parsedResponse->data);
        $this->assertObjectHasAttribute('token', $parsedResponse->data->login);
        $this->assertInternalType('string', $parsedResponse->data->login->token);
        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
    }

    public function test_user_needs_to_be_confirmed_to_be_able_to_log_in()
    {
        User::create([
            'name' => 'Foo',
            'password' => bcrypt('password'),
            'email' => 'foo@foo.foo',
        ]);

        $response = $this->getLoginResponse();

        $this->assertResponseHasErrorMessage($response, 'Email confirmation needed');
    }

    private function getLoginResponse()
    {
        return $this->getGraphQLResponse('/api');
    }

    protected function getQuery()
    {
        return
            'mutation($email: String, $password: String) {
                login(email: $email, password: $password){
                    token
                }
            }';
    }

    protected function getVariables()
    {
        return [
            'email' => 'foo@foo.foo',
            'password' => 'password',
        ];
    }
}
