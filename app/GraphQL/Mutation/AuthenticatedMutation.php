<?php

namespace ManyLinks\GraphQL\Mutation;

use Folklore\GraphQL\Error\AuthorizationError;
use Folklore\GraphQL\Support\Mutation;

abstract class AuthenticatedMutation extends Mutation
{
    protected $user;

    public function __construct($attributes = [])
    {
        $this->user = auth()->user();

        parent::__construct($attributes);
    }

    public function authorize()
    {
        if (!$this->user) {
            throw new AuthorizationError('No Authentication provided');
        }

        return true;
    }
}
