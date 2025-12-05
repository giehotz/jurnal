<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBannerToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'banner' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => null,
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'banner');
    }
}
