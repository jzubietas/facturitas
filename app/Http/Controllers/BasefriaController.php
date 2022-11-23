<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\User;
//use App\DataTables\BasefriaDataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class BasefriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //return $dataTable->render('base_fria.index');
        //if ($request->ajax()) {

        $data = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id', 
                    'clientes.nombre', 
                    'clientes.icelular', 
                    'clientes.celular', 
                    'u.identificador as identificador',
                    'u.rol'
                    )
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0');
            //->get();

        if(Auth::user()->rol == 'Llamadas')
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $data=$data->WhereIn('u.id',$usersasesores); 

        }else if(Auth::user()->rol == 'Jefe de llamadas')
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
                
            $data=$data->WhereIn('u.id',$usersasesores);

        }else if(Auth::user()->rol == 'Asesor')
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $data=$data->WhereIn('u.id',$usersasesores);

        }else if(Auth::user()->rol == 'Encargado')
        {
            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $data=$data->WhereIn('u.id',$usersasesores);
        }else{
            $data=$data;
        }
        $data=$data->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                            $btn="";
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function cargarid(Request $request)
    {
        if (!$request->basefria_id) {
            $html='';
        } else {
            $data = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id', 
                    'clientes.nombre', 
                    'clientes.celular', 
                    'u.identificador as identificador',
                    'u.rol'
                    )
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0')
            ->where('clientes.id',$request->basefria_id)
            ->get();

            $html=$data;
        }
        return response()->json(['html' => $html]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
