<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\Access\Authorizable as AuthorizableTrait;

class SSOUser implements Authenticatable, Authorizable
{
    use AuthorizableTrait;

    public $id;

    public $name;

    public $roles = [];

    public $claims = [];

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? null;

        $this->name = $attributes['name'] ?? null;

        $this->roles = $attributes['roles'] ?? [];

        $this->claims = $attributes['claims'] ?? [];
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value) {}

    public function getRememberTokenName()
    {
        return null;
    }
    public function getAuthPasswordName()
    {
        return null;
    }
}
