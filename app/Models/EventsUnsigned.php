<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventsUnsigned extends Model
{
    use HasFactory;
    use CommonModel;

    protected $fillable = [
        'title', 'description','color','attach'
    ];
}
