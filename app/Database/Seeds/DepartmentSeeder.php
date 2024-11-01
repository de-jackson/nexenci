<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\DepartmentModel;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $department = new DepartmentModel();
        $data = [
            'department_name' => "Administration",
            'department_slug' => "administration",
        ];
        $department->save($data);
    }
}
