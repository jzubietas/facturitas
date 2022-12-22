<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeActivo($query, $status = '1')
    {
        return $query->where($this->qualifyColumn('estado'), '=', $status);
    }
    public function scopePagado($query, $value = '2')
    {
        return $query->where($this->qualifyColumn('pagado'), '=', $value);
    }
}
