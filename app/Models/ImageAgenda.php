<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAgenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'unsigned',
        'event_id',
        'filename',
        'filepath',
        'filetype',
        'status',
        'created_at',
        'updated_at',
    ];
}
