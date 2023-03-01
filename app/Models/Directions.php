<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directions extends Model
{
    use HasFactory;
    use CommonModel;
    protected $guarded = ['id'];

  protected $fillable = [
    'id',
    'user_id',
    'rol',
    'distrito',
    'direccion_recojo',
    'numero_recojo', //celular
    'created_at',
    'update_at',
    //NUEVOS CAMPOS
    'destino',
    'referencia',
    'cliente',
  ];

}
