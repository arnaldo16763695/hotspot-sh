<?php

namespace App\Database\Seeds;

use App\Models\AdminRoleModel;
use App\Models\AdminUserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();
        $roleModel = new AdminRoleModel();
        $userModel = new AdminUserModel();

        $roles = [
            [
                'codigo' => 'super_admin',
                'nombre' => 'Super administrador',
                'descripcion' => 'Acceso total al panel y a la configuracion del sistema.',
            ],
            [
                'codigo' => 'admin_marketing',
                'nombre' => 'Administrador de marketing',
                'descripcion' => 'Gestiona clientes, campanas y exportaciones comerciales.',
            ],
            [
                'codigo' => 'admin_operaciones',
                'nombre' => 'Administrador de operaciones',
                'descripcion' => 'Gestiona sucursales, routers, sesiones y operacion del hotspot.',
            ],
        ];

        foreach ($roles as $role) {
            $existingRole = $roleModel->where('codigo', $role['codigo'])->first();

            $payload = array_merge($role, [
                'estado' => 'activo',
                'fecha_actualizacion' => $now,
            ]);

            if ($existingRole !== null) {
                $roleModel->update((int) $existingRole['id'], $payload);
                continue;
            }

            $payload['fecha_creacion'] = $now;
            $roleModel->insert($payload);
        }

        $superAdminRole = $roleModel->findByCode('super_admin');

        if ($superAdminRole === null) {
            return;
        }

        $email = 'admin@wifi.ajedev.com';
        $existingUser = $userModel->where('email', $email)->first();

        $userPayload = [
            'role_id' => (int) $superAdminRole['id'],
            'nombre' => 'Administrador principal',
            'email' => $email,
            'password_hash' => password_hash('Admin123*', PASSWORD_DEFAULT),
            'estado' => 'activo',
            'fecha_actualizacion' => $now,
        ];

        if ($existingUser !== null) {
            $userModel->update((int) $existingUser['id'], $userPayload);
            return;
        }

        $userPayload['fecha_creacion'] = $now;
        $userModel->insert($userPayload);
    }
}
