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
                    'clientes.celular', 
                    //'clientes.estado', 
                    //'u.name as user',
                    'u.identificador as identificador'
                    )
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0')
            ->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="" data-target="#modal-convertir" data-toggle="modal" data-opcion="'.$row->id.'"><button class="btn btn-info btn-sm">Convertir a cliente</button></a>';
                           //$btn = '<a href="" data-target="#modal-convertir-'.$row->id.'" data-toggle="modal" data-opcion="'.$row->id.'"><button class="btn btn-info btn-sm">Convertir a cliente</button></a>';

                           $btn = $btn.'<a href="'.route('clientes.editbf', $row).'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>';
                           $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-opcion="'.$row->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                           //$btn = $btn.'<a href="" data-target="#modal-delete-'.$row->id.'" data-toggle="modal"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
       
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        //}
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
