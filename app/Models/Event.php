<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Event extends Model
{
    use HasFactory;
    use CommonModel;

    protected $fillable = [
        'id',
        'title',
        'description',
        'attach',
        'start',
        'end',
        'color',
        'colorEvento',
        'fondoEvento',
        'grupo',
        'tipo',
        'frecuencia',
        'attach',
        'status',
        'created_at',
        'updated_at'
    ];
}
