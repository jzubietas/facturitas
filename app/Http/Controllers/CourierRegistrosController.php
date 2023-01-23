<?php

namespace App\Http\Controllers;

use App\Models\CourierRegistro;
use App\Models\MovimientoBancario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierRegistrosController extends Controller
{
    //
    public function index()
    {
        $superasesor = User::where('rol', 'Super asesor')->count();
        return view('courier_registro.index', compact('superasesor' ));
    }

    public function indextabla(Request $request)
    {
        $courier = CourierRegistro::where('estado','<>', '1');
        return Datatables::of(DB::table($courier))
            ->addIndexColumn()
            ->addColumn('action', function($courier){
                $btn='';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
