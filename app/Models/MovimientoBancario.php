<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoBancario extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at', 'fecha'];

    /*protected $fillable = [
        'id','banco','created_at'
     ];*/
    
}
