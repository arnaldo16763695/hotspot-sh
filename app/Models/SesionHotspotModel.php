<?php

namespace App\Models;

use CodeIgniter\Model;

class SesionHotspotModel extends Model
{
    protected $table = 'sesiones_hotspot';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
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
}
