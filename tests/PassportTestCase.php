<?php

namespace Tests;

use DateTime;
use DB;
use Laravel\Passport\ClientRepository;

abstract class PassportTestCase extends TestCase
{
    protected $headers = [];
    protected $scopes = [];

    public function setUp()
    {
        parent::setUp();
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', $this->baseUrl
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}
