<?php

namespace Database\Seeders\auth_sys;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'vehicle-list',
                'name_dr' => 'لیست واسطه',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'vehicle-create',
                'name_dr' => 'اضافه کردن واسطه',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'vehicle-edit',
                'name_dr' => 'تجدید کردن واسطه',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'vehicle-view',
                'name_dr' => 'نمایش کردن واسطه',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'vehicle-status',
                'name_dr' => 'تغیر حالت واسطه',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'driver-list',
                'name_dr' => 'لیست راننده ها',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'driver-create',
                'name_dr' => 'اضافه کردن راننده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'driver-edit',
                'name_dr' => 'تجدید کردن راننده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'driver-view',
                'name_dr' => 'نمایش کردن راننده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'driver-status',
                'name_dr' => 'تغیر حالت راننده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'license-list',
                'name_dr' => 'لیست جواز ',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'license-create',
                'name_dr' => 'اضافه کردن جواز ',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'license-edit',
                'name_dr' => 'تجدید کردن جواز ',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'license-view',
                'name_dr' => 'نمایش کردن جواز ',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'license-status',
                'name_dr' => 'تغیر حالت جواز ',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'cards',
                'name_dr' => 'بتن کارت های چاپ شده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'cards-view-income',
                'name_dr' => 'نمایش کارت های چاپ شده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'cards-print-accept',
                'name_dr' => 'تایید کارت های چاپ شده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'cards-print-reject',
                'name_dr' => 'رد کارت های چاپ شده',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'reports',
                'name_dr' => 'بتن بخش گزارشات',
                'guard_name' => 'web',
                'system_id' => 1,
            ],
            [
                'name' => 'user-create',
                'name_dr' => 'ثبت کاربر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'user-list',
                'name_dr' => 'لیست کاربر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'user-edit',
                'name_dr' => 'تجدید کاربر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'user-destroy',
                'name_dr' => 'حذف کاربر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'role-create',
                'name_dr' => 'ثبت صلاحیت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'role-list',
                'name_dr' => 'لیست صلاحیت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'role-edit',
                'name_dr' => 'تجدید صلاحیت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'admin-create',
                'name_dr' => 'ثبت سوپروایزر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'admin-edit',
                'name_dr' => 'تجدید سوپروایزر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'admin-view',
                'name_dr' => 'دیدن سوپروایزر',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'department-view',
                'name_dr' => 'دیدن مدیریت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'department-all-view',
                'name_dr' => 'دیدن همه مدیریت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'all-view',
                'name_dr' => 'دیدن همه ریکارد ها',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'province-view',
                'name_dr' => 'دیدن ریکارد های ولایت',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'view',
                'name_dr' => 'دیدن',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
            [
                'name' => 'system-report',
                'name_dr' => 'راپور سیستم',
                'guard_name' => 'web',
                'system_id' => 2,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
