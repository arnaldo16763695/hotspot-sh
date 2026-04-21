<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
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
}
