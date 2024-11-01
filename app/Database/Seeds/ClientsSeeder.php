<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use App\Models\ClientModel;
use App\Models\SettingModel;

class ClientsSeeder extends Seeder
{
    public function run()
    {
        $client = new ClientModel;
        $setting = new SettingModel;
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 150; $i++) {
            $client->save(
                [
                    'name' => $faker->name,
                    'account_type' => 'Client',
                    'account_no' => $setting->generateRandomNumbers(10),
                    'mobile' => $faker->phoneNumber,
                    'email' => $faker->email,
                    'alternate_no' => $faker->phoneNumber,
                    # 'gender' => trim($this->request->getVar('gender')),
                    'dob' => Time::createFromTimestamp($faker->unixTime()),
                    # 'marital_status' => trim($this->request->getVar('marital_status')),
                    # 'religion' => trim($this->request->getVar('religion')),
                    'nationality' => 'Ugandan',
                    'staff_id' => 1,
                    'occupation' => $faker->address,
                    'job_location' => $faker->address,
                    'residence' => $faker->address,
                    'id_type' => 'National ID',
                    'id_number' => $setting->generateRandomNumbers(14),
                    'id_expiry_date' => Time::createFromTimestamp($faker->unixTime()),
                    'next_of_kin_name' => $faker->name,
                    # 'next_of_kin_relationship' => trim($this->request->getVar('nok_relationship')),
                    'next_of_kin_contact' => $faker->phoneNumber,
                    'next_of_kin_alternate_contact' => $faker->phoneNumber,
                    'nok_email' => $faker->email,
                    'nok_address' => $faker->address,
                    'password' => password_hash($faker->password, PASSWORD_DEFAULT),
                    'branch_id' => 1,
                    'created_at'  => Time::createFromTimestamp($faker->unixTime()),
                    'updated_at'  => Time::now()
                ]


            );
        }
    }
}
