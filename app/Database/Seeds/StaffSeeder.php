<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\StaffModel;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staff = new StaffModel();
        $data = [
            'staffID' => '0001',
            'staff_name' => 'Administrator',
            'mobile' => '0785632882',
            'email' => 'admin@sitm.com',
            'address' => 'Mengo',
            'account_type' => 'Super',
            'branch_id' => 1,
            'position_id' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $staff->insert($data);
    }
}
