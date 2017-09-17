<?php

namespace ManyLinks\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

class Link extends BaseType
{
    protected $attributes = [
        'name' => 'Link',
        'description' => 'A link'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The link id'
            ],
            'url' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The link url'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'A short description'
            ]
        ];
    }
}
