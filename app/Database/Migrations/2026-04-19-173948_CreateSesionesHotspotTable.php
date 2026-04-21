<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSesionesHotspotTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'cliente_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'mac_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'hotspot_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'fecha_inicio' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'fecha_fin' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'duracion_minutos' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 60,
            ],
            'autorizado' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('cliente_id');
        $this->forge->addKey('mac_address');
        $this->forge->addKey('ip_address');
        $this->forge->addKey('hotspot_nombre');
        $this->forge->addKey('fecha_inicio');
        $this->forge->addKey('autorizado');
        $this->forge->addForeignKey('cliente_id', 'clientes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sesiones_hotspot', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('sesiones_hotspot', true);
    }
}
