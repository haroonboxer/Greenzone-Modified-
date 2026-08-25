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

            // Get UserInfo from token
            $userInfo = $decoded->UserInfo ?? null;

            // UserInfo is JSON string in your token
            if (is_string($userInfo)) {
                $userInfo = json_decode($userInfo);
            }


            // $this->user = new SSOUser([

            //     // From top-level token
            //     "id" => $decoded->id ?? $userInfo->Id ?? null,

            //     "name" => $decoded->name ?? $userInfo->Name ?? null,

            //     "email" => $decoded->email ?? $userInfo->email ?? null,

            //     "image" => $decoded->image ?? $userInfo->Image ?? null,

            //     "roles" => $decoded->role ?? [],

            //     "claims" => $decoded->permissions ?? [],


            //     // From UserInfo
            //     "departmentId" => $decoded->departmentId ?? $userInfo->DepartmentId ?? null,

            //     "departmentName" => $decoded->departmentName ?? $userInfo->DepartmentName ?? null,

            //     "ProvinceId" => $decoded->provinceId ?? $userInfo->ProvinceId ?? null,

            //     "ProvinceName" => $decoded->provinceName ?? $userInfo->provinceName ?? null,

            //     "LName" => $decoded->UserNameInLocalLang ?? null,
            // ]);
            $this->user = new SSOUser([

                "id" => $decoded->id ?? $userInfo->Id ?? null,

                "name" => $decoded->name ?? $userInfo->Name ?? null,

                "email" => $decoded->email ?? $userInfo->email ?? null,

                "image" => $decoded->image ?? $userInfo->Image ?? null,

                "roles" => $decoded->role ?? [],

                "claims" => $decoded->permissions ?? [],

                "departmentId" =>
                !empty($decoded->departmentId)
                    ? $decoded->departmentId
                    : ($userInfo->DepartmentId ?? null),

                "departmentName" =>
                !empty($decoded->departmentName)
                    ? $decoded->departmentName
                    : ($userInfo->DepartmentName ?? null),

                "ProvinceId" =>
                $decoded->provinceId ?? $userInfo->ProvinceId ?? null,

                "ProvinceName" =>
                $decoded->provinceName ?? $userInfo->provinceName ?? null,

                "LName" =>
                $userInfo->UserNameInLocalLang ?? null,
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
