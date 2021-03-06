<?php

namespace Tests\GraphQL\Mutations\Link;

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

    }

    public function test_guest_users_cant_add_links()
    {
        $response = $this->getAddResponse();

        $this->assertResponseHasAuthenticationError($response);
    }

    protected function getAddResponse()
    {
        return $this->getGraphQLResponse('/api/me');
    }

    protected function getQuery()
    {
        return
            'mutation($url: String, $description: String) {
                addLink(url: $url, description: $description){
                    url
                }
            }';
    }

    protected function getVariables()
    {
        return [
            'url' => 'www.google.com',
            'description' => 'The search engine that we love',
        ];
    }
}
