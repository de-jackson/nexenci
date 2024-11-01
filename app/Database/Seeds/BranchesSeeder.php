<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\BranchModel;

class BranchesSeeder extends Seeder
{
    public function run()
    {
        $branchModel = new BranchModel;
        $faker = \Faker\Factory::create();

        $branchModel->save(
            [
                'branch_name' => 'Main Branch',
                'slug' => 'main-branch',
                'branch_email' => 'admin@sitm.com',
                'branch_mobile' => '0785632882',
                'branch_address' => 'Bakuli',
                'branch_code' => "B0001",
            ]
        );
        
        /*

        for ($i = 0; $i < 50; $i++) {
            $branchModel->save(
                [
                    'branch_name'        =>    $faker->name,
                    'branch_email'       =>    $faker->email,
                    'branch_mobile'       =>   $faker->phoneNumber,
                    'branch_address'     =>    $faker->address
                ]
            );
        }

        */
    }
}
