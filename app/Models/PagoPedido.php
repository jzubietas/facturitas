<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];


    public function scopePagado($query, $value = '2')
    {
        return $query->where($this->qualifyColumn('pagado'), '=', $value);
    }
}
