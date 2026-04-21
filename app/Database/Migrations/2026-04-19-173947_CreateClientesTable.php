<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientesTable extends Migration
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
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'celular' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'acepta_promociones' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'acepta_terminos' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'fecha_registro' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'fecha_actualizacion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'activo',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('celular', 'uk_clientes_celular');
        $this->forge->addKey('correo');
        $this->forge->addKey('estado');
        $this->forge->createTable('clientes', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('clientes', true);
    }
}
