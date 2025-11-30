<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMissingRoutesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uri' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'guessed_controller' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'guessed_method' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'resolved', 'ignored'],
                'default'    => 'pending',
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'null'    => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('uri', false, false, 'uri_index');
        $this->forge->addKey('status', false, false, 'status_index');
        $this->forge->createTable('missing_routes');
    }

    public function down()
    {
        $this->forge->dropTable('missing_routes');
    }
}
