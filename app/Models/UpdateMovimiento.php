<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateMovimiento extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'obs',
        'valores_ant',
        'valores_act',
        'fecha_creacion',
        'created_at',
        'updated_at',
    ];
}
