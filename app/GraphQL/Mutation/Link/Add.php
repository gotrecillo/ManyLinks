<?php

namespace ManyLinks\GraphQL\Mutation\Link;

use ManyLinks\Models\Link;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class Add extends Mutation
{
    protected $attributes = [
        'name' => 'Add',
        'description' => 'Add a link to the user saved ones'
    ];

    public function type()
    {
        return GraphQL::type('Link');
    }

    public function args()
    {
        return [
            'url' => [
                'id' => 'url',
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'description' => [
                'id' => 'description',
                'type' => Type::string()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        auth()->user()->links()->save(Link::make($args));

        return [
            'url' => $args['url'],
            'description' => $args['description'],
        ];
    }
}
