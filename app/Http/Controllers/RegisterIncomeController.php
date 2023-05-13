<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('datatable'))
        {
            $query=Cliente::query()->where('tipo',0)->activo()->whereIn('llamado',[0])
            ->select([
                'created_at',
                'celular as basefria',
                'user_clavepedido as asesor',
                'llamado'
            ]);

            if (Auth::user()->rol == "Llamadas") {
            } else if (Auth::user()->rol == "Jefe de llamadas") {
            } elseif (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.clave_pedidos', Auth::user()->clave_pedidos)
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn("user_clavepedido", $usersasesores);

            } else if (Auth::user()->rol == "Encargado") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn("user_clavepedido", $usersasesores);
            } elseif (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
                $query = $query->Where("clientes.user_clavepedido", '=', 'B');
            }else if (Auth::user()->rol == User::ROL_ASISTENTE_PUBLICIDAD) {
                $usersasesores = User::where('users.rol', User::ROL_ASESOR)
                    ->where('users.estado', '1')
                    ->whereIn('users.clave_pedidos', ['15','16','17','18','19','20','21','22','23'])
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn('user_clavepedido', $usersasesores);
            }

            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($cliente)  {
                    $btn = [];
                    $btn []='<button type="button" class="btn btn-warning btn-sm btn_llamar"  data-basefria="' . $cliente->basefria . '"><i class="fa fa-phone"></i></a>';
                    return join('', $btn);
                })
                ->rawColumns(['action'])
                //->toJson();
                ->make(true);
        }
        return view('register_income.index');
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

    public function realizoLlamada(Request $request)
    {
        $q=$request->get('basefria');

        $cliente=Cliente::query()
            ->where('tipo',0)
            ->where('llamado',0)
            ->where('celular',$q)->first();
        $cliente->update([
            'llamado'=>1,
            'asesor_llamado'=>$cliente->user_clavepedido,
            'user_llamado'=>auth()->user()->id,
            'fecha_llamado'=>now()
        ]);


    }


    public function indexChats(Request $request)
    {
        if ($request->has('datatable'))
        {
            $query=Cliente::query()->where('tipo',0)->activo()->whereIn('llamado',[0,1])
                ->whereDate('created_at','<=','2023-05-12')
                ->select([
                    'created_at',
                    'celular as basefria',
                    'user_clavepedido as asesor',
                    'llamado'
                ]);

            if (Auth::user()->rol == "Llamadas") {
            } else if (Auth::user()->rol == "Jefe de llamadas") {
            } elseif (Auth::user()->rol == "Asesor") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.clave_pedidos', Auth::user()->clave_pedidos)
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn("user_clavepedido", $usersasesores);

            } else if (Auth::user()->rol == "Encargado") {
                $usersasesores = User::where('users.rol', 'Asesor')
                    ->where('users.estado', '1')
                    ->where('users.supervisor', Auth::user()->id)
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn("user_clavepedido", $usersasesores);
            } elseif (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
                $query = $query->Where("clientes.user_clavepedido", '=', 'B');
            }else if (Auth::user()->rol == User::ROL_ASISTENTE_PUBLICIDAD) {
                $usersasesores = User::where('users.rol', User::ROL_ASESOR)
                    ->where('users.estado', '1')
                    ->whereIn('users.clave_pedidos', ['15','16','17','18','19','20','21','22','23'])
                    ->select(
                        DB::raw("users.clave_pedidos as clave_pedidos")
                    )
                    ->pluck('users.clave_pedidos');
                $query = $query->WhereIn('user_clavepedido', $usersasesores);
            }

            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($cliente)  {
                    $btn = [];
                    $btn []= '<a target="_blank" href="https://api.whatsapp.com/send?phone='.$cliente->basefria.'"><i class="fa fa-comments"></i></a>';

                    return join('', $btn);
                })
                ->rawColumns(['action'])
                //->toJson();
                ->make(true);
        }
        return view('register_chats.index');
    }

}
