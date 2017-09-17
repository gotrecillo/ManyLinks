<?php

namespace Tests\GraphQL\Mutations\Link;

use Laravel\Passport\Passport;
use ManyLinks\Models\Link;
use ManyLinks\Models\User;
use Tests\PassportTestCase;

class DeleteTest extends PassportTestCase
{

    public function test_authenticated_users_can_remove_its_own_links()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $link = $user->links()->save(Link::make(['url' => 'www.google.com']));

        $response = $this->getRemoveResponse($link->id);
        $parsedResponse = $this->getParsedContent($response);

        $this->assertObjectNotHasAttribute('errors', $parsedResponse);
        $this->assertSame($link->id, $parsedResponse->data->deleteLink->id);
        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }

    public function test_guest_users_cant_remove_links()
    {
        $user = factory(User::class)->create();
        $link = $user->links()->save(Link::make(['url' => 'www.google.com']));

        $response = $this->getRemoveResponse($link->id);

        $this->assertResponseHasAuthenticationError($response);
    }

    public function test_user_can_only_remove_their_own_links()
    {
        $linkOwner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $link = $linkOwner->links()->save(Link::make(['url' => 'www.google.com']));
        Passport::actingAs($user);

        $response = $this->getRemoveResponse($link->id);

        $this->assertResponseHasErrorMessage($response,'You are not authorized to delete that link');
    }

    private function getRemoveResponse($id)
    {
        return $this->getGraphQLResponse('/api/me', ['variables' => compact('id')]);
    }

    protected function getQuery()
    {
        return
            'mutation($id: String) {
                deleteLink(id: $id){
                    id
                }
            }';
    }
}
