<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = new UserModel;
        $faker = \Faker\Factory::create();

        $user->save(
            [
                'name' => 'Administrator',
                'email' => 'admin@sitm.com',
                'password'    =>    password_hash("12345678", PASSWORD_DEFAULT),
                'mobile'       =>   '0785632882',
                'address'     =>    'Mengo',
                'account_type' => 'Super',
                'permissions' => 's:3:"all";',
                'branch_id' => 1,
                'staff_id' => 1,
                'access_status' => 'active'
            ]
        );
        
        /*

        for ($i = 0; $i < 150; $i++) {
            $user->save(
                [
                    'name'        =>    $faker->name,
                    'email'       =>    $faker->email,
                    'password'    =>    password_hash($faker->password, PASSWORD_DEFAULT),
                    'mobile'       =>   $faker->phoneNumber,
                    'address'     =>    $faker->address,
                    'created_at'  =>    Time::createFromTimestamp($faker->unixTime()),
                    'updated_at'  =>    Time::now()
                ]
            );
        }

        */
    }
}
