<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUrlQRFeature extends Migration
{
    public function up()
    {
        // Table url_entries
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'original_url' => [
                'type' => 'TEXT',
            ],
            'short_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'custom_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'click_count' => [
                'type'       => 'INT',
                'default'    => 0,
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
        $this->forge->createTable('url_entries');

        // Table qr_settings
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'url_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'qr_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'default'    => '#000000',
            ],
            'bg_color' => [
                'type'       => 'VARCHAR',
                'constraint' => '7',
                'default'    => '#FFFFFF',
            ],
            'size' => [
                'type'       => 'INT',
                'default'    => 300,
            ],
            'logo_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'frame_style' => [
                'type'       => 'ENUM',
                'constraint' => ['none', 'square', 'circle', 'rounded'],
                'default'    => 'none',
            ],
            'error_correction' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'M', 'Q', 'H'],
                'default'    => 'L',
            ],
            'version' => [
                'type'       => 'INT',
                'default'    => 5,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('url_id', 'url_entries', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('qr_settings');
    }

    public function down()
    {
        $this->forge->dropTable('qr_settings');
        $this->forge->dropTable('url_entries');
    }
}
