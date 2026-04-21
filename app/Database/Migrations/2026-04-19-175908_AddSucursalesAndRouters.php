<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSucursalesAndRouters extends Migration
{
    public function up(): void
    {
        $this->createSucursalesTable();
        $this->createRoutersTable();
        $this->extendSesionesTable();
        $this->extendEventosTable();
    }

    public function down(): void
    {
        $this->dropForeignKeyIfExists('eventos_acceso', 'eventos_acceso_router_id_foreign');
        $this->dropForeignKeyIfExists('eventos_acceso', 'eventos_acceso_sucursal_id_foreign');
        $this->dropColumnIfExists('eventos_acceso', 'branch_code');
        $this->dropColumnIfExists('eventos_acceso', 'router_id');
        $this->dropColumnIfExists('eventos_acceso', 'sucursal_id');

        $this->dropForeignKeyIfExists('sesiones_hotspot', 'sesiones_hotspot_router_id_foreign');
        $this->dropForeignKeyIfExists('sesiones_hotspot', 'sesiones_hotspot_sucursal_id_foreign');
        $this->dropColumnIfExists('sesiones_hotspot', 'router_code');
        $this->dropColumnIfExists('sesiones_hotspot', 'branch_code');
        $this->dropColumnIfExists('sesiones_hotspot', 'router_id');
        $this->dropColumnIfExists('sesiones_hotspot', 'sucursal_id');

        $this->forge->dropTable('mikrotik_routers', true);
        $this->forge->dropTable('sucursales', true);
    }

    private function createSucursalesTable(): void
    {
        if (! $this->db->tableExists('sucursales')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'codigo' => ['type' => 'VARCHAR', 'constraint' => 30],
                'nombre' => ['type' => 'VARCHAR', 'constraint' => 150],
                'ciudad' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'direccion' => ['type' => 'TEXT', 'null' => true],
                'estado' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'activa'],
                'fecha_registro' => ['type' => 'DATETIME', 'null' => false],
                'fecha_actualizacion' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('codigo', 'uk_sucursales_codigo');
            $this->forge->addKey('estado');
            $this->forge->createTable('sucursales', true);
        }
    }

    private function createRoutersTable(): void
    {
        if (! $this->db->tableExists('mikrotik_routers')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'sucursal_id' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
                'codigo' => ['type' => 'VARCHAR', 'constraint' => 40],
                'nombre_router' => ['type' => 'VARCHAR', 'constraint' => 120],
                'host' => ['type' => 'VARCHAR', 'constraint' => 150],
                'puerto' => ['type' => 'INT', 'constraint' => 11, 'default' => 8728],
                'usuario' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
                'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'estado' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'activo'],
                'fecha_registro' => ['type' => 'DATETIME', 'null' => false],
                'fecha_actualizacion' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('codigo', 'uk_routers_codigo');
            $this->forge->addKey('sucursal_id');
            $this->forge->addKey('estado');
            $this->forge->addForeignKey('sucursal_id', 'sucursales', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('mikrotik_routers', true);
        }
    }

    private function extendSesionesTable(): void
    {
        $this->addColumnIfMissing('sesiones_hotspot', 'sucursal_id', [
            'type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true, 'after' => 'cliente_id',
        ]);
        $this->addColumnIfMissing('sesiones_hotspot', 'router_id', [
            'type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true, 'after' => 'sucursal_id',
        ]);
        $this->addColumnIfMissing('sesiones_hotspot', 'branch_code', [
            'type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'router_id',
        ]);
        $this->addColumnIfMissing('sesiones_hotspot', 'router_code', [
            'type' => 'VARCHAR', 'constraint' => 40, 'null' => true, 'after' => 'branch_code',
        ]);

        $this->addIndexIfMissing('sesiones_hotspot', 'sucursal_id', 'sesiones_hotspot_sucursal_id_idx');
        $this->addIndexIfMissing('sesiones_hotspot', 'router_id', 'sesiones_hotspot_router_id_idx');
        $this->addIndexIfMissing('sesiones_hotspot', 'branch_code', 'sesiones_hotspot_branch_code_idx');
        $this->addIndexIfMissing('sesiones_hotspot', 'router_code', 'sesiones_hotspot_router_code_idx');
        $this->addForeignKeyIfMissing('sesiones_hotspot', 'sesiones_hotspot_sucursal_id_foreign', 'sucursal_id', 'sucursales', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKeyIfMissing('sesiones_hotspot', 'sesiones_hotspot_router_id_foreign', 'router_id', 'mikrotik_routers', 'id', 'SET NULL', 'CASCADE');
    }

    private function extendEventosTable(): void
    {
        $this->addColumnIfMissing('eventos_acceso', 'sucursal_id', [
            'type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true, 'after' => 'cliente_id',
        ]);
        $this->addColumnIfMissing('eventos_acceso', 'router_id', [
            'type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true, 'after' => 'sucursal_id',
        ]);
        $this->addColumnIfMissing('eventos_acceso', 'branch_code', [
            'type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'router_id',
        ]);

        $this->addIndexIfMissing('eventos_acceso', 'sucursal_id', 'eventos_acceso_sucursal_id_idx');
        $this->addIndexIfMissing('eventos_acceso', 'router_id', 'eventos_acceso_router_id_idx');
        $this->addIndexIfMissing('eventos_acceso', 'branch_code', 'eventos_acceso_branch_code_idx');
        $this->addForeignKeyIfMissing('eventos_acceso', 'eventos_acceso_sucursal_id_foreign', 'sucursal_id', 'sucursales', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKeyIfMissing('eventos_acceso', 'eventos_acceso_router_id_foreign', 'router_id', 'mikrotik_routers', 'id', 'SET NULL', 'CASCADE');
    }

    private function addColumnIfMissing(string $table, string $column, array $definition): void
    {
        if (! $this->db->fieldExists($column, $table)) {
            $this->forge->addColumn($table, [$column => $definition]);
        }
    }

    private function dropColumnIfExists(string $table, string $column): void
    {
        if ($this->db->fieldExists($column, $table)) {
            $this->forge->dropColumn($table, $column);
        }
    }

    private function addIndexIfMissing(string $table, string $column, string $indexName): void
    {
        $exists = $this->db->query(
            "SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1",
            [$table, $indexName]
        )->getRowArray();

        if ($exists === null) {
            $this->db->query(sprintf('ALTER TABLE `%s` ADD INDEX `%s` (`%s`)', $table, $indexName, $column));
        }
    }

    private function addForeignKeyIfMissing(string $table, string $constraint, string $column, string $refTable, string $refColumn, string $onDelete, string $onUpdate): void
    {
        $exists = $this->db->query(
            "SELECT 1 FROM information_schema.referential_constraints WHERE constraint_schema = DATABASE() AND table_name = ? AND constraint_name = ? LIMIT 1",
            [$table, $constraint]
        )->getRowArray();

        if ($exists === null) {
            $sql = sprintf(
                'ALTER TABLE `%s` ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`) ON DELETE %s ON UPDATE %s',
                $table,
                $constraint,
                $column,
                $refTable,
                $refColumn,
                $onDelete,
                $onUpdate
            );
            $this->db->query($sql);
        }
    }

    private function dropForeignKeyIfExists(string $table, string $constraint): void
    {
        $exists = $this->db->query(
            "SELECT 1 FROM information_schema.referential_constraints WHERE constraint_schema = DATABASE() AND table_name = ? AND constraint_name = ? LIMIT 1",
            [$table, $constraint]
        )->getRowArray();

        if ($exists !== null) {
            $this->db->query(sprintf('ALTER TABLE `%s` DROP FOREIGN KEY `%s`', $table, $constraint));
        }
    }
}
