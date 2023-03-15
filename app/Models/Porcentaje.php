<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Porcentaje extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    const FISICO_SIN_BANCA = 'FISICO - sin banca';//1
    const ELECTRONICA_SIN_BANCA = 'ELECTRONICA - sin banca';//2
    const FISICO_BANCA = 'FISICO - banca';//3
    const ELECTRONICA_BANCA = 'ELECTRONICA - banca';//4
}
