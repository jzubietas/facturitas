<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    const ABONADO = 'ABONADO';
    const ADELANTO = 'ADELANTO';
    const OBSERVADO = 'OBSERVADO';
    const PAGO = 'PAGO';
    const PENDIENTE = 'PENDIENTE';
    const ABONADO_PARCIAL = 'ABONADO_PARCIAL';

    protected $guarded = ['id'];

    public function pedidos()
    {

    }
}
