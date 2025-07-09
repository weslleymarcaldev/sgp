<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTechnologiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'project_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'version' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['framework', 'library', 'database', 'language', 'tool'],
            ],
            'is_primary' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('technologies');
    }

    public function down()
    {
        $this->forge->dropTable('technologies');
    }
}
