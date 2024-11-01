<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmailsTable extends Migration
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
            'label' => [
                'type'=>'enum',
                'constraint'=>['draft', 'spam', 'important', 'trash', 'archive', 'starred'],
                'default' => null
            ],
            'tag_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'null'=>true,
            ],
            'type' =>[
                'type' => 'enum',
                'constraint'=>['Cc', 'Bcc'],
                'default' => null,
            ],
            'sender_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'sender_account' => [
                'type'=>'enum',
                'constraint'=>['Super', 'Administrator', 'Employee', 'Client'],
                'default'=>null
            ],
            'recipient_id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
            ],
            'recipient_account' => [
                'type'=>'enum',
                'constraint'=>['Super', 'Administrator', 'Employee', 'Client'],
                'default'=>null
            ],
            'subject' => [
                'type'=>'varchar',
                'constraint'=>255,
            ],
            'message' => [
                'type'=>'text',
            ],
            'status' =>[
                'type' => 'enum',
                'constraint'=>['read', 'unread'],
                'default' => 'unread',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null'
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('tag_id','emailtags', 'id');
        $this->forge->createTable('emails');
    }

    public function down()
    {
        $this->forge->dropTable('emails');
    }
}
