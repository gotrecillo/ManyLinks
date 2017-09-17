<?php

namespace ManyLinks\GraphQL\Mutation\Link;

use Folklore\GraphQL\Error\AuthorizationError;
use ManyLinks\GraphQL\Mutation\AuthenticatedMutation;
use ManyLinks\Models\Link;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class Delete extends AuthenticatedMutation
{
    protected $attributes = [
        'name' => 'Delete link',
        'description' => 'Delete a link saved by the user',
    ];

    public function type()
    {
        return GraphQL::type('Link');
    }

    public function args()
    {
        return [
            'id' => [
                'id' => 'url',
                'type' => Type::string(),
                'rules' => ['required']
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $link = Link::with('user')->find($args['id']);

        if (auth()->user()->id !== $link->user->id) {
            throw new AuthorizationError('You are not authorized to delete that link');
        }

        $link->delete();

        return $link;
    }
}
