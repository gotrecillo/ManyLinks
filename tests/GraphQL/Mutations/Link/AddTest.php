<?php

namespace Tests\GraphQL\Mutations\Auth;

use Laravel\Passport\Passport;
use ManyLinks\Models\User;
use Tests\PassportTestCase;

class AddTest extends PassportTestCase
{

    public function test_authenticated_users_can_add_links()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $response = $this->getAddResponse();
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
        $this->assertObjectHasAttribute('data', $parsedResponse);
        $this->assertObjectHasAttribute('addLink', $parsedResponse->data);
        $this->assertObjectHasAttribute('url', $parsedResponse->data->addLink);
        $this->assertSame('www.google.com', $parsedResponse->data->addLink->url);

        $this->assertDatabaseHas('links',
            [
                'url' => 'www.google.com',
                'description' => 'The search engine that we love',
                'user_id' => $user->id
            ]);

        $response->assertStatus(200);
    }

    public function test_guest_users_cant_add_links()
    {
        $response = $this->getAddResponse();
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectHasAttribute('errors', $parsedResponse);
        $response->assertStatus(200);
    }

    private function getAddResponse()
    {
        return $this->post('/api', [
            'query' => $this->getQuery(),
            'variables' => $this->getVariables()
        ]);
    }

    private function getQuery()
    {
        return
            'mutation($url: String, $description: String) {
                addLink(url: $url, description: $description){
                    url
                }
            }';
    }

    private function getVariables()
    {
        return [
            'url' => 'www.google.com',
            'description' => 'The search engine that we love',
        ];
    }
}
