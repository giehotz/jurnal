<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllowedRoutesTable extends Migration
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
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'super_admin', 'guru', 'siswa'],
                'null'       => false,
            ],
            'enabled' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 1,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('allowed_routes');
    }

    public function down()
    {
        $this->forge->dropTable('allowed_routes');
    }
}
