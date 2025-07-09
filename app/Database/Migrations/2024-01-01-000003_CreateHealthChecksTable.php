<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHealthChecksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'VARCHAR','constraint' => 50],
            'project_id'    => ['type' => 'VARCHAR','constraint' => 50],
            'url'           => ['type' => 'VARCHAR','constraint' => 255],
            'status'        => ['type' => 'ENUM','constraint' => ['healthy','warning','error']],
            'response_time' => ['type' => 'INT','null' => true],
            'health_status' => ['type' => 'ENUM','constraint' => ['healthy','warning','error']],
            'created_at'    => ['type' => 'DATETIME','null' => true],
            'updated_at'    => ['type' => 'DATETIME','null' => true],
            'last_check'    => ['type' => 'DATETIME','null' => true],
            'error_message' => ['type' => 'TEXT','null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('health_checks');
    }

    public function down()
    {
        $this->forge->dropTable('health_checks');
    }
}
