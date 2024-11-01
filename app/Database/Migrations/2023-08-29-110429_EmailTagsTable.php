<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmailTagsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true,
            ],
            'tag_name' => [
                'type'=>'varchar',
                'constraint'=>20,
            ],
            'slug' =>[
                'type' => 'varchar',
                'constraint'=>20,
            ],
            'color' => [
                'type'=>'enum',
                'constraint' => ['primary', 'info', 'secondary', 'success', 'warning', 'danger'],
                'default' => 'primary',
            ],
            'status' =>[
                'type' => 'enum',
                'constraint'=>['active', 'inactive'],
                'default' => 'active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null'
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('emailtags');
    }

    public function down()
    {
        $this->forge->dropTable('emailtags');
    }
}
