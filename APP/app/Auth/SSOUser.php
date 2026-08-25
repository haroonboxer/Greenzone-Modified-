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
    public $departmentId;
    public $departmentName;
    public $ProvinceId;
    public $ProvinceName;
    public $LName;
    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->roles = $attributes['roles'] ?? [];
        $this->claims = $attributes['claims'] ?? [];
        $this->image = $attributes['image'] ?? null;
        $this->departmentId = $attributes['departmentId'] ?? null;
        $this->departmentName = $attributes['departmentName'] ?? null;
        $this->ProvinceId = $attributes['ProvinceId'] ?? null;
        $this->ProvinceName = $attributes['ProvinceName'] ?? null;
        $this->LName = $attributes["LName"] ?? null;
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
