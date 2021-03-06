<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $baseUrl = '/';

    protected function getGraphQLResponse($endpoint, $opts = [])
    {
        $query = $opts['query'] ?? $this->getQuery();
        $variables = $opts['variables'] ?? $this->getVariables();
        $response = $this->post($endpoint, compact('query', 'variables'));

        return tap($response)->assertStatus(200);
    }

    protected function getQuery()
    {
        return '';
    }

    protected function getVariables()
    {
        return [];
    }

    /**
     * @param $response \Illuminate\Foundation\Testing\TestResponse
     * @return mixed
     */
    public function getParsedContent($response)
    {
        return \GuzzleHttp\json_decode($response->getContent());
    }

    public function assertResponseHasErrorMessage($response, $message)
    {
        $parsedContent = $this->getParsedContent($response);

        $this->assertEquals($message, $parsedContent->errors[0]->message);
    }

    public function assertResponseHasAuthenticationError($response)
    {
        $this->assertResponseHasErrorMessage($response, 'No Authentication provided');
    }
}
