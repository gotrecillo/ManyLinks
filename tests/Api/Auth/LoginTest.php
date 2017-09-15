<?php

namespace Tests\Api\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\PassportTestCase;

class LoginTest extends PassportTestCase
{
    use DatabaseTransactions;

    public function test_gives_error_when_credentials_dont_match()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'foo@foo.foo',
            'password' => 'password'
        ]);
        $response->assertJson(["error" => true, "message" => "Invalid credentials"]);
        $response->assertStatus(401);
    }

    public function test_changes_access_token_for_correct_credentials()
    {
        User::create(['name' => 'Foo', 'password' => bcrypt('password'), 'email' => 'foo@foo.foo']);

        $response = $this->post('/api/auth/login', [
            'email' => 'foo@foo.foo',
            'password' => 'password'
        ]);

        $response->assertJsonStructure(["data" => ["token"]]);
        $response->assertStatus(200);
    }

}
