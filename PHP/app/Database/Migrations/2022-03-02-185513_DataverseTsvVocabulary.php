<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataverseTsvVocabulary extends Migration
{

    protected $DBGroup = 'schema';

    public function up()
    {
        $this->forge->addField([
            'id_vc' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'vc_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'vc_value' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],                       
            'vc_class_1' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ],
            'vc_class_1' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ],
            'vc_order' => [
                'type' => 'INT',
                'default' => '0'
            ],           
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_vc', true);
        $this->forge->createTable('dataverse_tsv_vocabulary');
    }

    public function down()
    {
        //
    }
}
