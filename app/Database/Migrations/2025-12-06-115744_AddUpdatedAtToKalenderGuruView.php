<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToKalenderGuruView extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kalender_guru_view', [
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kalender_guru_view', 'updated_at');
    }
}
