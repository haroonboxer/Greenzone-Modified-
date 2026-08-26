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

            $decoded = JWT::decode(
                $token,
                new Key(
                    env('JWT_SECRET'),
                    'HS256'
                )
            );

            // Convert decoded JWT object to array
            $data = json_decode(
                json_encode($decoded),
                true
            );

            // Log exactly what Laravel is reading
            logger()->info('JWT DATA', [
                'data' => $decoded
            ]);

            $this->user = new SSOUser([

                'id' => $data['id'] ?? null,

                'name' => $data['name'] ?? null,

                'email' => $data['email'] ?? null,

                'image' => $data['image'] ?? null,

                'roles' => $data['role'] ?? [],

                'claims' => $data['permissions'] ?? [],

                'departmentId' => $data['departmentId'] ?? null,

                'departmentName' => $data['departmentName'] ?? '',

                'ProvinceId' => $data['provinceId'] ?? null,

                'ProvinceName' => $data['provinceName'] ?? '',

                'LName' => $data['LName'] ?? null,
            ]);

            return $this->user;
        } catch (\Exception $e) {

            logger()->error('JWT ERROR', [
                'message' => $e->getMessage()
            ]);

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
