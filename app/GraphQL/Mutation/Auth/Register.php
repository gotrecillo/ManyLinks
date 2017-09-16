<?php

namespace App\GraphQL\Mutation\Auth;

use App\User;
use Auth;
use Folklore\GraphQL\Error\AuthorizationError;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class Register extends Mutation
{
    protected $attributes = [
        'name' => 'Register',
        'description' => 'Register a user in the application'
    ];

    public function type()
    {
        return GraphQL::type('Me');
    }

    public function args()
    {
        return [
            'name' => [
                'id' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'unique:users,name']
            ],
            'email' => [
                'id' => 'email',
                'type' => Type::string(),
                'rules' => ['required', 'unique:users,email']
            ],
            'password' => [
                'id' => 'password',
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'passwordConfirmation' => [
                'id' => 'passwordConfirmation',
                'type' => Type::string(),
                'rules' => ['required', 'same:password']
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $user = User::create($args);
        $token = $user->createToken('password-granted')->accessToken;

        return [
            'token' => $token,
            'User' => $user
        ];
    }
}
