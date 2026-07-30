<?php

namespace Database\Seeders;

use Database\Seeders\auth_sys\departmentSeeder;
use Database\Seeders\auth_sys\PermissionSeeder;
use Database\Seeders\auth_sys\SystemSeeder;
use Database\Seeders\auth_sys\UserSeeder;
use Database\Seeders\auth_sys\UserSystemSeeder;
use Database\Seeders\auth_sys\ProvinceSeeder;
use Database\Seeders\auth_sys\DistrictsSeeder;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            SystemSeeder::class,
            UserSystemSeeder::class,
            ProvinceSeeder::class,
            DistrictsSeeder::class,
            UserSeeder::class,
            departmentSeeder::class,
            
        ]);
    }
}
