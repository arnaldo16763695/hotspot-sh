<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table = 'usuarios_admin';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'role_id',
        'nombre',
        'email',
        'password_hash',
        'estado',
        'ultimo_login_at',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function findActiveByEmail(string $email): ?array
    {
        return $this->select('usuarios_admin.*, roles_admin.codigo as role_code, roles_admin.nombre as role_name')
            ->join('roles_admin', 'roles_admin.id = usuarios_admin.role_id')
            ->where('usuarios_admin.email', $email)
            ->where('usuarios_admin.estado', 'activo')
            ->where('roles_admin.estado', 'activo')
            ->first();
    }

    public function findActiveById(int $id): ?array
    {
        return $this->select('usuarios_admin.*, roles_admin.codigo as role_code, roles_admin.nombre as role_name')
            ->join('roles_admin', 'roles_admin.id = usuarios_admin.role_id')
            ->where('usuarios_admin.id', $id)
            ->where('usuarios_admin.estado', 'activo')
            ->where('roles_admin.estado', 'activo')
            ->first();
    }
}
