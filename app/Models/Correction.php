<?php

namespace App\Models;

use App\Traits\CommonModel;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction extends Model
{
    use HasFactory;
    use CommonModel;

    protected $fillable = [
        'type',
        'code',
        'ruc',
        'razon_social',
        'asesor_id',
        'asesor_identify',
        'fecha_correccion',
        'motivo',
        'adjuntos',
        'detalle',
        'estado',
        'condicion_envio',
        'condicion_envio_code',
        'cant_compro'
        ];

}
