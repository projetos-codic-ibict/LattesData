<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataverseTsvSchema extends Migration
{
    protected $DBGroup = 'schema';

    public function up()
    {
        $this->forge->addField([
            'id_mt' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'mt_name' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'mt_dataverseAlias' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'mt_displayName' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'mt_blockURI' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_mt', true);
        $this->forge->createTable('dataverse_tsv_schema');
    }

    public function down()
    {
        //
    }
}
