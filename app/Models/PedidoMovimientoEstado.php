<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoMovimientoEstado extends Model
{
    use HasFactory;

    protected $table = 'pedido_movimiento_estados';

    protected $guarded = ['id'];

  
    
}
