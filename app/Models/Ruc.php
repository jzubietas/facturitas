<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruc extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'num_ruc',
        'user_id',
        'cliente_id',
        'empresa',
        'estado',
        'created_at',
        'updated_at',
        'porcentaje',
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
