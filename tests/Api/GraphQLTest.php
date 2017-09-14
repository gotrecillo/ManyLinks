<?php

namespace Tests\Api;

use Tests\TestCase;

class GraphQLTestTest extends TestCase
{

    public function test_graphql_is_responding()
    {
        $response = $this->get('/graphql');
        $response->assertExactJson([]);
        $response->assertStatus(200);
    }
}
