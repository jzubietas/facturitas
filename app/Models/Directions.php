<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directions extends Model
{
    use HasFactory;
    use CommonModel;
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'rol',
        'distrito',
        'direccion_recojo',
        'numero_recojo',
    ];

}
