<?php

namespace ManyLinks\GraphQL\Mutation\Auth;

use ManyLinks\Models\User;
use Auth;
use Folklore\GraphQL\Error\AuthorizationError;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class Login extends Mutation
{
    protected $attributes = [
        'name' => 'Login',
        'description' => 'Exchanges credentials for auth token'
    ];

    public function type()
    {
        return GraphQL::type('Me');
    }

    public function args()
    {
        return [
            'email' => [
                'id' => 'email',
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'password' => [
                'id' => 'password',
                'type' => Type::string(),
                'rules' => ['required']
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $valid = Auth::validate(['email' => $args['email'], 'password' => $args['password']]);
        $user = User::whereEmail($args['email'])->first();

        if (!$valid) {
            throw new AuthorizationError('Invalid credentials');
        }
        
        if (!$user->confirmed) {
            throw new AuthorizationError('Email confirmation needed');
        }

        $token = $user->createToken('password-granted')->accessToken;

        return [
            'token' => $token,
            'User' => $user
        ];
    }
}
