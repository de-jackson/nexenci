<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\SettingModel;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = new SettingModel();
        $data = [
            'author' => 'Sai Pali Infotech',
            'system_name' => 'Sacco System',
            'system_abbr' => 'SITM-SS',
            'system_slogan' => 'Applying Knowledge',
            'system_version' => '1.0.0.1',
            'business_name' => 'SaiPali Infotech',
            'business_abbr' => 'SITM-I',
            'business_slogan' => 'Applying Knowledge',
            'business_contact' => '+256726371993',
            'business_alt_contact' => '+256754295925',
            'business_email' => 'info@sitm.com',
            'business_pobox' => 'P.O Box 01, Kampala',
            'business_address' => 'Bakuli Mengo, Kampala',
            'business_web' => 'http://smicrofinance.com',
            'business_logo' => 'default.jpg',
            'email_template_logo' => 'https://www.saipali.education/wp-content/uploads/2020/06/saipali-logo_new.png',
            'business_about' => '',
            'google_map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31918.07261450533!2d32.55871208606913!3d0.31223535062718655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbc9e4b0a27c1%3A0xf4010054ac292942!2sSai+Pali+Institute+Of+Technology+And+Management!5e0!3m2!1sen!2sug!4v1560937303770!5m2!1sen!2sug" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>',
            'tax_rate' => 30.00,
            'round_off' => 100,
            'currency_id' => 143,
        ];
        $settings->insert($data);
    }
}
