<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataverseTsvMetadata extends Migration
{
    public function up()
    {
$this->forge->addField([
            'id_m' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'm_active' => [
                'type' => 'INT',
                'default' => 1
            ],
            'm_schema' => [
                'type' => 'INT',
                'default' => 0
            ],                        
            'm_name' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'm_title' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'm_description' => [
                'type' => 'TEXT'
            ],
            'm_watermark' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'm_fieldType' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'm_displayOrder' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_displayFormat' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'm_advancedSearchField' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_allowControlledVocabulary' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_allowmultiples' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_facetable' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_displayoncreate' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_required' => [
                'type' => 'INT',
                'default' => 0
            ],
            'm_parent' => [
                'type' => 'INT',
                'default' => 0
            ],            
            'metadatablock_id' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
            ],            
            'm_termURI' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],                                                                                     
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_m', true);
        $this->forge->createTable('dataverse_tsv_metadata');
    }

    public function down()
    {
        //
    }
}
