<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SituacionClientes extends Model implements HasMedia
{
    use HasFactory;
    use CommonModel;
    use InteractsWithMedia;

    protected $guarded = ['id'];

  protected $fillable = [
    'cliente_id',
      'user_id',
      'user_identificador',
      'user_clavepedido',
    'situacion',
    'cantidad_pedidos',
    'anulados',
    'activos',
    'periodo',
    'flag_fp'
  ];

}
