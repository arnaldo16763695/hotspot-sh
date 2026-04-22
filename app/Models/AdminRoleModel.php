<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminRoleModel extends Model
{
    protected $table = 'roles_admin';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'codigo',
        'nombre',
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    public function findByCode(string $code): ?array
    {
        return $this->where('codigo', $code)->where('estado', 'activo')->first();
    }
}
