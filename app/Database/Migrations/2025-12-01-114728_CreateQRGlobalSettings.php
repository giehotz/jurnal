<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQRGlobalSettings extends Migration
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
            'default_size' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 300,
            ],
            'default_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#000000',
            ],
            'default_bg_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#FFFFFF',
            ],
            'default_logo_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'allow_custom_logo' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'allow_custom_colors' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'allow_custom_size' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'max_file_size_kb' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 2048,
            ],
            'allowed_mime_types' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('qr_global_settings');

        // Insert default settings
        $data = [
            'default_size'        => 300,
            'default_color'       => '#000000',
            'default_bg_color'    => '#FFFFFF',
            'allow_custom_logo'   => 1,
            'allow_custom_colors' => 1,
            'allow_custom_size'   => 1,
            'max_file_size_kb'    => 2048,
            'allowed_mime_types'  => 'image/png,image/jpeg,image/gif',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];
        $this->db->table('qr_global_settings')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('qr_global_settings');
    }
}
