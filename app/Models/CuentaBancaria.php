<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaBancaria extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

    /*protected $fillable = [
        'id','banco','created_at'
     ];*/

}
