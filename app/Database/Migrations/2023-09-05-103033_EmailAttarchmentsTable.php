<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmailAttachmentsTable extends Migration
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
            'email_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'attachment' =>[
                'type' => 'varchar',
                'constraint'=>50,
            ],
            'extension' => [
                'type'=>'varchar',
                'constraint'=>11,
            ],
            'size' => [
                'type'=>'int',
                'constraint'=>50,
                'default'=>null
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null'
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('email_id','emails', 'id');
        $this->forge->createTable('emailattachments');
    }

    public function down()
    {
        $this->forge->dropTable('emailattachments');
    }
}
