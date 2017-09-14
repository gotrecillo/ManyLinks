<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

class User extends BaseType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user',
            ],
            'email' => [
                'type' => Type::string('sda'),
                'description' => 'The email of the user',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The email of the user',
            ],
        ];
    }
}
