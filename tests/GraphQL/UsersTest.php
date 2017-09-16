<?php

namespace Tests\GraphQL;

use ManyLinks\Models\User;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function test_doesnt_fail_when_there_is_no_user()
    {
        $response = $this->get('/api?query={users{id,name,email}}');
        $response->assertExactJson([
            "data" => [
                "users" => []
            ]
        ]);
        $response->assertStatus(200);
    }

    public function test_can_retrieve_the_users()
    {
        $this->createTestUsers();

        $response = $this->get('/api?query={users{id,name,email}}');
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

    public function test_cant_retrieve_passwords()
    {
        $this->createTestUsers();
        $response = $this->get('/api?query={users{id,name,email,password}}');

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
