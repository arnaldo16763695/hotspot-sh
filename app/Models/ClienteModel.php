<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'nombre',
        'celular',
        'correo',
        'fecha_nacimiento',
        'acepta_promociones',
        'acepta_terminos',
        'fecha_registro',
        'fecha_actualizacion',
        'estado',
    ];

    public function paginateForAdmin(array $filters, int $perPage = 15): array
    {
        $builder = $this->builder();
        $builder->select('clientes.*, MAX(sesiones_hotspot.fecha_inicio) as ultima_sesion, sucursales.nombre as ultima_sucursal');
        $builder->join('sesiones_hotspot', 'sesiones_hotspot.cliente_id = clientes.id', 'left');
        $builder->join('sucursales', 'sucursales.id = sesiones_hotspot.sucursal_id', 'left');

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $builder->groupStart()
                ->like('clientes.nombre', $search)
                ->orLike('clientes.celular', $search)
                ->orLike('clientes.correo', $search)
                ->groupEnd();
        }

        if (! empty($filters['estado'])) {
            $builder->where('clientes.estado', $filters['estado']);
        }

        if (! empty($filters['sucursal_id'])) {
            $builder->where('sesiones_hotspot.sucursal_id', (int) $filters['sucursal_id']);
        }

        $builder->groupBy('clientes.id');
        $builder->orderBy('clientes.fecha_registro', 'DESC');

        return $this->paginate($perPage, 'clientes', null, 0, $builder);
    }

    public function findForAdminDetail(int $clienteId): ?array
    {
        $builder = $this->builder();
        $builder->select('clientes.*, MAX(sesiones_hotspot.fecha_inicio) as ultima_sesion, sucursales.nombre as ultima_sucursal');
        $builder->join('sesiones_hotspot', 'sesiones_hotspot.cliente_id = clientes.id', 'left');
        $builder->join('sucursales', 'sucursales.id = sesiones_hotspot.sucursal_id', 'left');
        $builder->where('clientes.id', $clienteId);
        $builder->groupBy('clientes.id');

        return $builder->get()->getRowArray() ?: null;
    }
}
