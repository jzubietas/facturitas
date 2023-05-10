<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pago()
    {
        return $this->belongsTo(Pago::class,'pago_id');
    }
}
