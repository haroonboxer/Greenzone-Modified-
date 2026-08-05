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
    public $email;
    public $roles = [];
    public $claims = [];
    public $image;

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->roles = $attributes['roles'] ?? [];
        $this->claims = $attributes['claims'] ?? [];
        $this->image = $attributes['image'] ?? null;
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
}
