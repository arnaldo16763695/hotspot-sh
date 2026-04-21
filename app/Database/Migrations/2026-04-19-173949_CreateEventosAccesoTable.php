<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventosAccesoTable extends Migration
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
                'null'       => true,
            ],
            'tipo_evento' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fecha_evento' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('cliente_id');
        $this->forge->addKey('tipo_evento');
        $this->forge->addKey('fecha_evento');
        $this->forge->addForeignKey('cliente_id', 'clientes', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('eventos_acceso', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('eventos_acceso', true);
    }
}
