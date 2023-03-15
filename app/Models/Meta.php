<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'rol',
        'user_id',
        'email',
        'anio',
        'mes',
        'meta_pedido',
        'meta_pedido_2',
        'meta_cobro',
        'status',
        'created_at',
        'updated_at',
        'meta_quincena',
        'cliente_nuevo',
        'cliente_recurrente',
        'cliente_recuperado_abandono',
        'cliente_recuperado_reciente',
        'cliente_nuevo_2',
        'cliente_recurrente_2',
        'cliente_recuperado_abandono_2',
        'cliente_recuperado_reciente_2',
        'meta_quincena_nuevo',
        'meta_quincena_recuperado_abandono',
        'meta_quincena_recuperado_reciente',
        'meta_intermedia'
    ];
}
