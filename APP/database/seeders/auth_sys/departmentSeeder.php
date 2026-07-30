<?php

namespace Database\Seeders\auth_sys;

use App\Models\Auth\Department;
use Illuminate\Database\Seeder;

class departmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name_en' => 'Ministry of Interior Affairs',
            'name_da' => 'وزارت امور داخله',
            'name_pa' => 'د کورنیو چارو وزارت',
            'department_parent' => 0,
            'org_type' => 1,
            'created_by' => 1,
        ]);
    }
}
