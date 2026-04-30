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
        $builder->select(
            "clientes.*,
            (
                SELECT MAX(sh.fecha_inicio)
                FROM sesiones_hotspot sh
                WHERE sh.cliente_id = clientes.id
            ) as ultima_sesion,
            (
                SELECT s.nombre
                FROM sesiones_hotspot sh2
                LEFT JOIN sucursales s ON s.id = sh2.sucursal_id
                WHERE sh2.cliente_id = clientes.id
                ORDER BY sh2.fecha_inicio DESC, sh2.id DESC
                LIMIT 1
            ) as ultima_sucursal",
            false
        );

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
            $builder->where(
                'EXISTS (
                    SELECT 1
                    FROM sesiones_hotspot shf
                    WHERE shf.cliente_id = clientes.id
                    AND shf.sucursal_id = ' . (int) $filters['sucursal_id'] . '
                )',
                null,
                false
            );
        }

        $builder->orderBy('clientes.fecha_registro', 'DESC');

        return $this->paginate($perPage, 'clientes', null, 0, $builder);
    }

    public function findForAdminDetail(int $clienteId): ?array
    {
        $builder = $this->builder();
        $builder->select(
            "clientes.*,
            (
                SELECT MAX(sh.fecha_inicio)
                FROM sesiones_hotspot sh
                WHERE sh.cliente_id = clientes.id
            ) as ultima_sesion,
            (
                SELECT s.nombre
                FROM sesiones_hotspot sh2
                LEFT JOIN sucursales s ON s.id = sh2.sucursal_id
                WHERE sh2.cliente_id = clientes.id
                ORDER BY sh2.fecha_inicio DESC, sh2.id DESC
                LIMIT 1
            ) as ultima_sucursal",
            false
        );
        $builder->where('clientes.id', $clienteId);

        return $builder->get()->getRowArray() ?: null;
    }
}
