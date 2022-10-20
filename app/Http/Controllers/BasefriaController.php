<?php

namespace App\Http\Controllers;

/* use Validator; */
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BasefriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            
            $basefria = Cliente::select('clientes.nombre,clientes.celular');

            return DataTables::of($basefria)
                ->addColumn('actions', 'clientes.action')
                ->rawColumns(['actions'])
                ->make(true);

            
        }
        return view('base_fria.index');
        
    }
    

    

    
}
