<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\EmailTagsModel;

class EmailTagsSeeder extends Seeder
{
    public function run()
    {
        $model = new EmailTagsModel();
        $data = [
            [
                'tag_name' => 'Info',
                'slug' => 'info',
                'color' => 'info',
            ],
            [
                'tag_name' => 'Promo',
                'slug' => 'promo',
                'color' => 'primary',
            ],
            [
                'tag_name' => 'Social',
                'slug' => 'social',
                'color' => 'success',
            ],
            [
                'tag_name' => 'Notice',
                'slug' => 'notice',
                'color' => 'warning',
            ],
            [
                'tag_name' => 'Reminder',
                'slug' => 'reminder',
                'color' => 'danger',
            ],
        ];
        $model->insertBatch($data);
    }
}
