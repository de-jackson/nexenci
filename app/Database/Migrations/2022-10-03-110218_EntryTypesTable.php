<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EntryTypesTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true,
            ],
            'type' => [
                'type'=>'varchar',
                'constraint'=>20,
                // 'unique'=>true,
            ],
            'part' => [
                'type'=>'enum',
                'constraint'=>['debit', 'credit'],
            ],
            'entry_menu' => [
                'type'=>'enum',
                'constraint'=>['financing', 'expense', 'transfer', 'investment'],
            ],
            'account_typeId' => [
                'type'=>'int',
                'constraint'=>11,
                'unsigned'=>true,
                'null'=> true,
            ],
            'type_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'status' => [
                'type'=>'enum',
                'constraint'=>['active', 'inactive'],
                'default' => 'active',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
            'deleted_at datetime default null',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('account_typeId', 'account_types', 'id');
        $this->forge->createTable('entrytypes', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('entrytypes', true);
    }
}
