<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWarnaKodeToKalenderGuruView extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kalender_guru_view', [
            'warna_kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'null'       => true,
                'after'      => 'keterangan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kalender_guru_view', 'warna_kode');
    }
}
