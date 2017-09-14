<?php

namespace Tests\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyUsers()
    {
        $response = $this->get('/graphql?query={users{id,name,email}}');
        $response->assertExactJson([
            "data" => [
                "users" => []
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testWithSomeUsers()
    {
        $this->createTestUsers();

        $response = $this->get('/graphql?query={users{id,name,email}}');
        $response->assertExactJson([
            "data" => [
                "users" => [
                    ["id" => 1, "name" => "Foo", "email" => "foo@foo.com"],
                    ["id" => 2, "name" => "Bar", "email" => "bar@bar.com"],
                    ["id" => 3, "name" => "Baz", "email" => "baz@baz.com"],
                ]
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testCantRetrievePasswords()
    {
        $this->createTestUsers();
        $response = $this->get('/graphql?query={users{id,name,email,password}}');

        $response->assertJsonFragment(["data" => null]);
        $response->assertJsonStructure(["data", "errors"]);

        $response->assertStatus(200);
    }

    private function createTestUsers()
    {
        User::create(['name' => 'Foo', 'password' => 'password', 'email' => 'foo@foo.com']);
        User::create(['name' => 'Bar', 'password' => 'password', 'email' => 'bar@bar.com']);
        User::create(['name' => 'Baz', 'password' => 'password', 'email' => 'baz@baz.com']);
    }
}
