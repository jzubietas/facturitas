<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionEnvio extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

}
