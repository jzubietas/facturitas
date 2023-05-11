<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Publicidad extends Model
{
    use HasFactory;
    use CommonModel;

    protected $table = 'publicidad';

    protected $fillable = [
        'id',
        'name',
        'email',
        'cargado',
        'total',
        'created_at',
        'updated_at',
        'estado'
    ];
}
