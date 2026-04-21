<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class HotspotDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Time::now()->toDateTimeString();

        $existingBranch = $this->db->table('sucursales')
            ->where('codigo', 'CCS01')
            ->get()
            ->getRowArray();

        if ($existingBranch === null) {
            $this->db->table('sucursales')->insert([
                'codigo' => 'CCS01',
                'nombre' => 'Sucursal Demo Caracas',
                'ciudad' => 'Caracas',
                'direccion' => 'Configuracion inicial de ejemplo',
                'estado' => 'activa',
                'fecha_registro' => $now,
                'fecha_actualizacion' => $now,
            ]);

            $sucursalId = (int) $this->db->insertID();
        } else {
            $sucursalId = (int) $existingBranch['id'];
        }

        $existingRouter = $this->db->table('mikrotik_routers')
            ->where('codigo', 'CCS01-HS1')
            ->get()
            ->getRowArray();

        if ($existingRouter === null) {
            $this->db->table('mikrotik_routers')->insert([
                'sucursal_id' => $sucursalId,
                'codigo' => 'CCS01-HS1',
                'nombre_router' => 'MikroTik Demo Caracas',
                'host' => '192.168.88.1',
                'puerto' => 443,
                'usuario' => 'admin',
                'password' => '',
                'estado' => 'activo',
                'fecha_registro' => $now,
                'fecha_actualizacion' => $now,
            ]);
        } else {
            $this->db->table('mikrotik_routers')
                ->where('id', $existingRouter['id'])
                ->update([
                    'puerto' => 443,
                    'fecha_actualizacion' => $now,
                ]);
        }
    }
}
