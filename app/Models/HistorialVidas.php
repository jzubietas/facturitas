<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialVidas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'id','user_id','accion','created_at','updated_at'
    ];
}
