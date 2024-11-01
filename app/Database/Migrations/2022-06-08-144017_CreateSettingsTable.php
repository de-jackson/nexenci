<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'author' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
            ],
            'system_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'system_abbr' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'system_slogan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'system_version' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'business_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'business_abbr' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'business_slogan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'business_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'business_alt_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'business_email' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'business_pobox' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'business_address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'business_web' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'business_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'business_about' => [
                'type' => 'text',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'background_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'email_template_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'google_map_iframe' => [
                'type' => 'text',
            ],
            'whatsapp' => [
                'type' => 'text',
            ],
            'facebook' => [
                'type' => 'text',
            ],
            'twitter' => [
                'type' => 'text',
            ],
            'instagram' => [
                'type' => 'text',
            ],
            'youtube' => [
                'type' => 'text',
            ],
            'linkedin' => [
                'type' => 'text',
            ],
            'tax_rate' => [
                'type' => 'double',
            ],
            'round_off' => [
                'type' => 'int',
                'constraint' => 6,
            ],
            'currency_id' => [
                'type' => "INT",
                'constraint' => 6,
                'unsigned' => true,
                'default' => null,
            ]
            ,'created_at datetime default current_timestamp'
            ,'updated_at datetime default current_timestamp on update current_timestamp'
            ,'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('currency_id', 'currencies', 'id');
        $this->forge->createTable('settings');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
