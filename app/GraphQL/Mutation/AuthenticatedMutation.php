<?php

namespace ManyLinks\GraphQL\Mutation;

use Folklore\GraphQL\Error\AuthorizationError;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;

abstract class AuthenticatedMutation extends Mutation
{
    protected $user;

    public function __construct($attributes = [])
    {
        $this->user = auth()->user();

        parent::__construct($attributes);
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        if (!$this->user) {
            throw new AuthorizationError('No Authentication provided');
        }
    }
}
