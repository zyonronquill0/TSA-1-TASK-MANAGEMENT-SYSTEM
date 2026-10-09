<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTasksAndUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'task_date'  => ['type' => 'DATE'],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        // IF NOT EXISTS also supports the supplied tasks_db.sql export.
        $this->forge->createTable('tasks', true);

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'username'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'full_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('tasks', true);
    }
}
