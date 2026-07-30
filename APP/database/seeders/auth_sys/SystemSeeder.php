<?php

namespace Database\Seeders\auth_sys;

use App\Models\Auth\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $systems = [
            [
                'name_da' => 'سیستم مدیریتی جواز وسایط برای ساحات سبز',
                'name_pa' => 'د شین ساحو د وسایطو د جواز مدیریت سیستم',
                'icon' => 'group.png',
                'route' => '/green-zone/list',
            ],
            [
                'name_da' => 'مدیریت کاربران',
                'name_pa' => 'د کاروونکو مدیریت',
                'icon' => 'group.png',
                'route' => '/authentication/users',
            ],
        ];

        foreach ($systems as $system)
            System::create($system);
    }
}
