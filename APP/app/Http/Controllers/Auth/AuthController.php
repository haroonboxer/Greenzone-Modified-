<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{


    /**
     * Receives the SSO token from ASP.NET Core,
     * validates it, generates a Laravel API JWT,
     * stores it temporarily in the session,
     * then redirects React.
     */
    public function ssoLogin(Request $request)
    {

        $token = $request->input('token');

        if (!$token) {
            return response()->json([
                "message" => "Token missing."
            ], 401);
        }

        try {

            /*
             |--------------------------------------------------------------------------
             | Validate SSO Token
             |--------------------------------------------------------------------------
             */

            $decoded = JWT::decode(
                $token,
                new Key(
                    'Laravel-React-Project-Secrute-Key-2027',
                    'HS256'
                )
            );

            /*
             |--------------------------------------------------------------------------
             | Generate Laravel JWT
             |--------------------------------------------------------------------------
             */

            $laravelToken = $this->generateLaravelToken($decoded);

            /*
             |--------------------------------------------------------------------------
             | Store Laravel JWT temporarily
             |--------------------------------------------------------------------------
             */

            session([
                'react_token' => $laravelToken
            ]);

            /*
             |--------------------------------------------------------------------------
             | Redirect React
             |--------------------------------------------------------------------------
             */

            return redirect("http://127.0.0.1:3011/auth/callback");
        } catch (Exception $ex) {

            dd([
                "message" => $ex->getMessage(),
                "line" => $ex->getLine(),
                "file" => $ex->getFile()
            ]);
        }
    }
    /**
     * React calls this endpoint one time
     * to obtain the Laravel JWT.
     */
    public function getReactToken(Request $request)
    {

        $token = session('react_token');

        if (!$token) {

            return response()->json([
                "message" => "Token not found."
            ], 401);
        }

        /*
         * Optional:
         * Remove token after first use.
         */

        //session()->forget('react_token');

        return response()->json([
            "token" => $token
        ]);
    }
    /**
     * Generate Laravel JWT
     */
    private function generateLaravelToken($decoded)
    {
        // Roles
        $roles = $decoded->Role ?? [];

        if (is_string($roles)) {
            $roles = json_decode($roles, true);
        }

        if (!is_array($roles)) {
            $roles = [];
        }

        // Claims
        $claims = $decoded->role_claims ?? [];

        if (is_string($claims)) {
            $claims = json_decode($claims, true);
        }

        if (!is_array($claims)) {
            $claims = [];
        }

        // Permissions
        $permissions = [];

        foreach ($claims as $claim) {
            if (
                isset($claim['ClaimType'], $claim['ClaimValue']) &&
                filter_var($claim['ClaimValue'], FILTER_VALIDATE_BOOLEAN)
            ) {
                $permissions[] = $claim['ClaimType'];
            }
        }

        // Projects
        $projects = $decoded->Project ?? [];

        if (is_string($projects)) {
            $projects = json_decode($projects, true);
        }

        if (!is_array($projects)) {
            $projects = [];
        }

        // UserInfo
        $UserInfo = $decoded->UserInfo ?? $decoded->user_info ?? null;

        if (is_string($UserInfo)) {
            $userinfo = json_decode($UserInfo, true);
        } else {
            $userinfo = $UserInfo;
        }

        if (!is_array($userinfo)) {
            $userinfo = [];
        }

        $payload = [

            // User
            "id" => $userinfo['Id'] ?? "",

            "name" => $userinfo['Name'] ?? "",

            "LName" => $userinfo['UserNameInLocalLang'] ?? "",

            "username" => $userinfo['UserName'] ?? "",

            "email" => $userinfo['email'] ?? "",

            "image" => $userinfo['image'] ?? "",

            "signature" => $userinfo['signature'] ?? "",

            "departmentId" => $userinfo['DepartmentId'] ?? "",

            "departmentName" => $userinfo['DepartmentName'] ?? "",

            "provinceId" => $userinfo['ProvinceId'] ?? "",

            "provinceName" => $userinfo['provinceName'] ?? "",

            // Roles & permissions
            "role" => $roles,

            "permissions" => $permissions,

            // Projects
            "projects" => $projects,

            // JWT
            "iat" => time(),

            "exp" => time() + 3600,
        ];

        return JWT::encode(
            $payload,
            "Laravel-React-Project-Secrute-Key-2027",
            "HS256"
        );
    }
    // private function generateLaravelToken($decoded)
    // {


    //     $roles = $decoded->Role ?? [];

    //     if (is_string($roles)) {
    //         $roles = json_decode($roles, true);
    //     }

    //     if (!is_array($roles)) {
    //         $roles = [];
    //     }

    //     $claims = $decoded->role_claims ?? [];

    //     if (is_string($claims)) {
    //         $claims = json_decode($claims, true);
    //     }

    //     if (!is_array($claims)) {
    //         $claims = [];
    //     }

    //     $permissions = [];

    //     foreach ($claims as $claim) {

    //         if (
    //             isset($claim['ClaimType'], $claim['ClaimValue']) &&
    //             filter_var($claim['ClaimValue'], FILTER_VALIDATE_BOOLEAN)
    //         ) {
    //             $permissions[] = $claim['ClaimType'];
    //         }
    //     }

    //     $UserInfo = $decoded->user_info ?? null;

    //     $UserInfo = $decoded->UserInfo ?? $decoded->user_info ?? null;


    //     if (is_string($UserInfo)) {
    //         $userinfo = json_decode($UserInfo, true);
    //     } else {
    //         $userinfo = $UserInfo;
    //     }
    //     dd($decoded);

    //     $payload = [

    //         // UserModel
    //         "id" =>             $userinfo->Id ?? $userinfo['Id'] ?? "",

    //         "name" =>           $userinfo->Name ?? $userinfo['Name'] ?? "",
    //         "LName" =>          $userinfo['UserNameInLocalLang'] ?? "",

    //         "username" =>       $userinfo->UserName ?? $userinfo["UserName"],

    //         "email" =>          $userinfo->email ?? $userinfo['email'] ?? "",

    //         "image" =>          $userinfo->image ?? $userinfo['image'] ?? "",

    //         "signature" =>      $userinfo->signature ?? $userinfo['signature'] ?? "",

    //         "departmentId" => $userinfo->DepartmentId ?? $userinfo['DepartmentId'],

    //         "departmentName" => $userinfo->DepartmentName ?? $userinfo['DepartmentName'] ?? "",

    //         "provinceId" => $userinfo->ProvinceId ?? $userinfo['ProvinceId'],

    //         "provinceName" =>   $userinfo->provinceName ?? $userinfo['provinceName'] ?? "",

    //         "LName" => $userinfo['UserNameInLocalLang'] ?? "",

    //         // React UserModel expects these names
    //         "role" => $roles,

    //         "permissions" => $permissions,

    //         "systems" => $userinfo->systems ?? $userinfo['systems'] ?? [],

    //         // JWT
    //         "iat" => time(),

    //         "exp" => time() + 3600,
    //     ];

    //     return JWT::encode(
    //         $payload,
    //         "Laravel-React-Project-Secrute-Key-2027",
    //         "HS256"
    //     );
    // }
}
