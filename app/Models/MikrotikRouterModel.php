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

    public function listForAdmin(?string $search = null): array
    {
        $builder = $this->builder();
        $builder->select('mikrotik_routers.*, sucursales.nombre as sucursal_nombre, sucursales.codigo as sucursal_codigo');
        $builder->join('sucursales', 'sucursales.id = mikrotik_routers.sucursal_id', 'left');

        if ($search !== null && trim($search) !== '') {
            $search = trim($search);
            $builder->groupStart()
                ->like('mikrotik_routers.codigo', strtoupper($search))
                ->orLike('mikrotik_routers.nombre_router', $search)
                ->orLike('mikrotik_routers.host', $search)
                ->orLike('sucursales.nombre', $search)
                ->groupEnd();
        }

        return $builder
            ->orderBy('sucursales.nombre', 'ASC')
            ->orderBy('mikrotik_routers.nombre_router', 'ASC')
            ->get()
            ->getResultArray();
    }
}
