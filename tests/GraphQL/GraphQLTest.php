<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class GraphQLTest extends TestCase
{

    public function test_graphql_is_responding()
    {
        $response = $this->get('/api');
        $response->assertExactJson([]);
        $response->assertStatus(200);
    }
}
