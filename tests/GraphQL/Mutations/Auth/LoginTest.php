<?php

namespace Tests\GraphQL\Mutations\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\PassportTestCase;

class LoginTest extends PassportTestCase
{
    use DatabaseTransactions;

    public function test_gives_error_when_credentials_dont_match()
    {
        $response = $this->getLoginResponse();
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('errors', $parsedResponse);
        $this->assertObjectHasAttribute('message', $parsedResponse->errors[0]);
        $this->assertEquals('Invalid credentials', $parsedResponse->errors[0]->message);
        $response->assertStatus(200);
    }

    public function test_changes_access_token_for_correct_credentials()
    {
        User::create(['name' => 'Foo', 'password' => bcrypt('password'), 'email' => 'foo@foo.foo']);

        $response = $this->getLoginResponse();
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('login', $parsedResponse->data);
        $this->assertObjectHasAttribute('token', $parsedResponse->data->login);
        $this->assertInternalType('string', $parsedResponse->data->login->token);
        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
        $response->assertStatus(200);
    }

    public function getLoginResponse()
    {
        return $this->post('/graphql', [
            'query' => $this->getQuery(),
            'variables' => $this->getVariables(),
        ]);

    }

    public function getQuery()
    {
        return
            'mutation($email: String, $password: String) {
                login(email: $email, password: $password){
                    token
                }
            }';
    }

    public function getVariables()
    {
        return [
            'email' => 'foo@foo.foo',
            'password' => 'password',
        ];
    }
}
