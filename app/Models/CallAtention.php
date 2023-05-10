<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallAtention extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'id','user_id','user_identificador','accion','responsable','created_at','updated_at'
    ];
}
