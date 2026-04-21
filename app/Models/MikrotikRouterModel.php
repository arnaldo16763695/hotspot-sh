<?php

namespace App\Models;

use CodeIgniter\Model;

class MikrotikRouterModel extends Model
{
    protected $table = 'mikrotik_routers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sucursal_id',
        'codigo',
        'nombre_router',
        'host',
        'puerto',
        'usuario',
        'password',
        'estado',
        'fecha_registro',
        'fecha_actualizacion',
    ];

    public function findActiveByCode(string $codigo): ?array
    {
        return $this->where('codigo', strtoupper($codigo))
            ->where('estado', 'activo')
            ->first();
    }

    public function findPrimaryForBranch(int $sucursalId): ?array
    {
        return $this->where('sucursal_id', $sucursalId)
            ->where('estado', 'activo')
            ->orderBy('id', 'ASC')
            ->first();
    }
}
