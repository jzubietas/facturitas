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
        'title','description', 'start', 'end','color','colorTexto','colorBackground'
    ];
}
