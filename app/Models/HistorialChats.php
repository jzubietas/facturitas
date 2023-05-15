<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialChats extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'celular',
        'user_registro',
        'subido',
        'created_at',
        'updated_at',
        'estado'
    ];
}
