<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titular extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

    /*protected $fillable = [
        'id','banco','created_at'
     ];*/
    
}
