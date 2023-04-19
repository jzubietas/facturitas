<?php

namespace App\Http\Controllers;

use App\Models\CallAtention;
use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CallAtentionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $mirol = Auth::user()->rol;
        $users = User::where('cant_vidas_cero', '>', 0)
            ->select([
                'users.*',
                DB::raw("(CASE WHEN users.rol in ('Asesor','COBRANZAS','Llamadas') THEN (select u.name  from users u where u.id=users.supervisor limit 1) ELSE
        (select us.name  from users us where us.id=users.jefe limit 1)
         END) AS nombre_jefe"),
            ]);
        if ($mirol == User::ROL_JEFE_LLAMADAS) { User::ROL_LLAMADAS;
            $users = $users->where('jefe', Auth::user()->id)->where("rol", User::ROL_LLAMADAS);
            $users = $users->orderBy('name', 'ASC')->get();
        } else if ($mirol == User::ROL_JEFE_OPERARIO) {
            $users = $users->where('jefe', Auth::user()->id)->where("rol", User::ROL_OPERARIO);
            $users = $users->orderBy('name', 'ASC')->get();
        } else if ($mirol == User::ROL_ENCARGADO) {
            $users = $users->where('supervisor', Auth::user()->id)->where("rol", User::ROL_ASESOR);
            $users = $users->orderBy('exidentificador', 'ASC')->get();
        }else{
            $users = $users->orderBy('name', 'ASC')->get();
        }

        return view('callatention.index',compact("users"));
    }

    public function tabla(Request $request)
    {
        $call_atentions = CallAtention::join('users as u', 'call_atentions.user_id', 'u.id')
            ->select(
                [
                    'call_atentions.*',
                ]
            )
            ->whereNotNull('call_atentions.user_id');

        return Datatables::of(DB::table($call_atentions))
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
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
     * @param  \App\Models\CallAtention  $callAtention
     * @return \Illuminate\Http\Response
     */
    public function show(CallAtention $callAtention)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallAtention  $callAtention
     * @return \Illuminate\Http\Response
     */
    public function edit(CallAtention $callAtention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallAtention  $callAtention
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CallAtention $callAtention)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallAtention  $callAtention
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallAtention $callAtention)
    {
        //
    }

    /*public function pedidosanulaciones()
    {
        return view('pedidos.anulaciones.index');
    }*/
}
