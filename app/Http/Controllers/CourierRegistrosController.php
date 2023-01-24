<?php

namespace App\Http\Controllers;

use App\Models\CourierRegistro;
use App\Models\MovimientoBancario;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierRegistrosController extends Controller
{

    public function indexi()
    {
        $superasesor = User::where('rol', 'Super asesor')->count();
        return view('courier_registro.index', compact('superasesor' ));
    }

    public function indextabla(Request $request)
    {
        $courier = CourierRegistro::where('id','<>', '0');
        return Datatables::of(DB::table($courier))
            ->addIndexColumn()
            ->addColumn('estado', function($courier){
                return '1';
            })
            ->addColumn('action', function($courier){
                $btn='';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function register(Request $request)
    {
        $numregistro = $request->numregistro;

        $registro = CourierRegistro::create([
            'courier_registro' => $numregistro,
            'user_created'=>auth()->user()->id,
        ]);
        $html="ok|0";
        return response()->json(['html' => $html]);
    }
    public function validarregister(Request $request)
    {
        $numregistro = $request->numregistro;
        $count=CourierRegistro::where('courier_registro',$numregistro)->where('status','1')->count();
        if($count)
        {
            if($count>0){
                return response()->json(['html' => 1]);
            }else{
                return response()->json(['html' => 0]);
            }
        }
    }

}
