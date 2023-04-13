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
        return view('pedidos.anulaciones.index');
    }

    public function tabla(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('pedidos_anulacions as pea','pedidos.id','pea.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftJoin('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->select(
                [
                    'pedidos.*',
                    'pedidos.codigo as codigos',
                    'pedidos.condicion as condiciones',
                    'pedidos.pagado as condicion_pa',
                    'c.nombre as nombres',
                    'c.icelular as icelulares',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'dp.cantidad as cantidad',
                    'dp.ruc as ruc',
                    'dp.saldo as diferencia',
                    'direccion_grupos.motorizado_status',
                    'pea.tipo as tipoanulacion',
                    'pea.tipo as itipoanulacion',
                    'pea.total_anular',
                    'pea.created_at as fechacreaanula',
                    'pea.id as idanulacion',
                    'pea.estado_aprueba_asesor',
                    'pea.estado_aprueba_encargado',
                    'pea.estado_aprueba_administrador',
                    'pea.estado_aprueba_jefeop',
                    'pea.motivo_solicitud',
                    'pea.motivo_sol_encargado',
                    'pea.motivo_sol_admin',
                    'pea.motivo_jefeop_admin',
                    'pea.resposable_create_asesor',
                    'pea.resposable_aprob_encargado',
                    DB::raw("(select us.name from users us where us.id= pea.user_id_asesor limit 1)as nameRegistra")
                ]
            )
            ->where('pea.state_solicitud',1);


        if (Auth::user()->rol == User::ROL_ASESOR) {
            $pedidos=$pedidos->whereIn('pea.estado_aprueba_asesor',[0,1])->whereIn('pea.estado_aprueba_encargado',[0,2])->whereIn('pea.estado_aprueba_administrador',[0,2]);
            $usersasesores = User::where('users.rol', User::ROL_ASESOR)
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $pedidos = $pedidos->WhereIn('pea.user_id_asesor', $usersasesores)->whereIn('pea.tipo',["C","F"]);
        } else if (Auth::user()->rol == User::ROL_ENCARGADO) {
            $usersasesores = User::where('users.rol', User::ROL_ASESOR)
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $pedidos = $pedidos->WhereIn('pea.user_id_asesor', $usersasesores)->whereIn('pea.tipo',["C","F"]);
            $pedidos=$pedidos->whereIn('estado_aprueba_encargado',[0]);
        } else if (Auth::user()->rol == User::ROL_COBRANZAS) {
            $pedidos=$pedidos->whereIn('pea.estado_aprueba_asesor',[0,1])
                ->whereIn('pea.estado_aprueba_encargado',[0,2])
                ->whereIn('pea.estado_aprueba_administrador',[0,2])
                ->whereIn('pea.tipo',["C","Q"]);
            $usersasesores = User::where('rol', User::ROL_COBRANZAS)
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $pedidos = $pedidos->WhereIn('pea.user_id_asesor', $usersasesores);
        }else if (Auth::user()->rol == User::ROL_JEFE_LLAMADAS) {
            $asesor_cobranza=User::where('rol',User::ROL_COBRANZAS)
                ->where('users.estado','1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $pedidos = $pedidos->whereIn('pea.user_id_asesor', $asesor_cobranza)->whereIn('pea.tipo',["C","Q"]);
            $pedidos = $pedidos->whereIn('estado_aprueba_encargado',[0]);
        }  else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $usersasesores = User::where('users.rol', User::ROL_ASESOR_ADMINISTRATIVO)
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        }else{
            $pedidos=$pedidos->whereIn('estado_aprueba_asesor',[0,1,2])
                ->whereIn('estado_aprueba_encargado',[0,1,2])
                ->whereIn('estado_aprueba_administrador',[0]);
        }

        $miidentificador = Auth::user()->name;
        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('condicion_envio_color', function ($pedido) {
                return Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
            })
            ->editColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                if ($pedido->codigo_regularizado == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">REGULARIZACION</span>';
                }
                if ($pedido->pendiente_anulacion == '1') {
                    $badge_estado .= '<span class="badge badge-success">' . Pedido::PENDIENTE_ANULACION . '</span>';
                    return $badge_estado;
                }
                if ($pedido->condicion_code == '4' || $pedido->estado == '0') {
                    return '<span class="badge badge-danger">ANULADO</span>';
                }
                if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #cd11af; font-weight: 600; margin: 0px !important;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; ">Observado</span>';
                } elseif ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_CONTESTO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #ff0014; font-weight: 600; margin: 0px !important;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">No Contesto</span>';
                } elseif ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #00b972; font-weight: 600; margin: 0px !important;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">No Recibido</span>';
                }
                if ($pedido->estado_sobre == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin: 0px !important;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">Direccion agregada</span>';
                }
                if ($pedido->estado_ruta == '1') {
                    $badge_estado .= '<span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span>';
                }
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $badge_estado .= '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                return $badge_estado;
            })
            ->addColumn('action', function ($pedido) use ($miidentificador) {
                $btn = [];
                if (Auth::user()->rol == User::ROL_ASESOR) {
                    if ($pedido->estado_aprueba_asesor==0){
                        $btn[] = '<button class="btn btn-primary btn-lg btnApruebaAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-check"></i></button>';
                        $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_encargado  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        $btn[] = '<button class="btn btn-danger btn-sm btnAnularSolicitudByAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-trash"></i></button>';

                    }else if($pedido->estado_aprueba_encargado!=1){
                        $btn[] = '<button class="btn btn-danger btn-sm btnAnularSolicitudByAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-trash"></i></button>';
                    }
                }
                if (Auth::user()->rol == User::ROL_ENCARGADO) {
                    if ($pedido->estado_aprueba_encargado==0 && (in_array($pedido->itipoanulacion,["F","C"])) ) {
                        $btn[] = '<button class="btn btn-warning btn-lg btnApruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Aprobar Anulacion"><i class="fa fa-check-double"></i></button>';
                        $btn[] = '<button class="btn btn-danger btn-lg btnDesapruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Desaprobar Anulacion"><i class="fas fa-ban"></i></button>';
                        if ($pedido->estado_aprueba_administrador==2){
                            $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_admin  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        }
                    }else if($pedido->estado_aprueba_encargado==2 && (in_array($pedido->itipoanulacion,["F","C"])) ){
                        $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_encargado  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        /*$btn[] = '<button class="btn btn-danger btn-sm btnAnularSolicitudByAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-trash"></i></button>';*/
                    }
                }
                if (Auth::user()->rol == User::ROL_JEFE_LLAMADAS) {
                    if ($pedido->estado_aprueba_encargado==0 &&  in_array($pedido->itipoanulacion,["Q","C"]) ){
                        $btn[] = '<button class="btn btn-warning btn-lg btnApruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Aprobar Anulacion"><i class="fa fa-check-double"></i></button>';
                        $btn[] = '<button class="btn btn-danger btn-lg btnDesapruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Desaprobar Anulacion"><i class="fas fa-ban"></i></button>';
                        if ($pedido->estado_aprueba_administrador==2){
                            $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_admin  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        }
                    }else if($pedido->estado_aprueba_encargado==2 && in_array($pedido->itipoanulacion,["Q","C"]) ){
                        $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_encargado  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        /*$btn[] = '<button class="btn btn-danger btn-sm btnAnularSolicitudByAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-trash"></i></button>';*/
                    }
                }
                if (Auth::user()->rol == User::ROL_ADMIN) {
                    $deshabilitar="";

                    if ($pedido->estado_aprueba_asesor==0 || $pedido->estado_aprueba_encargado==2){
                        $btn[] = '<button class="btn btn-primary btn-lg btnApruebaAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-check"></i></button>';
                        $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_encargado  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        $btn[] = '<button class="btn btn-danger btn-sm btnAnularSolicitudByAsesor mr-2" data-idanulacion="' . $pedido->idanulacion . '"><i class="fa fa-trash"></i></button>';
                    }

                    if ($pedido->estado_aprueba_encargado==0 && $pedido->estado_aprueba_asesor==1){
                        $btn[] = '<button class="btn btn-warning btn-lg btnApruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Aprobar Anulacion"><i class="fa fa-check-double"></i></button>';
                        $btn[] = '<button class="btn btn-danger btn-lg btnDesapruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Desaprobar Anulacion"><i class="fas fa-ban"></i></button>';
                        if ($pedido->estado_aprueba_administrador==2){
                            $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_admin  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  '; //nameRegistra
                        }
                        $deshabilitar="disabled";
                    }
                    if ($pedido->estado_aprueba_administrador==0 && $pedido->estado_aprueba_encargado==1){
                        $btn[] = '<a class="btn btn-success btn-lg  mr-2 '.$deshabilitar.'" href="#" data-target="#modal-confirma-anulacion" data-toggle="modal" data-idanulacion="' . $pedido->idanulacion . '" data-pedido-id="' . $pedido->id . '" data-codigo-pedido="' . $pedido->codigo . '"  data-responsable-anula="'.$miidentificador.'" data-registra_solicitud="'.$pedido->nameRegistra.'" >
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                  </a>';
                        $btn[] = '<button class="btn btn-danger btn-lg btnDesapruebaAdministrador mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Desaprobar Anulacion"><i class="fas fa-minus-circle"></i></button>';
                    }
                }
                return join('', $btn);
            })
            ->addColumn('tipoanulacion', function ($pedido) use ($miidentificador) {
                $htmltipoanul = "";

                if ($pedido->tipoanulacion=='C'){
                    $htmltipoanul = $htmltipoanul . '<span class="badge badge-info bg-info">PEDIDO COMPLETO</span>';
                }else  if ($pedido->tipoanulacion=='F'){
                    $htmltipoanul = $htmltipoanul . '<span class="badge badge-warning">FACTURA</span>';
                }else  if ($pedido->tipoanulacion=='Q'){
                    $htmltipoanul = $htmltipoanul . '<span class="badge badge-success">COBRANZA</span>';
                }
                return $htmltipoanul;
                return join('', $htmltipoanul);
            })
            ->addColumn('motivo', function ($pedido) use ($miidentificador) {
                $htmltipoanul = "";
                $deshabilitar="";
                if (in_array(Auth::user()->rol,[User::ROL_ADMIN,User::ROL_ASESOR,User::ROL_ENCARGADO,User::ROL_COBRANZAS,User::ROL_JEFE_LLAMADAS])) {
                    if ($pedido->tipoanulacion=='C'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" data-idanulacion="' . $pedido->idanulacion . '" data-codigos="' . $pedido->codigos . '" data-responsable_create_asesor="' . $pedido->resposable_create_asesor . '" data-responsable_aprob_encarg="' . $pedido->resposable_aprob_encargado . '" data-pedido-motivo="' . $pedido->motivo_solicitud  . '"  data-toggle="modal" title="Ver motivo" ><span class="badge badge-primary bg-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }else  if ($pedido->tipoanulacion=='F' || $pedido->tipoanulacion=='Q'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" data-idanulacion="' . $pedido->idanulacion . '" data-codigos="' . $pedido->codigos . '" data-responsable_create_asesor="' . $pedido->resposable_create_asesor . '" data-responsable_aprob_encarg="' . $pedido->resposable_aprob_encargado . '" data-pedido-motivo="' . $pedido->motivo_solicitud  . '"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }
                    return $htmltipoanul;
                }else{
                    $deshabilitar="btn disabled";
                    if ($pedido->tipoanulacion=='C'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" class="'.$deshabilitar.'"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary bg-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }else  if ($pedido->tipoanulacion=='F' || $pedido->tipoanulacion=='Q'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" class="'.$deshabilitar.'"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }
                    return $htmltipoanul;
                }
                return join('', $htmltipoanul);
            })
            ->rawColumns(['action','action','tipoanulacion','motivo'])
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
