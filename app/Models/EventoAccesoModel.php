<?php

namespace App\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class EventoAccesoModel extends Model
{
    protected $table = 'eventos_acceso';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'cliente_id',
        'sucursal_id',
        'router_id',
        'branch_code',
        'tipo_evento',
        'descripcion',
        'fecha_evento',
    ];

    public function registrar(?int $clienteId, string $tipoEvento, ?string $descripcion = null, array $context = []): bool
    {
        return (bool) $this->insert([
            'cliente_id' => $clienteId,
            'sucursal_id' => $context['sucursal_id'] ?? null,
            'router_id' => $context['router_id'] ?? null,
            'branch_code' => $context['branch_code'] ?? null,
            'tipo_evento' => $tipoEvento,
            'descripcion' => $descripcion,
            'fecha_evento' => Time::now()->toDateTimeString(),
        ]);
    }
}
