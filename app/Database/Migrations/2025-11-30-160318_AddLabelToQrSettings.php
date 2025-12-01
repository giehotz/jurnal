<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLabelToQrSettings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('qr_settings', [
            'show_label' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'version'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('qr_settings', 'show_label');
    }
}
