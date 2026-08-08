<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Guard;

class JwtGuard implements Guard
{
    protected $user;

    public function user()
    {

        if ($this->user) {
            return $this->user;
        }

        $token = request()->bearerToken();


        if (!$token) {
            return null;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Decode Laravel JWT
            |--------------------------------------------------------------------------
            */

            $decoded = JWT::decode(

                $token,

                new Key(
                    env('JWT_SECRET'),
                    "HS256"
                )

            );

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Right now the JWT only contains:
            |
            | user_id
            | user_name
            |
            | Roles & Permissions will be loaded
            | from SSO later.
            |
            */



            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $this->user = new SSOUser([

                "id" => $decoded->id ?? null,

                "name" => $decoded->name ?? null,

                "email" => $decoded->email ?? null,

                "image" => $decoded->image ?? null,

                "roles" => $decoded->role ?? [],

                "claims" => $decoded->permissions ?? []

            ]);

            return $this->user;
        } catch (\Exception $e) {

            logger()->error($e->getMessage());

            return null;
        }
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->user()?->id;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function hasUser()
    {
        return !is_null($this->user);
    }
}
