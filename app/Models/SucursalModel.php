<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'codigo',
        'nombre',
        'ciudad',
        'direccion',
        'estado',
        'fecha_registro',
        'fecha_actualizacion',
    ];

    public function findActiveByCode(string $codigo): ?array
    {
        return $this->where('codigo', strtoupper($codigo))
            ->where('estado', 'activa')
            ->first();
    }
}
