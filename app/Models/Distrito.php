<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public static function cargaDistrito()
    {
        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->WhereNotIn('distrito' ,['CHACLACAYO','CIENEGUILLA','LURIN','PACHACAMAC','PUCUSANA','PUNTA HERMOSA','PUNTA NEGRA','SAN BARTOLO','SANTA MARIA DEL MAR'])
            ->select([
                'distrito',                                
                //DB::raw("concat(distrito,' - ',zona) as distritonam as text"),
                'zona'
            ])->orderBy('distrito')->get();
            return $distritos;
    }
    
    

}
