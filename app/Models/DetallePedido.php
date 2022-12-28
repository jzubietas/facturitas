<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeActivo($query, $status = '1')
    {
        return $query->where($this->qualifyColumn('estado'), '=', $status);
    }
}
