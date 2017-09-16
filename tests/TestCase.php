<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $baseUrl;

    public function setUp()
    {
        parent::setUp();

        $this->baseUrl = url('/');
    }

    /**
     * @param $response \Illuminate\Foundation\Testing\TestResponse
     * @return mixed
     */
    public function getParsedContent($response)
    {
        return \GuzzleHttp\json_decode($response->getContent());
    }
}
