<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    const ABANDONO_RECIENTE ="ABANDONO RECIENTE";
    const ABANDONO_PERMANENTE ="ABANDONO PERMANENTE";
    const RECURRENTE ="RECURRENTE";
    const NUEVO ="NUEVO";
    const RECUPERADO ="RECUPERADO";

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
