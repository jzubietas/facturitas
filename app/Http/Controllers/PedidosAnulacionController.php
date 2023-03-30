<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Correction;
use App\Models\DetalleContactos;
use App\Models\DetallePedido;
use App\Models\FileUploadAnulacion;
use App\Models\Pedido;
use App\Models\PedidosAnulacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PedidosAnulacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\PedidosAnulacion  $pedidosAnulacion
     * @return \Illuminate\Http\Response
     */
    public function show(PedidosAnulacion $pedidosAnulacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PedidosAnulacion  $pedidosAnulacion
     * @return \Illuminate\Http\Response
     */
    public function edit(PedidosAnulacion $pedidosAnulacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PedidosAnulacion  $pedidosAnulacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PedidosAnulacion $pedidosAnulacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PedidosAnulacion  $pedidosAnulacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(PedidosAnulacion $pedidosAnulacion)
    {
        //
    }

    public function indexanulacionestabla(Request $request)
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
                ]
            )
        ->where('pea.state_solicitud',1);


        if (Auth::user()->rol == User::ROL_ASESOR) {
            $pedidos=$pedidos->whereIn('pea.estado_aprueba_asesor',[0,1])->whereIn('pea.estado_aprueba_encargado',[0,2])->whereIn('pea.estado_aprueba_administrador',[0,2]);
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == User::ROL_ENCARGADO) {
            $pedidos=$pedidos->whereIn('estado_aprueba_encargado',[0]);
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == User::ROL_LLAMADAS) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Au::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == User::ROL_JEFE_LLAMADAS) {
            $pedidos = $pedidos->where('u.identificador', '<>', 'B');
        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
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
                    if ($pedido->estado_aprueba_encargado==0){
                        $btn[] = '<button class="btn btn-warning btn-lg btnApruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Aprobar Anulacion"><i class="fa fa-check-double"></i></button>';
                        $btn[] = '<button class="btn btn-danger btn-lg btnDesapruebaEncargado mr-2" data-idanulacion="' . $pedido->idanulacion . '" title="Desaprobar Anulacion"><i class="fas fa-ban"></i></button>';
                        if ($pedido->estado_aprueba_administrador==2){
                            $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_admin  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        }
                    }else if($pedido->estado_aprueba_encargado==2){
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
                            $btn[] = '<a class="btn btn-info btn-sm" data-target="#modal-ver_rechazo_encargado" data-idanulacion="' . $pedido->idanulacion . '" data-motivo-rechazo="' . $pedido->motivo_sol_admin  . '"  data-toggle="modal" title="Ver Sustento de rechazo"><i class="fa fa-eye"></i></a>  ';
                        }
                        $deshabilitar="disabled";
                    }
                    if ($pedido->estado_aprueba_administrador==0 && $pedido->estado_aprueba_encargado==1){
                        $btn[] = '<a class="btn btn-success btn-lg  mr-2 '.$deshabilitar.'" href="#" data-target="#modal-confirma-anulacion" data-toggle="modal" data-idanulacion="' . $pedido->idanulacion . '" data-pedido-id="' . $pedido->id . '" data-codigo-pedido="' . $pedido->codigo . '"  data-responsable-anula="'.$miidentificador.'" >
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
                if (in_array(Auth::user()->rol,[User::ROL_ADMIN,User::ROL_ASESOR,User::ROL_ENCARGADO])) {
                    if ($pedido->tipoanulacion=='C'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" data-idanulacion="' . $pedido->idanulacion . '" data-codigos="' . $pedido->codigos . '" data-responsable_create_asesor="' . $pedido->resposable_create_asesor . '" data-responsable_aprob_encarg="' . $pedido->resposable_aprob_encargado . '" data-pedido-motivo="' . $pedido->motivo_solicitud  . '"  data-toggle="modal" title="Ver motivo" ><span class="badge badge-primary bg-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }else  if ($pedido->tipoanulacion=='F'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" data-idanulacion="' . $pedido->idanulacion . '" data-codigos="' . $pedido->codigos . '" data-responsable_create_asesor="' . $pedido->resposable_create_asesor . '" data-responsable_aprob_encarg="' . $pedido->resposable_aprob_encargado . '" data-pedido-motivo="' . $pedido->motivo_solicitud  . '"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }
                    return $htmltipoanul;
                }else{
                    $deshabilitar="btn disabled";
                    if ($pedido->tipoanulacion=='C'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" class="'.$deshabilitar.'"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary bg-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }else  if ($pedido->tipoanulacion=='F'){
                        $htmltipoanul = $htmltipoanul . '<a href="" data-target="#modal-ver_motivoanulacion" class="'.$deshabilitar.'"  data-toggle="modal" title="Ver motivo"><span class="badge badge-primary"><i class="fas fa-eye text-lg"></i></span></a>  ';
                    }
                    return $htmltipoanul;
                }
                return join('', $htmltipoanul);
            })
            ->rawColumns(['action','action','tipoanulacion','motivo'])
            ->make(true);
    }

    public function modalsAnulacion(Request $request)
    {
        $listado_codigo_pedido = Pedido::query()
            ->join('detalle_pedidos as dp', 'dp.codigo', 'pedidos.codigo')
            ->join('users as u', 'u.id', 'pedidos.user_id')
            ->where('pedidos.codigo',  trim($request->codigo))
            ->select([
                'pedidos.id',
                'pedidos.codigo',
                'u.name',
                'dp.cantidad as total',
                'dp.ruc',
                'dp.nombre_empresa',
                'dp.adjunto',
                'pedidos.estado',
                'dp.saldo',
                'dp.total as totaldp',
            ]);
        if (Auth::user()->rol == User::ROL_ASESOR){
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $listado_codigo_pedido = $listado_codigo_pedido->where('u.rol', 'Asesor')->WhereIn('u.identificador', $usersasesores);
        }elseif (Auth::user()->rol == User::ROL_ENCARGADO){
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $listado_codigo_pedido = $listado_codigo_pedido->where('u.rol', 'Asesor')->WhereIn('u.identificador', $usersasesores);
        }
        $contadorcodigo=0;
        if ($request->tipo=="F" || $request->tipo=="Q"){
            $listado_codigo_pedidoverifica=$listado_codigo_pedido->clone()->where('pedidos.condicion_envio_code', Pedido::POR_ATENDER_INT);
            $contadorcodigo=$listado_codigo_pedidoverifica->count();
        }
        $totallistado=$listado_codigo_pedido->count();
        $listado_codigo_pedido=$listado_codigo_pedido->first();
        return response()->json(['data'=>$listado_codigo_pedido,'contador'=>$totallistado,'contadorcodigo'=>$contadorcodigo]);
    }
    public function solicitaAnulacionPedido(Request $request)
    {

        $idsfiles="";
        $idsfilesc="";
        $motivoanulacion=$request->txtMotivoPedComplet;
        $pedido_id= $request->txtIdPedidoCompleto;
        /*if($request->tipoAnulacion=="C"){
            $motivoanulacion="Solicitud de anulacion - Pedido Completo";
        }else if($request->tipoAnulacion=="F"){
            $motivoanulacion="Solicitud de anulacion - Factura";
        }*/
        $pedidosAnulValida=PedidosAnulacion::where('pedido_id',$pedido_id)->where('state_solicitud',1);
        $countpedidosanul=$pedidosAnulValida->count();
        if ($countpedidosanul==0){
            $pedidosinpago=Pedido::where('id',$pedido_id)->where('pago',0)->where('pagado',0)->count();
            if ($pedidosinpago==1){
                $files = $request->file('inputArchivoSubir');
                $filesC = $request->file('inputArchivoCapturaSubir');
                $pedidosanulacion = new PedidosAnulacion;
                $pedidosanulacion->pedido_id=$pedido_id;;
                $pedidosanulacion->user_id_asesor=auth()->user()->id;
                $pedidosanulacion->motivo_solicitud=$motivoanulacion;
                $pedidosanulacion->estado_aprueba_asesor=1;
                $pedidosanulacion->tipo=$request->tipoAnulacion;
                $pedidosanulacion->total_anular=$request->anulacionCodigoPc;
                $pedidosanulacion->resposable_create_asesor=$request->txtResponsablePedComplet;
                $pedidosanulacion->save();

                foreach($files as $file){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $file->getClientOriginalName();
                    $fileUpload->filepath = $file->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $file->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfiles=$idsfiles.$fileUpload->id."-";
                }
                foreach($filesC as $fileC){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $fileC->getClientOriginalName();
                    $fileUpload->filepath = $fileC->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $fileC->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfilesc=$idsfilesc.$fileUpload->id."-";
                }
                $pedidosanulacion->files_asesor_ids=$idsfiles;
                $pedidosanulacion->files_responsable_asesor=$idsfilesc;
                $pedidosanulacion->update();
            }
        }


        return  response()->json(['data' => $request->all(),'pedidosanulacion' => ((isset($pedidosanulacion))?$pedidosanulacion:0) ,'IDs Files: '  => $idsfiles,'countpedidosanul'=>((isset($countpedidosanul))?$countpedidosanul:0) ,'pedidosinpago'=> ((isset($pedidosinpago))?$pedidosinpago:0) ]);

    }
    public function solicitaAnulacionPedidof(Request $request)
    {

        $idsfiles="";
        $idsfilesc="";
        $motivoanulacion= $request->txtMotivoFactura;
        $responsableanulacion= $request->txtResponsableFactura;
        $pedido_id= $request->txtIdPedidoFactura;
        /*if($request->tipoAnulacion2=="C"){
            $motivoanulacion="Solicitud de anulacion - Pedido Completo";
        }else if($request->tipoAnulacion2=="F"){
            $motivoanulacion="Solicitud de anulacion - Factura";
        }*/
        $pedidosAnulValida=PedidosAnulacion::where('pedido_id',$pedido_id)->where('state_solicitud',1);
        $countpedidosanul=$pedidosAnulValida->count();
        if ($countpedidosanul==0){
            $pedidosinpago=Pedido::where('id',$pedido_id)->where('pago',0)->where('pagado',0)->count();
            if ($pedidosinpago==1){
                $files = $request->file('inputArchivoSubirf');
                $filesC = $request->file('inputArchivoCapturaSubirf');
                $pedidosanulacion = new PedidosAnulacion;
                $pedidosanulacion->pedido_id=$pedido_id;;
                $pedidosanulacion->user_id_asesor=auth()->user()->id;
                $pedidosanulacion->motivo_solicitud=$motivoanulacion;
                $pedidosanulacion->estado_aprueba_asesor=1;
                $pedidosanulacion->tipo=$request->tipoAnulacion2;
                $pedidosanulacion->total_anular=$request->anularCodigoF;
                $pedidosanulacion->resposable_create_asesor=$request->txtResponsableFactura;
                $pedidosanulacion->save();

                foreach($files as $file){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $file->getClientOriginalName();
                    $fileUpload->filepath = $file->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $file->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfiles=$idsfiles.$fileUpload->id."-";
                }
                foreach($filesC as $fileC){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $fileC->getClientOriginalName();
                    $fileUpload->filepath = $fileC->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $fileC->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfilesc=$idsfilesc.$fileUpload->id."-";
                }

                $pedidosanulacion->files_asesor_ids=$idsfiles;
                $pedidosanulacion->files_responsable_asesor=$idsfilesc;
                $pedidosanulacion->update();
            }

        }

        return  response()->json(['data' => $request->all(),'pedidosanulacion' => ((isset($pedidosanulacion))?$pedidosanulacion:0) ,'IDs Files: '  => $idsfiles,'countpedidosanul'=>((isset($countpedidosanul))?$countpedidosanul:0) ,'pedidosinpago'=> ((isset($pedidosinpago))?$pedidosinpago:0)]);
    }

    public function solicitaAnulacionPedidoq(Request $request)
    {

        $idsfiles="";
        $idsfilesc="";
        $motivoanulacion= $request->txtMotivoCobranza;
        $responsableanulacion= $request->txtResponsableCobranza;
        $pedido_id= $request->txtIdPedidoCobranza;

        $pedidosAnulValida=PedidosAnulacion::where('pedido_id',$pedido_id)->where('state_solicitud',1);
        $countpedidosanul=$pedidosAnulValida->count();
        if ($countpedidosanul==0){
            /*$pedidosinpago=Pedido::where('id',$pedido_id)->where('pago',0)->where('pagado',0)->count();
            if ($pedidosinpago==1){*/
                $files = $request->file('filesAddCobranza');
                $filesC = $request->file('filesAddCapturaCobranza');
                $pedidosanulacion = new PedidosAnulacion;
                $pedidosanulacion->pedido_id=$pedido_id;;
                $pedidosanulacion->user_id_asesor=auth()->user()->id;
                $pedidosanulacion->motivo_solicitud=$motivoanulacion;
                $pedidosanulacion->estado_aprueba_asesor=1;
                $pedidosanulacion->tipo=$request->tipoCobranza2;
                $pedidosanulacion->total_anular=$request->txtImporteAnularCob;
                $pedidosanulacion->resposable_create_asesor=$request->txtResponsableCobranza;
                $pedidosanulacion->save();

                foreach($files as $file){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $file->getClientOriginalName();
                    $fileUpload->filepath = $file->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $file->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfiles=$idsfiles.$fileUpload->id."-";
                }
                foreach($filesC as $fileC){
                    $fileUpload = new FileUploadAnulacion;
                    $fileUpload->pedido_anulacion_id=$pedidosanulacion->id;
                    $fileUpload->filename = $fileC->getClientOriginalName();
                    $fileUpload->filepath = $fileC->store('pedidos/anulaciones', 'pstorage');
                    $fileUpload->type= $fileC->getClientOriginalExtension();
                    $fileUpload->save();
                    $idsfilesc=$idsfilesc.$fileUpload->id."-";
                }

                $pedidosanulacion->files_asesor_ids=$idsfiles;
                $pedidosanulacion->files_responsable_asesor=$idsfilesc;
                $pedidosanulacion->update();
            /*}*/
        }

        return  response()->json(['data' => $request->all(),'pedidosanulacion' => ((isset($pedidosanulacion))?$pedidosanulacion:0) ,'IDs Files: '  => $idsfiles,'countpedidosanul'=>((isset($countpedidosanul))?$countpedidosanul:0) ,'pedidosinpago'=> 0]);
    }

    public function anulacionAprobacionAsesor(Request $request)
    {
        $pedidosanulacion=PedidosAnulacion::where('id',$request->pedidoAnulacionId)->first();
        $pedidosanulacion->update([
            'estado_aprueba_asesor' => $request->estado,
            'estado_aprueba_encargado' => 0,
        ]);
        return  response()->json(['data' => $request->all(),'pedidosanulacion' => $pedidosanulacion,'success'=>"Aprobado" ]);
    }

    public function anulacionAprobacionEncargado(Request $request)
    {
        $pedidosanulacion=PedidosAnulacion::where('id',$request->pedidoAnulacionId)->first();
        if ($request->estado==1){
            $pedidosanulacion->update([
                'user_id_encargado'=>  Auth::user()->id,
                'estado_aprueba_encargado' => $request->estado,
                'estado_aprueba_administrador' => 0,
                'resposable_aprob_encargado' => $request->responsableanulacion_enc,
            ]);
        }elseif ($request->estado==2){
            $pedidosanulacion->update([
                'user_id_encargado'=>  Auth::user()->id,
                'motivo_sol_encargado' => $request->sustento,
                'estado_aprueba_asesor' => 0,
                'estado_aprueba_encargado' => $request->estado,
            ]);
        }

        return  response()->json(['data' => $request->all(),'pedidosanulacion' => $pedidosanulacion,'success'=>"Aprobado" ]);
    }
    public function anulacionAprobacionAdmin(Request $request)
    {
        $pedidosanulacion=PedidosAnulacion::where('id',$request->pedidoAnulacionId)->first();
        if ($request->estado==1){
            $pedidosanulacion->update([
                'user_id_administrador'=>  Auth::user()->id,
                'estado_aprueba_administrador' => $request->estado,
                'estado_aprueba_jefeop' => 0,
            ]);
        }elseif ($request->estado==2){
            $pedidosanulacion->update([
                'user_id_administrador'=>  Auth::user()->id,
                'motivo_sol_admin' => $request->sustento,
                'estado_aprueba_asesor' => 2,
                'estado_aprueba_encargado' => 0,
                'estado_aprueba_administrador' => $request->estado,
            ]);
        }

        return  response()->json(['data' => $request->all(),'pedidosanulacion' => $pedidosanulacion,'success'=>"Aprobado" ]);
    }
    public function anulacionAprobacionJefeOp(Request $request)
    {
        $pedidosanulacion=PedidosAnulacion::where('id',$request->pedidoAnulacionId)->first();
        $pedidosanulacion->update([
            'estado_aprueba_jefeop' => $request->estado,
        ]);
        return  response()->json(['data' => $request->all(),'pedidosanulacion' => $pedidosanulacion,'success'=>"Aprobado" ]);
    }

    public function confirmaSolicitudAnulacion(Request $request)
    {
        /*return $request->all();*/
        $idsfiles="";
        $culpable="";
        $files = $request->file('inputFilesAdmin');
        if ($request->cbxCulpables=="-1"){
            $culpable=$request->txtOtrosCulpables;
        }else{
            $culpable=$request->cbxCulpables;
        }
        foreach($files as $file){
            $fileUpload = new FileUploadAnulacion;
            $fileUpload->pedido_anulacion_id=$request->txtPedidoAnulacionId;
            $fileUpload->filename = $file->getClientOriginalName();
            $fileUpload->filepath = $file->store('pedidos/anulaciones', 'pstorage');
            $fileUpload->type= $file->getClientOriginalExtension();
            $fileUpload->save();
            $idsfiles=$idsfiles.$fileUpload->id."-";
        }

        $pedidosanulacion=PedidosAnulacion::where('id',$request->txtPedidoAnulacionId);
        $pedidos=Pedido::where('id',$request->txtPedidoId);
        $contpedanulacions=$pedidosanulacion->count();
        $contpedidos=$pedidos->count();
        if ($contpedanulacions==1){
            $pedidosanulacion=$pedidosanulacion->first();
            $pedidosanulacion->update([
                'user_id_administrador' => Auth::user()->id,
                'motivo_sol_admin' => $request->motivo,
                'filesadmin_ids' => $idsfiles,
                'estado_aprueba_administrador' => 1,
                'resposable_aprob_admin' => $culpable,
            ]);
        }
        if ($pedidosanulacion->tipo=='C'){
            if ($contpedidos==1){
                $pedidos=$pedidos->clone()->first();
                if ($pedidos->condicion_code == Pedido::POR_ATENDER_INT) {
                    $pedidos->update([
                        'motivo' => $request->motivo,
                        'responsable' => $request->responsable,
                        'condicion' => 'ANULADO',
                        'condicion_code' => Pedido::ANULADO_INT,
                        'modificador' => 'USER' . Auth::user()->id,
                        'user_anulacion_id' => Auth::user()->id,
                        'fecha_anulacion' => now(),
                        'fecha_anulacion_confirm' => now(),
                        'estado' => '0',
                        'path_adjunto_anular' => null,
                        'path_adjunto_anular_disk' => 'pstorage',
                    ]);
                    $detalle_pedidos=DetallePedido::where('pedido_id',$pedidos->id)->first();
                    $detalle_pedidos->update([
                        'estado' => '0'
                    ]);
                }else{
                    $pedidos->update([
                        'motivo' => $request->motivo,
                        'responsable' => $request->responsable,
                        'pendiente_anulacion' => 1,
                        'path_adjunto_anular' => null,
                        'path_adjunto_anular_disk' => 'pstorage',
                        'modificador' => 'USER' . Auth::user()->id,
                        'fecha_anulacion' => now(),
                    ]);
                }

            }
        }else if ($pedidosanulacion->tipo=='F'){
            $pedidos=$pedidos->clone()->first();
            $pedidos->update([
                'motivo' => $request->motivo,
                'condicion' => Pedido::PENDIENTE_ANULACION_PARCIAL,
            ]);
        }else if ($pedidosanulacion->tipo=='Q'){
            $pedidos=$pedidos->clone()->first();
            $pedidodetail= DetallePedido::where('pedido_id',$pedidos->id);

            $pedidos->update([
                'motivo' => $request->motivo,
                'pagado' => 2,
                'condicion' => Pedido::ANULACION_COBRANZA,
            ]);
            $pedidodetail->update([
                'saldo' => 0.00,
            ]);
        }
        return  response()->json(['data' => $request->all(),'pedidosanulacion' => ((isset($pedidosanulacion))?$pedidosanulacion:0) ,'IDsFiles: ' => $idsfiles,'pedidos: '  => $pedidos,'contpedanulacions'=>$contpedanulacions,'$contpedidos'=>$contpedidos]);
    }

    public function anulacionSolicitud(Request $request)
    {
        $pedidosanulacion=PedidosAnulacion::where('id',$request->pedidoAnulacionId)->first();
        $pedidosanulacion->update([
            'state_solicitud' => 0,
        ]);
        return  response()->json(['data' => $request->all(),'pedidosanulacion' => $pedidosanulacion,'success'=>"Anulado" ]);
    }

    public function verAdjuntosAddAsesorAnulacion(Request $request)
    {
        $pedidosanulaadjuntos = PedidosAnulacion::where('id', $request->idAnulacionAdjuntos)->first();
        $adjuntosid = explode("-", $pedidosanulaadjuntos->files_asesor_ids);
        $adjuntosid2=array();
        for ($i = 0; $i < count($adjuntosid); $i++) {
            if ($adjuntosid[$i]!=""){
                array_push($adjuntosid2, $adjuntosid[$i]);
            }
        }
        if (count($adjuntosid2)>0){
            $imagenes = FileUploadAnulacion::whereIn('id', $adjuntosid2)->get();
        }
        return view('operaciones.modal.ContenidoModal.ListadoAdjuntosAnula', compact('imagenes'));
        /*return  response()->json(['data' => $request->all(),'pedidosanulacion' => ((isset($pedidosanulaadjuntos))?$pedidosanulaadjuntos:0) ,'adjuntosid: ' => $adjuntosid,'adjuntosid2: ' => $adjuntosid2,'imagenes: ' => ((isset($imagenes))?$imagenes:0)]);*/
    }

    public function verFilesSolicitudAsesorAnulacion(PedidosAnulacion $pedidosanulacion)
    {
        $pedidosanulaadjuntos = PedidosAnulacion::where('id', $pedidosanulacion->id)->first();
        $adjuntosid = explode("-", $pedidosanulaadjuntos->files_responsable_asesor);
        $adjuntosid2=array();
        for ($i = 0; $i < count($adjuntosid); $i++) {
            if ($adjuntosid[$i]!=""){
                array_push($adjuntosid2, $adjuntosid[$i]);
            }
        }
        if (count($adjuntosid2)>0){
            $imagenes = FileUploadAnulacion::whereIn('id', $adjuntosid2)->get();
        }
        return view('pedidos.anulaciones.modal.ContenidoModal.ListadoAdjuntosSolicitud', compact('imagenes'));
        //return response()->json(compact('pedido', 'pedidos', 'imagenespedido', 'imagenes'));
    }

    public function getcbxculpables(Request $request)
    {
        $html = '<option value="-1">' . trans('---- OTRO CULPABLE ----') . '</option>';
        $registros = PedidosAnulacion::where('id',$request->idpedidoanulacion)->pluck('resposable_create_asesor', 'resposable_aprob_encargado');

        foreach ($registros as $resposable_create_asesor => $resposable_aprob_encargado) {
            if (isset($resposable_create_asesor)){
                $html .= '<option value="' . $resposable_create_asesor. '">' . $resposable_create_asesor. '</option>';
            }
            if (isset($resposable_aprob_encargado)){
                $html .= '<option value="' . $resposable_aprob_encargado. '">' . $resposable_aprob_encargado. '</option>';
            }
        }
        return response()->json(['datoscombo' => $html]);
    }
}
