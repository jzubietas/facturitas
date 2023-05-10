<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoBancario extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at', 'fecha'];

    public function scopeSinConciliar($query){
        return $query->where($this->qualifyColumn('pago'),'=','0');
    }
}
