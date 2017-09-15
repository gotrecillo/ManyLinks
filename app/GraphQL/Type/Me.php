<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

class Me extends BaseType
{
    protected $attributes = [
        'name' => 'Me',
        'description' => 'Current user'
    ];

    public function fields()
    {
        return [
            'User' => [
                'type' => GraphQL::type('User'),
                'description' => 'The id of the user',
            ],
            'token' => [
                'type' => Type::string(),
                'description' => 'An authorization token'
            ]
        ];
    }
}
