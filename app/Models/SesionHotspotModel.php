<?php

namespace App\Models;

use CodeIgniter\Model;

class SesionHotspotModel extends Model
{
    protected $table = 'sesiones_hotspot';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'cliente_id',
        'sucursal_id',
        'router_id',
        'branch_code',
        'router_code',
        'mac_address',
        'ip_address',
        'hotspot_nombre',
        'fecha_inicio',
        'fecha_fin',
        'duracion_minutos',
        'autorizado',
        'observaciones',
    ];

    public function getUltimaSesionAutorizada(int $clienteId): ?array
    {
        return $this->where('cliente_id', $clienteId)
            ->where('autorizado', 1)
            ->orderBy('fecha_fin', 'DESC')
            ->first();
    }

    public function paginateForAdmin(array $filters, int $perPage = 15): array
    {
        $builder = $this->builder();
        $builder->select('sesiones_hotspot.*, clientes.nombre as cliente_nombre, clientes.celular, sucursales.nombre as sucursal_nombre');
        $builder->join('clientes', 'clientes.id = sesiones_hotspot.cliente_id');
        $builder->join('sucursales', 'sucursales.id = sesiones_hotspot.sucursal_id', 'left');

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $builder->groupStart()
                ->like('clientes.nombre', $search)
                ->orLike('clientes.celular', $search)
                ->orLike('sesiones_hotspot.mac_address', $search)
                ->orLike('sesiones_hotspot.ip_address', $search)
                ->groupEnd();
        }

        if ($filters['autorizado'] !== '' && $filters['autorizado'] !== null) {
            $builder->where('sesiones_hotspot.autorizado', (int) $filters['autorizado']);
        }

        if (! empty($filters['sucursal_id'])) {
            $builder->where('sesiones_hotspot.sucursal_id', (int) $filters['sucursal_id']);
        }

        $builder->orderBy('sesiones_hotspot.fecha_inicio', 'DESC');

        return $this->paginate($perPage, 'sesiones', null, 0, $builder);
    }
}
