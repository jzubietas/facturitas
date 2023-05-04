<?php

namespace App\Http\Controllers;

use App\Events\PedidoAnulledEvent;
use App\Events\PedidoAtendidoEvent;
use App\Events\PedidoEntregadoEvent;
use App\Events\PedidoEvent;
use App\Jobs\PostUpdateSituacion;
use App\Models\AttachCorrection;
use App\Models\Cliente;
use App\Models\Correction;
use App\Models\Departamento;
use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\DireccionPedido;
use App\Models\Directions;
use App\Models\Distrito;
use App\Models\GastoEnvio;
use App\Models\GastoPedido;
use App\Models\GrupoPedido;
use App\Models\ImagenAtencion;
use App\Models\ImagenPedido;
use App\Models\Pago;
use App\Models\PagoPedido;
use App\Models\PedidoHistory;
use App\Models\PedidosAnulacion;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Porcentaje;
use App\Models\Provincia;
use App\Models\Ruc;
use App\Notifications\PedidoNotification;
use Carbon\Carbon;
use Exception;
use Facade\FlareClient\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use PDF;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Storage;
use Yajra\DataTables\DataTables;

class PedidoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $mirol = Auth::user()->rol;
        $miidentificador = Auth::user()->name;

        $superasesor = User::where('rol', 'Super asesor')->count();

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            //->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
            ->select([
                'distrito',
                DB::raw("concat(distrito,' - ',zona) as distritonam"),
                'zona'
            ])->orderBy('distrito')->get();

        return view('pedidos.index', compact('dateMin', 'dateMax', 'superasesor', 'mirol', 'miidentificador', 'distritos'));
    }

    public function indexperdonarcurrier()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $mirol = Auth::user()->rol;
        $miidentificador = Auth::user()->name;

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.perdonarCurrier', compact('dateMin', 'dateMax', 'superasesor', 'mirol', 'miidentificador'));
    }

    public function indextablahistorial(Request $request)
    {
        //return $request->buscarpedidocliente;
        if (!$request->buscarpedidocliente && !$request->buscarpedidoruc) {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->join('imagen_pedidos as ip', 'pedidos.id', 'ip.pedido_id')
                ->select(
                    'pedidos.id',
                    'dp.descripcion',
                    'dp.nota',
                    'ip.adjunto'
                )
                ->where('dp.estado', '3')
                ->where('pedidos.estado', '1')
                //->where('pedidos.cliente_id',$request->buscarpedidocliente)
                //->where('dp.ruc',$request->buscarpedidoruc)
                ->orderBy('pedidos.created_at', 'DESC');
            //->get();
            return Datatables::of(DB::table($pedidos))
                ->addIndexColumn()
                ->make(true);
        } else {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->join('imagen_pedidos as ip', 'pedidos.id', 'ip.pedido_id')
                ->select(
                    'pedidos.id',
                    'dp.descripcion',
                    'dp.nota',
                    'ip.adjunto'
                )
                ->where('dp.estado', '1')
                ->where('pedidos.estado', '1')
                ->where('pedidos.cliente_id', $request->buscarpedidocliente)
                ->where('dp.ruc', $request->buscarpedidoruc)
                ->orderBy('pedidos.created_at', 'DESC');
            //->get();

            return Datatables::of(DB::table($pedidos))
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function indextabla(Request $request)
    {
        DB::setDefaultConnection('bandejas');

        $mirol = Auth::user()->rol;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->leftJoin('users as ub','pedidos.user_reg','ub.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftJoin('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->select(
                [
                    'pedidos.*',
                    'pedidos.codigo as codigos',
                    'pedidos.condicion as condiciones',
                    'pedidos.pagado as condicion_pa',
                    'c.nombre as nombres',
                    'c.situacion as s_cliente',
                    'c.icelular as icelulares',
                    'c.celular as celulares',
                    'u.identificador as users',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'dp.cantidad as cantidad',
                    'dp.ruc as ruc',
                    'ub.name as  subio_pedido',
                    /*DB::raw("
                    concat(
                        (case when pedidos.pago=1 and pedidos.pagado=1 then 'ADELANTO' when pedidos.pago=1 and pedidos.pagado=2 then 'PAGO' else '' end),
                        ' ',
                        (
                            select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1
                        )
                    )  as condiciones_aprobado"),*/
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                    DB::raw('DATE_FORMAT(ifnull(pedidos.fecha_anulacion_confirm,pedidos.updated_at), "%Y-%m-%d %H:%i:%s") as fecha_up'),
                    'dp.saldo as diferencia',
                    'direccion_grupos.motorizado_status',
                    'direccion_grupos.observacion as dg_observacion',
                    /*DB::raw("(select  pea.tipo from pedidos_anulacions as pea where pea.pedido_id= pedidos.id and pea.estado_aprueba_asesor=1 and
                    pea.estado_aprueba_encargado =1 and pea.estado_aprueba_administrador=1 and estado_aprueba_jefeop=0  and pea.tipo='F' and pea.state_solicitud=1 limit 1) as vtipoAnulacion"),*/
                    DB::raw("(select NULLIF(pea.state_solicitud,-1) from pedidos_anulacions as pea where pea.pedido_id= pedidos.id order by pea.created_at desc limit 1) as vStateSolicitud"),
                ]
            );


        if (Auth::user()->rol == User::ROL_LLAMADAS) {

        } else if (Auth::user()->rol == "Jefe de llamadas") {
            $pedidos = $pedidos->where('u.identificador', '<>', 'B');
        } else if (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.clave_pedidos', Auth::user()->clave_pedidos)
                ->select(
                    DB::raw("users.clave_pedidos as clave_pedidos")
                )
                ->pluck('users.clave_pedidos');

            $pedidos = $pedidos->WhereIn('u.clave_pedidos', $usersasesores);

        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $usersasesores = User::where('users.rol', User::ROL_ASESOR_ADMINISTRATIVO)
                ->where('users.estado', '1')
                ->where('users.clave_pedidos', Auth::user()->clave_pedidos)
                ->select(
                    DB::raw("users.clave_pedidos as clave_pedidos")
                )
                ->pluck('users.clave_pedidos');

            $pedidos = $pedidos->WhereIn('u.clave_pedidos', $usersasesores);

        } else if (Auth::user()->rol == User::ROL_ENCARGADO) {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.clave_pedidos as clave_pedidos")
                )
                ->pluck('users.clave_pedidos');
            $pedidos = $pedidos->WhereIn('u.clave_pedidos', $usersasesores);
        }else if (Auth::user()->rol == User::ROL_ASISTENTE_PUBLICIDAD) {
            $usersasesores = User::where('users.rol', User::ROL_ASESOR)
                ->where('users.estado', '1')
                ->whereIn('users.clave_pedidos', ['15','16','17','18','19','20'])
                ->select(
                    DB::raw("users.clave_pedidos as clave_pedidos")
                )
                ->pluck('users.clave_pedidos');
            $pedidos = $pedidos->WhereIn('u.clave_pedidos', $usersasesores);
        }

        $miidentificador = Auth::user()->name;
        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('condicion_envio_color', function ($pedido) {
                return Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
            })
            ->addColumn('tipo_letra', function ($pedido) {
                return Pedido::getTipoLetraByCliente($pedido->cliente_id);
            })
            ->editColumn('fecha_up',function($pedido){
                if ($pedido->condicion_code == '4' || $pedido->estado == '0') {
                    return $pedido->fecha_up;

                }else{
                    return '--';
                }
            })
            ->editColumn('codigos',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->codigos.'</span>';
                }else{
                    return $pedido->codigos;
                }
            })
            ->editColumn('empresas',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->empresas.'</span>';
                }else{
                    return $pedido->empresas;
                }
            })
            ->editColumn('cantidad',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.number_format($pedido->cantidad,2,'.','').'</span>';
                }else{
                    return $pedido->cantidad;
                }
            })
            ->editColumn('users',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->users.'</span>';
                }else{
                    return $pedido->users;
                }
            })
            ->editColumn('ruc',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->ruc.'</span>';
                }else{
                    return $pedido->ruc;
                }
            })
            ->editColumn('fecha',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->fecha.'</span>';
                }else{
                    return $pedido->fecha;
                }
            })
            ->editColumn('fecha_up',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.$pedido->fecha_up.'</span>';
                }else{
                    return $pedido->fecha_up;
                }
            })
            ->editColumn('total',function($pedido){
                if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                {
                    return '<span class="color-recuperado-abandono">'.number_format($pedido->total,2,'.','').'</span>';
                }else{
                    return $pedido->total;
                }
            })
            ->editColumn('condicion_pa',function($pedido){
                if ($pedido->condiciones == 'ANULADO' || $pedido->condicion_code == 4 || $pedido->estado == 0)
                {
                    if ($pedido->estado == '0' && $pedido->condicion_code != '5'){
                        return 'ANULADO';
                    }else if($pedido->condicion_code == '5'){
                        return 'ANULADO PARCIAL';
                    }
                } else {
                    if ($pedido->condicion_pa == null) {
                        return 'SIN PAGO REGISTRADO';
                    } else {
                        if ($pedido->condicion_pa == '0') {
                            return '<p>SIN PAGO REGISTRADO</p>';
                                    }
                        if ($pedido->condicion_pa == '1') {
                            return '<p>ADELANTO</p>';
                                    }
                        if ($pedido->condicion_pa == '2') {
                            return '<p>PAGO</p>';
                                    }
                        if ($pedido->condicion_pa == '3') {
                            return '<p>ABONADO</p>';
                                    }
                        //return data;
                    }
                }
            })
            ->editColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                if ($pedido->codigo_regularizado == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">REGULARIZACION</span>';
                }
                if ($pedido->pendiente_anulacion == '1') {
                    $badge_estado .= '<span class="badge badge-danger text-white">' . Pedido::PENDIENTE_ANULACION. '</span>';
                    //return $badge_estado;
                }

                if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #cd11af; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; ">Observado</span>';
                } elseif ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_CONTESTO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #ff0014; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">No Contesto</span>';
                } elseif ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO) {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #00b972; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">No Recibido</span>';
                }
                if ($pedido->estado_sobre == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">Direccion agregada</span>';
                }
                if ($pedido->condiciones==Pedido::PENDIENTE_ANULACION_PARCIAL ) {
                    $badge_estado .= '<span class="badge badge-danger p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: 4px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">'.Pedido::PENDIENTE_ANULACION_PARCIAL.'</span>';
                }

                if ( $pedido->condiciones==Pedido::ANULADO_PARCIAL) {
                    $badge_estado .= '<span class="badge badge-danger p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: 4px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">'.Pedido::ANULADO_PARCIAL.'</span>';
                }

                if ($pedido->condiciones==Pedido::PENDIENTE_ANULACION_COBRANZA ) {
                    $badge_estado .= '<span class="badge badge-danger p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: 4px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">'.Pedido::PENDIENTE_ANULACION_COBRANZA.'</span>';
                }

                if ( $pedido->condiciones==Pedido::ANULACION_COBRANZA) {
                    $badge_estado .= '<span class="badge bg-indigo p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: 4px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">'.Pedido::ANULACION_COBRANZA.'</span>';
                }
                if ($pedido->vStateSolicitud=='0' && $pedido->vStateSolicitud!='') {
                    $badge_estado .= '<span class="badge badge-danger p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: 4px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">ANULACION RECHAZADA</span>';
                }

                if ($pedido->estado_ruta == '1') {
                    $badge_estado .= '<span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 8px !important;
                    margin: 0px !important;
                    font-size: 8px;
                    color: black !important;">Con ruta</span>';
                }



                if ($pedido->condicion_code == '4' || $pedido->estado == '0') {
                    $badge_estado='<span class="badge badge-danger es-anulado">ANULADO</span>';
                    return $badge_estado;
                }else{
                    $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                    $badge_estado .= '<span class="rounded etiquetas_asignacion" style="margin: 0px !important; background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                    return $badge_estado;
                }


            })
            ->editColumn('diferencia',function($pedido){
                if ($pedido->condicion_code == 4 || $pedido->estado == 0) {
                    return '0';
                }
                if ($pedido->diferencia == null) {
                    return 'NO REGISTRA PAGO';
                } else {
                    if ($pedido->diferencia > 0) {
                        return $pedido->diferencia;
                    } else {
                        return $pedido->diferencia;
                    }
                }
            })
            ->editColumn('celulares',function($pedido){
                if ($pedido->icelulares != null) {
                    if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                    {
                        return '<span class="color-recuperado-abandono">'.$pedido->celulares . '-' . $pedido->icelulares . ' - ' . $pedido->nombres.' : <span class="badge">'.$pedido->s_cliente.'</span></span>';
                    }else{
                        return $pedido->celulares . '-' . $pedido->icelulares . ' - ' . $pedido->nombres.' : <span class="badge">'.$pedido->s_cliente.'</span>';
                    }
                } else {
                    if($pedido->s_cliente==Cliente::RECUPERADO_ABANDONO)
                    {
                        return '<span class="color-recuperado-abandono">'.$pedido->celulares . ' - ' . $pedido->nombres.' : <span class="badge">'.$pedido->s_cliente.'</span></span>';
                    }else{
                        return $pedido->celulares . ' - ' . $pedido->nombres.' : <span class="badge">'.$pedido->s_cliente.'</span>';
                    }
                }
            })
            ->addColumn('action', function ($pedido) use ($miidentificador) {
                $btn = [];

                $btn[] = '<div><ul class="m-0 p-1 dis-grid" aria-labelledby="dropdownMenuButton">';

                if ($pedido->condicion_envio_code == Pedido::ENTREGADO_CLIENTE_INT) {
                    $grupo = DireccionGrupo::query()->find($pedido->direccion_grupo);
                    if ($grupo != null) {
                        $fotos = [
                            'foto1' => foto_url($grupo->foto1),
                            'foto2' => foto_url($grupo->foto2),
                            'foto3' => foto_url($grupo->foto3),
                        ];
                        if (collect($fotos)->values()->filter()->count() > 0) {
                            $btn[] = '<button data-verforotos=\'' . json_encode($fotos) . '\' class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize"><i class="fa fa-camera" aria-hidden="true"></i>
 Ver Fotos</button>';
                        } else {
                            $btn[] = '<button disabled class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize"><i class="fa fa-camera text-dark text-wrap text-center btn-fontsize"></i> Sin Fotos</button>';
                        }
                    } else {
                        $btn[] = '<button disabled class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize"><i class="fa fa-camera text-dark text-wrap" aria-hidden="true"></i> Sin Fotos</button>';
                    }
                }
                if (can('pedidos.pedidosPDF')) {
                    $btn[] = '<a href="' . route("pedidosPDF", $pedido->id) . '" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" target="_blank"><i class="fa fa-file-pdf text-primary"></i> Ver PDF</a>';
                }

                if (can('pedidos.show')) {
                    $btn[] = '<a href="' . route("pedidos.show", $pedido->id) . '" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize"><i class="fas fa-eye text-success"></i> Ver pedido</a>';

                    if($pedido->env_rotulo!='' && $pedido->env_zona=='OLVA')
                    {
                        $btn[] = collect(explode(',', $pedido->dg_observacion))->trim()->map(fn($f) => '<a target="_blank" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rotulo</a>')->join('<br>');
                    }
                }
                if (can('pedidos.edit')) {
                    if ($pedido->condicion_pa == 0) {
                        $btn[] = '<a href="' . route("pedidos.edit", $pedido->id) . '" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize"><i class="fas fa-edit text-warning" aria-hidden="true"></i> Editar</a>';
                    }
                }

                $btn [] = '<details style="max-width: 100px !important">';
                $btn [] = '<summary class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize font-weight-bold"> <i class="fa fa-sort" aria-hidden="true"></i> Otros  </summary>';
                /*SUMARY*/
                if (can('pedidos.destroy')) {
                    if ($pedido->estado == 0) {
                        $btn[] = '<a href="#" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" data-target="#modal-restaurar" data-toggle="modal" data-restaurar="' . $pedido->id . '" data-codigo=' . $pedido->codigo . '><i class="fas fa-check text-secondary"></i> Restaurar</a>';
                    } else {

                        if ($pedido->condicion_envio_code != Pedido::ENTREGADO_CLIENTE_INT) {
                            if (!$pedido->pendiente_anulacion) {
                                if (\Str::contains(\Str::lower($pedido->codigo), '-c')) {
                                    $btn[] = '<a href="" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" data-target="#modal-delete" data-toggle="modal" data-delete="' . $pedido->id . '" data-codigo=' . $pedido->codigo . ' data-responsable="' . $miidentificador . '"><i class="fas fa-trash-alt text-danger"></i> Anular</a>';
                                } else if ($pedido->condicion_pa == 0 || $pedido->estado_correccion == 1) {
                                    $btn[] = '<a href="" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" data-target="#modal-delete" data-toggle="modal" data-delete="' . $pedido->id . '" data-codigo=' . $pedido->codigo . ' data-responsable="' . $miidentificador . '"><i class="fas fa-trash-alt text-danger"></i> Anular</a>';
                                }
                            }
                        } else {
                            if (in_array(auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_LLAMADAS])) {
                                if ($pedido->condicion_pa == 0 || $pedido->estado_correccion == 1) {
                                    $btn[] = '<a href="" class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" data-target="#modal-delete" data-toggle="modal" data-delete="' . $pedido->id . '" data-codigo=' . $pedido->codigo . ' data-responsable="' . $miidentificador . '"><i class="fas fa-trash-alt text-danger"></i> Anular</a>';
                                }
                            }
                        }
                    }

                }

                if (\auth()->user()->can('envios.direccionenvio.editar')) {
                    if ($pedido->estado_sobre == 1) {
                        $btn[] = '<button class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" data-jqconfirm="' . $pedido->id . '"><i class="fa fa-map-marker-alt text-info mr-8"></i>Editar direccion de envio</button>';
                    }
                }

                if ($pedido->da_confirmar_descarga) {
                    if ($pedido->estado == 1) {
                        $btn[] = '<button data-jqconfirmdetalle="jqConfirm" data-target="' . route("pedidos.estados.detalle-atencion", $pedido->id) . '"
                                    data-idc="' . $pedido->id . '"
                                    data-codigo="' . $pedido->codigos . '"

                                    class="btn btn-light btn-sm text-left p-2 text-center btn-fontsize" ' . (($pedido->da_confirmar_descarga == 0 && !empty($pedido->sustento_adjunto)) ? 'style="border: 3px solid #dc3545!important;"' : '') . '
                                    ' . (($pedido->da_confirmar_descarga == 0 && !empty($pedido->sustento_adjunto)) ? ' data-toggle="tooltip" data-placement="top" title="Los archivos de este pedido fueron editados"' : '') . '
                                     >
                                    <i class="fa fa-file"></i> Adjuntos
                                </button>';
                    } else {

                    }

                }

                if (!in_array($pedido->condicion_envio_code, [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT])) {
                    if ($pedido->estado_correccion == "0") {
                        if (\Str::contains(\Str::lower($pedido->codigo), '-c')) {
                        } else {
                            $btn[] = '<a href="#" data-backdrop="static" data-keyboard="false" class=" btn btn-light btn-sm text-left p-2 text-center btn-fontsize"
                            data-target="#modal-correccion-pedidos"
                            data-correccion=' . $pedido->id . ' data-codigo=' . $pedido->codigos . ' data-toggle="modal" >
                                <i class="fa fa-check-circle text-warning"></i>
                                Correccion</a>';
                        }
                    }

                }


                if ($pedido->condicion_envio_code == Pedido::ENTREGADO_CLIENTE_INT) {

                    $codigo_p = trim($pedido->codigos);

                    $btn[] = '<a href="#" data-backdrop="static" data-keyboard="false" class="btn-sm dropdown-item text-center btn-fontsize"
                                data-target="#modal-recojo-pedidos"
                                data-pedidoid="' . $pedido->id . '" data-pedidocodigo="' . $codigo_p . '" data-toggle="modal"
                                data-clienteid="' . $pedido->cliente_id . '" data-clientenombre="' . $pedido->nombres . '"
                                data-nombreResiv="' . $pedido->env_nombre_cliente_recibe . '" data-telefonoResiv="' . $pedido->env_celular_cliente_recibe . '"
                                data-direccionReco="' . $pedido->env_direccion . '" data-referenciaReco="' . $pedido->env_referencia . '"
                                data-observacionReco="' . $pedido->env_observacion . '" data-gmclink="' . $pedido->env_gmlink . '"
                                >
                                <i class="fa fa-check-circle text-warning"></i>
                                Recojo</a>';
                }

                $btn [] = '</details>';
                /*SUMARY*/


                $btn[] = '</ul></div>';
                return join('', $btn);
            })
            ->rawColumns([
                'codigos'
                ,'celulares'
                ,'empresas'
                ,'cantidad'
                ,'users'
                ,'ruc'
                ,'fecha'
                ,'fecha_up'
                ,'total'
                ,'condicion_pa'
                ,'action'
                ,'condicion_envio'
                ,'condicion_envio_color'
                ,'celulares'
                ,'fecha_up'
            ])
            ->make(true);
    }

    public function indexrecojotabla(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftJoin('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->whereIn('pedidos.condicion_envio_code',
                [
                    Pedido::RECOJO_COURIER_INT,
                    Pedido::REPARTO_RECOJO_COURIER_INT,
                    Pedido::ENVIO_RECOJO_MOTORIZADO_COURIER_INT,
                    Pedido::RECEPCION_RECOJO_MOTORIZADO_INT,
                    Pedido::RECOJO_MOTORIZADO_INT,
                    Pedido::RECIBIDO_RECOJO_CLIENTE_INT,
                    Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT,
                    Pedido::ENTREGADO_RECOJO_COURIER_INT,
                    Pedido::ENTREGADO_RECOJO_JEFE_OPE_INT,
                ])
            ->where('pedidos.estado', 1)
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
                    DB::raw("
                    concat(
                        (case when pedidos.pago=1 and pedidos.pagado=1 then 'ADELANTO' when pedidos.pago=1 and pedidos.pagado=2 then 'PAGO' else '' end),
                        ' ',
                        (
                            select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1
                        )
                    )  as condiciones_aprobado"),

                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                    DB::raw('DATE_FORMAT(pedidos.updated_at, "%d/%m/%Y") as fecha2_up'),
                    DB::raw('DATE_FORMAT(pedidos.updated_at, "%Y-%m-%d %H:%i:%s") as fecha_up'),
                    'dp.saldo as diferencia',
                    'direccion_grupos.motorizado_status'
                ]
            );


        if (Auth::user()->rol == "Llamadas") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == "Jefe de llamadas") {
            $pedidos = $pedidos->where('u.identificador', '<>', 'B');
        } else if (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Super asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $usersasesores = User::where('users.rol', User::ROL_ASESOR_ADMINISTRATIVO)
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == User::ROL_ENCARGADO) {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
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
                $btn[] = '';
                return join('', $btn);
            })
            ->rawColumns(['action', 'condicion_envio', 'condicion_envio_color'])
            ->make(true);
    }

    public function pedidosrecojo()
    {
        return view('pedidos.recojo.index');
    }

    /*    data-nombreResiv="' . $pedido->env_nombre_cliente_recibe . '" data-telefonoResiv="' . $pedido->env_celular_cliente_recibe . '" data-toggle="modal"
            data-clienteid="'. $pedido->cliente_id . '" data-clientenombre="' . $pedido->nombres . '"*/


    public function indexperdonarcurriertabla(Request $request)
    {
        $mirol = Auth::user()->rol;
        $pedidos = null;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'pedidos.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.motivo',
                'pedidos.responsable',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha2'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %H:%i:%s") as fecha'),
                'dp.saldo as diferencia',
                'pedidos.estado',
                'pedidos.pago',
                'pedidos.pagado',
                'pedidos.envio'
            ])
            ->where('pedidos.pagado', '=', '1')
            ->where('pedidos.pago', '=', '1')
            ->where([
                ['dp.saldo', '>=', 11],
                ['dp.saldo', '<=', 13],
            ])
            ->orwhere([
                ['dp.saldo', '>=', 17],
                ['dp.saldo', '<=', 19],
            ])
            ->where('pedidos.estado', '1');


        if (Auth::user()->rol == "Llamadas") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == "Jefe de llamadas") {
            /*
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
            */
        } else if (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Super asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);
        }

        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->editColumn('condicion_pa', function ($pedido) {
                $return = "";
                if ($pedido->condicion_pa === null) {
                    $return = 'SIN PAGO REGISTRADO';
                } else if ($pedido->condicion_pa === 'ANULADO') {
                    $return = 'ANULADO';
                } else {
                    switch ($pedido->condicion_pa) {
                        case '0':
                            $return = '<p>SIN PAGO REGISTRADO</p>';
                            break;
                        case '1':
                            $return = '<p>ADELANTO</p>';
                            break;
                        case '2':
                            $return = '<p>PAGO</p>';
                            break;
                        case '3':
                            $return = '<p>ABONADO</p>';
                            break;
                    }
                }
                return $return;

            })
            ->addColumn('action', function ($pedido) {
                $btn = '';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function deudoresoncreate(Request $request)
    {
        $deudores = Cliente::where('estado', '1')
            //->where('user_id', Auth::user()->id)
            ->where('tipo', '1')
            ->where('deuda', '1');
        //->get();

        return Datatables::of(DB::table($deudores))
            ->addIndexColumn()
            ->make(true);

        //return response()->json($deudores);
    }

    public function clientesenpedidos(Request $request)
    {
        $clientes1 = Cliente::
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->where('clientes.user_id', Auth::user()->id)
            ->groupBy(
                'clientes.id',
                'clientes.nombre',
                'clientes.celular',
                'clientes.estado',
                'u.name',
                'u.identificador',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.pidio',
                'clientes.deuda'
            )
            ->get(['clientes.id',
                'clientes.nombre',
                'clientes.celular',
                'clientes.estado',
                'u.name as user',
                'u.identificador',
                'clientes.provincia',
                'clientes.distrito',
                'clientes.direccion',
                'clientes.pidio',
                'clientes.deuda',
                DB::raw('count(p.created_at) as cantidad'),
                DB::raw('MAX(p.created_at) as fecha'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
            ]);

        return response()->json($clientes1);
    }

    public function clientesenruconcreate(Request $request)
    {
        $clientes_ruc = Cliente::
        where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->where('clientes.user_id', Auth::user()->id)
            ->groupBy(
                'clientes.id',
                'clientes.nombre',
                'clientes.celular',
                'clientes.estado'
            )
            ->get(['clientes.id',
                'clientes.nombre',
                'clientes.celular',
                'clientes.estado'
            ]);

        return response()->json($clientes_ruc);
    }

    public function asesortiempo(Request $request)//clientes
    {
        $mirol = Auth::user()->rol;
        $html = '<option value="">' . trans('---- SELECCIONE ASESOR ----') . '</option>';

        if ($mirol == 'Llamadas') {
            $asesores = Users::where('users.rol', "Asesor")
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->get();
        } else if ($mirol == 'Jefe de llamadas') {
            $asesores = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->get();
        } else if ($mirol == 'Asesor') {
            $asesores = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->get();
        } else {
            $asesores = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->get();
        }

        foreach ($asesores as $asesor) {
            $html .= '<option style="color:#000" value="' . $asesor->id . '">' . $asesor->identificador . '</option>';
        }

        return response()->json(['html' => $html]);
    }

    public function create()
    {
        //setlocale(LC_ALL,"es_ES");
        //\Carbon\Carbon::setLocale('es');
        $fecha = \Carbon\Carbon::now()->locale('es-PE');
        $mes_selected = trim(strtoupper($fecha->translatedFormat('F')));
        $anno_selected = trim($fecha->format("Y"));

        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $mirol = Auth::user()->rol;
        $users = User::where('estado', '1')->where("rol", "Asesor");

        if ($mirol == 'Llamadas') {
            $users = $users->where('llamada', Auth::user()->id);
        } else if ($mirol == 'Jefe de llamadas') {
            $users = $users->where('llamada', Auth::user()->id);
        } else if ($mirol == 'Asesor') {
            $users = $users->where('id', Auth::user()->id);
        }
        //$users=$users->get(['identificador','id'])  ;//->pluck('identificador', 'id');
        $users = $users->pluck('identificador', 'id');

        $meses = [
            "ENERO" => 'ENERO',
            "FEBRERO" => 'FEBRERO',
            "MARZO" => 'MARZO',
            "ABRIL" => 'ABRIL',
            "MAYO" => 'MAYO',
            "JUNIO" => 'JUNIO',
            "JULIO" => 'JULIO',
            "AGOSTO" => 'AGOSTO',
            "SEPTIEMBRE" => 'SEPTIEMBRE',
            "OCTUBRE" => 'OCTUBRE',
            "NOVIEMBRE" => 'NOVIEMBRE',
            "DICIEMBRE" => 'DICIEMBRE',
        ];

        $anios = [
            ($dateY - 1) => ($dateY - 1),
            $dateY => $dateY
        ];

        $anios = [
            "2021" => '2021',
            "2022" => '2022',
            "2023" => '2023',
        ];

        /*$rucs = Ruc::where('user_id', Auth::user()->id)
                    ->where('estado', '1')
                    ->pluck('num_ruc', 'num_ruc');*/

        $fecha = Carbon::now()->format('dm');
        $dia = Carbon::now()->toDateString();

        $numped = Pedido::where(DB::raw('Date(created_at)'), $dia)
            ->where('user_id', Auth::user()->id)
            ->groupBy(DB::raw('Date(created_at)'))
            ->count();
        $numped = $numped + 1;

        $mirol = Auth::user()->rol;

        $distritos_recojo = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
            ->select([
                'distrito',
                DB::raw("concat(distrito,' - ',zona) as distritonam"),
                'zona'
            ])->orderBy('distrito')->get();

        return view('pedidos.create', compact('users', 'dateM', 'dateY', 'meses', 'anios', 'fecha', 'numped', 'mirol', 'mes_selected', 'anno_selected', 'distritos_recojo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function validarrelacionruc(Request $request)
    {
        $ruc_registrar = $request->agregarruc;
        $cliente_registrar = $request->cliente_id_ruc;
        $nombreruc_registrar = $request->pempresaruc;
        $asesor_registrar = $request->user_id;

        $ruc_repetido = Ruc::where('rucs.num_ruc', $ruc_registrar)->count();

        if ($ruc_repetido > 0) {
            //ya existe, actualizar y buscar relacion
            //busco relacion si es correcta
            $ruc = Ruc::where('num_ruc', $request->agregarruc)->first();//ruc ya exisste entoces busco al asesor//buscar si corresponde al cliente
            if ($cliente_registrar == $ruc->cliente_id_ruc) {
                //verificar el asesor
                $asesordelruc = User::where("users.id", $ruc->user_id)->first();
                if ($asesor_registrar == $asesordelruc->id) {
                    $html = "1";
                    return response()->json(['html' => $html]);
                } else {
                    $html = "0|A|" . $asesordelruc->name;
                    return response()->json(['html' => $html]);
                }
                //$html="1";
            } else {
                //$asesordelruc= User::where("users.id",$ruc->user_id)->first();
                $cliente = Cliente::where("clientes.id", $ruc->cliente_id)->first();
                //$html="0|C|RUC YA EXISTE PERO NO CORRESPONDE AL CLIENTE";
                $html = "0|C|" . $cliente->nombre;
                return response()->json(['html' => $html]);
            }
        } else {
            //no existe ,registrare
            $html = "1";
            return response()->json(['html' => $html]);
        }

    }

    public function pedidoobteneradjuntoRequest(Request $request)
    {
        $buscar_pedido = $request->pedido;

        $array_html = [];

        $imagenes = ImagenPedido::where('pedido_id', $buscar_pedido)
            ->where("estado", "1")
            ->whereNotIn("adjunto", ['logo_facturas.png'])
            ->orderBy('created_at', 'DESC')->get();
        foreach ($imagenes as $imagen) {
            $array_html[] = $imagen->adjunto;
        }
        /*
        $imagenesatencion = ImagenAtencion::where('pedido_id', $buscar_pedido)
            ->where("estado", "1")
            ->whereNotIn("adjunto", ['logo_facturas.png'])
            ->orderBy('created_at', 'DESC')->get();
        foreach ($imagenesatencion as $imagenatencion) {
            $array_html[] = $imagenatencion->adjunto;
        } */

        $html = implode("|", $array_html);
        return response()->json(['html' => $html, 'cantidad' => count($array_html)]);
    }

    public function pedidoobteneradjuntoOPRequest(Request $request)
    {
        $buscar_pedido = $request->pedido;

        $array_html = [];
        /*
        $imagenes = ImagenPedido::where('pedido_id', $buscar_pedido)
            ->where("estado", "1")
            ->whereNotIn("adjunto", ['logo_facturas.png'])
            ->orderBy('created_at', 'DESC')->get();
        foreach ($imagenes as $imagen) {
            $array_html[] = $imagen->adjunto;
        } */
        $imagenesatencion = ImagenAtencion::where('pedido_id', $buscar_pedido)
            ->where("estado", "1")
            ->whereNotIn("adjunto", ['logo_facturas.png'])
            ->orderBy('created_at', 'DESC')->get();
        foreach ($imagenesatencion as $imagenatencion) {
            $array_html[] = $imagenatencion->adjunto;
        }
        $html = implode("|", $array_html);
        return response()->json(['html' => $html, 'cantidad' => count($array_html)]);
    }

    public function correccionobteneradjuntoRequest(Request $request)
    {
        $buscar_pedido = $request->correccion;
        $array_html = [];
        $imagenes = AttachCorrection::where('correction_id', $request->correccion)
            ->where("estado", "1")
            ->orderBy('created_at', 'DESC')->get();
        foreach ($imagenes as $imagen) {
            $array_html[] = '<p><a href="' . Storage::disk($imagen->disk)->url($imagen->file_name) . '"><i class="fa fa-file mr-2"></i>' . $imagen->name . '</a><p>';
        }
        $html = implode("|", $array_html);
        return response()->json(['html' => $array_html, 'cantidad' => count($array_html)]);
    }

    public function ruc(Request $request)//rucs
    {
        if (!$request->cliente_id || $request->cliente_id == '') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::join('clientes as c', 'rucs.cliente_id', 'c.id')
                ->select('rucs.num_ruc as num_ruc', 'rucs.empresa')
                ->where('rucs.cliente_id', $request->cliente_id)
                ->get();
            foreach ($rucs as $ruc) {
                $html .= '<option value="' . $ruc->num_ruc . '">' . $ruc->num_ruc . "  " . $ruc->empresa . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function rucnombreempresa(Request $request)//rucs
    {
        if (!$request->ruc || $request->ruc == '') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::where('rucs.num_ruc', $request->ruc)
                ->first();
            $html = htmlentities($rucs->empresa);

        }
        return response()->json(['html' => $html]);
    }

    public function infopdf(Request $request)//rucs
    {
        if (!$request->infocopiar) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $pedido = "";
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $pedido = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'dp.cantidad',
                    'dp.porcentaje',
                    'dp.ft',
                    'dp.courier',
                    'dp.total',
                )
                ->where('pedidos.id', $request->infocopiar)
                ->first();
            //$html=$pedido->id;

        }
        return response()->json($pedido);
    }

    /*
    public function ruc  vantigua(Request $request)//rucs
    {
        if (!$request->cliente_id) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $rucs = Ruc::where('rucs.cliente_id', $request->cliente_id)
                ->get();
            foreach ($rucs as $ruc) {
                $html .= '<option value="' . $ruc->num_ruc . '">' . $ruc->num_ruc . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    } */

    public function cliente()//clientes
    {
        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        $clientes = Cliente::where('clientes.user_id', Auth::user()->id)
            ->where('clientes.tipo', '1')
            ->get();
        foreach ($clientes as $cliente) {
            $html .= '<option value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->nombre . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function clientemodal1(Request $request)
    {
        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        if (!empty($request->user_id)) {
            $clientes = Cliente::join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.tipo', '1')
                ->where('clientes.estado', '1');
            if ($request->rol != User::ROL_ADMIN) {
                $clientes->where('u.identificador', $request->user_id);
            }

            $clientes = $clientes->get([
                'clientes.id',
                'clientes.celular',
                'clientes.icelular',
                'clientes.nombre',
                'clientes.crea_temporal',
                'clientes.activado_tiempo',
                'clientes.activado_pedido',
                'clientes.temporal_update',
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and cast(ped.created_at as date) >='" . now()->startOfMonth()->format('Y-m-d') . "' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and cast(ped2.created_at as date) <='" . now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d') . "'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
            ]);
            foreach ($clientes as $cliente) {
                //if ($cliente->pedidos_mes_deuda > 0 || $cliente->pedidos_mes_deuda_antes > 0) {
                $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular . (($cliente->icelular != null) ? '-' . $cliente->icelular : '') . '  -  ' . $cliente->nombre . '</option>';
                //}
            }
        }
        return response()->json(['html' => $html]);
    }


    public function clientedeudaparaactivar(Request $request)//clientes
    {
        if (!$request->user_id || $request->user_id == '') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.tipo', '1')//->where('clientes.celular','925549426')
            ->where('clientes.user_clavepedido', $request->user_id)
                ->where('clientes.estado', '1')
                ->get([
                    'clientes.id',
                    'clientes.celular',
                    'clientes.icelular',
                    'clientes.nombre',
                    'clientes.crea_temporal',
                    'clientes.activado_tiempo',
                    'clientes.activado_pedido',
                    'clientes.temporal_update',
                    DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and cast(ped.created_at as date) >='" . now()->startOfMonth()->format('Y-m-d') . "' and ped.estado=1) as pedidos_mes_deuda "),
                    DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and cast(ped2.created_at as date) <='" . now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d') . "'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                ]);
            foreach ($clientes as $cliente) {
                /*if ($cliente->pedidos_mes_deuda > 0 || $cliente->pedidos_mes_deuda_antes > 0) {*/
                    $html .= '<option style="color:black" value="' . $cliente->celular . '">' . $cliente->celular . (($cliente->icelular != null) ? '-' . $cliente->icelular : '') . '  -  ' . $cliente->nombre . '</option>';
                /*}*/
            }
        }
        return response()->json(['html' => $html]);
    }

    public function clientedeasesordeuda(Request $request)//clientes
    {
        if (!$request->user_id || $request->user_id == '') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                ->where('clientes.tipo', '1')
                ->where('clientes.deuda', '1')
                ->where('clientes.estado', '1')
                ->get();
            foreach ($clientes as $cliente) {
                $html .= '<option value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '</option>';
            }

        }

        return response()->json(['html' => $html]);
    }

    public function tipobanca(Request $request)//pedidoscliente
    {
        if (!$request->cliente_id || $request->cliente_id == '') {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $porcentajes = Porcentaje::where('porcentajes.cliente_id', $request->cliente_id)->get();
            foreach ($porcentajes as $porcentaje) {
                $html .= '<option value="' . $porcentaje->nombre . '_' . $porcentaje->porcentaje . '">' . $porcentaje->nombre . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function AgregarRuc(Request $request)
    {
        $ruc = Ruc::where('num_ruc', $request->agregarruc)->first();

        if ($ruc !== null) {
            $user = User::where('id', $ruc->user_id)->first();

            $messages = [
                'required' => 'EL RUC INGRESADO ESTA ASIGNADO AL ASESOR ' . $user->identificador,
            ];

            $validator = Validator::make($request->all(), [
                'num_ruc' => 'required|unique:rucs',
            ], $messages);

            /*if ($validator->fails()) {
                return redirect('pedidos/create')
                            ->withErrors($validator)
                            ->withInput();
            }*/
            $ruc->update([
                'empresa' => $request->pempresaruc
            ]);

            $html = "false";
        } else {
            $ruc = Ruc::create([
                'num_ruc' => $request->agregarruc,
                'user_id' => Auth::user()->id,
                'cliente_id' => $request->cliente_id_ruc,
                'empresa' => $request->pempresaruc,
                'porcentaje' => ((!$request->porcentajeruc) ? 0.0 : $request->porcentajeruc),
                'estado' => '1'
            ]);
            $html = "true";
        }


        return response()->json(['html' => $html]);

    }

    public function pedidosstore(Request $request)
    {
        //return $request;
        $identi_asesor = User::where("identificador", $request->user_id)->where("unificado", "NO")->first();
        $mirol = Auth::user()->rol;
        if ($mirol == 'Llamadas') {

            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now();
            $numped = Pedido::join('clientes as c', 'c.id', 'pedidos.cliente_id')
                ->join('users as u', 'c.user_id', 'u.id')
                ->where("c.estado", "1")
                ->where("c.tipo", "1")
                //->where("pedidos.estado_correccion", "0")
                ->whereDate('pedidos.created_at', $dia)
                ->where('u.clave_pedidos', $request->user_id)
                ->count();
            $numped = $numped + 1;

        } else if ($mirol == 'Jefe de llamadas') {
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now();
            $numped = Pedido::join('clientes as c', 'c.id', 'pedidos.cliente_id')
                ->join('users as u', 'c.user_id', 'u.id')
                ->where("c.estado", "1")
                ->where("c.tipo", "1")
                //->where("pedidos.estado_correccion", "0")
                ->whereDate('pedidos.created_at', $dia)
                ->where('u.clave_pedidos', $request->user_id)
                ->count();
            $numped = $numped + 1;
        } else {
            $fecha = Carbon::now()->format('dm');
            $dia = Carbon::now();
            $numped = Pedido::join('clientes as c', 'c.id', 'pedidos.cliente_id')
                ->join('users as u', 'c.user_id', 'u.id')
                ->where("c.estado", "1")
                ->where("c.tipo", "1")
                //->where("pedidos.estado_correccion", "0")
                ->whereDate('pedidos.created_at', $dia)
                ->where('u.clave_pedidos', $request->user_id)
                ->count();
            $numped = $numped + 1;
        }
        $cliente_AB = Cliente::where("id", $request->cliente_id)->first();

        //calculo de letras
        $codigo = null;
        if ($identi_asesor->clave_pedidos == 'B') {
            $codigo = $identi_asesor->clave_pedidos;
        }
        else if ($identi_asesor->clave_pedidos == '20') {
            $codigo = $identi_asesor->clave_pedidos;
        }else if ($identi_asesor->clave_pedidos == '21') {
            $codigo = $identi_asesor->clave_pedidos;
        } else {
            $codigo = intval($identi_asesor->identificador);
        }
        if ($cliente_AB->icelular != null) {
            //if ($identi_asesor->identificador != 'B') {
            $codigo = $codigo . $cliente_AB->icelular;
            //}
        }
        $codigo = $codigo . "-" . $fecha . "-" . $numped;

        //$codigo = (($identi_asesor->identificador == 'B') ? $identi_asesor->identificador : intval($identi_asesor->identificador)) .
        //          (($cliente_AB->icelular != null) ? $cliente_AB->icelular : '') . "-" . $fecha . "-" . $numped;

        $request->validate([
            'cliente_id' => 'required',
        ]);

        $arreglo = array("ASESOR ADMINISTRATIVO", "Administrador",);


        $cliente_deuda = Cliente::findOrFail($request->cliente_id);

        if (!(in_array($mirol, $arreglo))) {
            //calcular con activacion temporal

            //sino darle bloqueado por 3 maximo en el mes
            //sino  alerta deniega registrar

            if ($cliente_deuda->crea_temporal == 1) {
                $now = now();
                $temporal_update = $cliente_deuda->temporal_update;
                if ($temporal_update < $now) {
                    $cliente_deuda->update([
                        'crea_temporal' => '0',
                        'activado_pedido' => '0',
                        'activado_tiempo' => '0',
                    ]);
                    return response()->json([
                        'html' => "|tmp_time",
                    ]);
                }
                $limitepedidos = $cliente_deuda->activado_pedido;
                if ($limitepedidos <= 0) {
                    $cliente_deuda->update([
                        'crea_temporal' => '0',
                        'activado_pedido' => '0',
                        'activado_tiempo' => '0',
                    ]);
                    return response()->json([
                        'html' => "|tmp_count",
                    ]);
                }
            } else {

                $pedidos_mes_deuda = $cliente_deuda->pedidos()->noPagados()->whereDate('pedidos.created_at', '>=', now()->startOfMonth())->activo()->count();
                $pedidos_mes_deuda_antes = $cliente_deuda->pedidos()->noPagados()->where('pedidos.created_at', '<=', now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay())->activo()->count();

                if ($pedidos_mes_deuda > 0 && $pedidos_mes_deuda_antes == 0) {
                    if ($pedidos_mes_deuda > 4) {
                        $html = "|4";
                        return response()->json(['html' => $html]);
                    }
                } else if ($pedidos_mes_deuda > 0 && $pedidos_mes_deuda_antes > 0) {
                    $html = "|0";
                    return response()->json(['html' => $html]);
                } else if ($pedidos_mes_deuda == 0 && $pedidos_mes_deuda_antes > 0) {
                    $html = "|0";
                    return response()->json(['html' => $html]);
                }
            }
        }

        try {

            $identi_asesor = User::where("identificador", $request->user_id)->where("unificado", "NO")->first();

            DB::beginTransaction();


            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'user_reg' => auth()->user()->id,
                'creador' => 'USER0' . Auth::user()->id,//aqui una observacion, en el migrate la columna en tabla pedido tenia nombre creador y resulto ser creador_id
                'condicion' => Pedido::POR_ATENDER,
                'condicion_code' => 1,
                'condicion_int' => '1',
                'pago' => '0',
                'condicion_envio' => Pedido::POR_ATENDER_OPE,
                'condicion_envio_code' => Pedido::POR_ATENDER_INT,
                'condicion_envio_at' => now(),
                'estado' => '1',
                'codigo' => $codigo,
                'notificacion' => 'Nuevo pedido creado',
                'modificador' => 'USER0' . Auth::user()->id,
                'pagado' => '0',
                'direccion' => '0',
                'user_id' => $identi_asesor->id, //usuario que registra
                'identificador' => $identi_asesor->identificador,
                'exidentificador' => $identi_asesor->exidentificador,
                'user_clavepedido' => $identi_asesor->clave_pedidos,
                'icelular_asesor' => $identi_asesor->letra,
                'icelular_cliente' => $cliente_AB->icelular,
                'celular_cliente' => $cliente_AB->celular,
                'estado_correccion' => '0',
                'condicion_envio_anterior' => '',
                'condicion_envio_code_anterior' => "0",
                'codigo_anterior' => '',
                'pedidoid_anterior' => 0,
                'resultado_correccion' => 0
            ]);

            $pedido->update([
                "correlativo" => $pedido->id_code
            ]);
            $zona = "";
            $zona_distrito = null;
            if ($request->distrito_env != '') {
                $zona_distrito = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                    ->where('distrito', $request->distrito_env)->first();
                $zona = $zona_distrito->zona;
            }

            $file_name = null;
            if ($request->destino_env == "OLVA") {
                $file_name = $request->file('observacion_env')->store('entregas', 'pstorage');
            }
            $pedido->update([
                'estado_sobre' => (($request->distrito_env == '') ? '0' : '1'),
            ]);
            if ($request->distrito_env != '') {
                $pedido->update([
                    'destino' => $request->destino_env,
                    'direccion' => $request->direccion,
                    'env_destino' => $request->destino_env,
                    'env_distrito' => $request->distrito_env,
                    'env_zona' => $zona,
                    'env_nombre_cliente_recibe' => $request->contacto_nom_env,
                    'env_celular_cliente_recibe' => $request->contacto_cel_env,
                    'env_cantidad' => "0",
                    'env_direccion' => (($request->destino_env == "LIMA") ? $request->direccion_env : ''),
                    'env_tracking' => (($request->destino_env == "LIMA") ? '' : $request->direccion_env),
                    'env_referencia' => (($request->destino_env == "LIMA") ? $request->referencia_env : ''),
                    'env_numregistro' => (($request->destino_env == "LIMA") ? '' : $request->referencia_env),
                    'env_rotulo' => (($request->destino_env == "LIMA") ? $file_name : ''),
                    'env_observacion' => (($request->destino_env == "LIMA") ? $request->observacion_env : ''),
                    'env_gmlink' => $request->maps_env,
                    'env_importe' => (($request->destino_env == "LIMA") ? '' : $request->importe_env),
                ]);
            }
            if ($cliente_deuda->crea_temporal == 1) {
                $limitepedidos = $cliente_deuda->activado_pedido;
                $limitepedidos--;
                if ($limitepedidos < 0) {
                    $limitepedidos = 0;
                }
                $cliente_deuda->update([
                    'activado_pedido' => $limitepedidos
                ]);
            }

            if ($cliente_AB->situacion == 'ABANDONO RECIENTE') {
                $cliente_AB->update([
                    'situacion' => 'RECUPERADO RECIENTE',
                ]);
            } else if ($cliente_AB->situacion == 'ABANDONO') {
                $cliente_AB->update([
                    'situacion' => 'RECUPERADO ABANDONO'
                ]);
            } else if ($cliente_AB->situacion == 'BASE FRIA') {
                $cliente_AB->update([
                    'situacion' => 'NUEVO'
                ]);
            }


            // ALMACENANDO DETALLES
            $codigo_generado = $codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;
            $validasobres = $request->validasobres;

            $files = $request->file('adjunto');

            if (isset($files)) {
                foreach ($files as $file) {
                    $file_name = $file->store('adjuntos', 'pstorage');
                    ImagenPedido::create([
                        'pedido_id' => $pedido->id,
                        'adjunto' => basename($file_name),
                        'estado' => '1'
                    ]);
                }
            } else {
                ImagenPedido::create([
                    'pedido_id' => $pedido->id,
                    'adjunto' => 'logo_facturas.png',
                    'estado' => '1'
                ]);
            }

            $contP = 0;

            while ($contP < count((array)$codigo)) {

                $detallepedido = DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'user_reg' => auth()->user()->id,
                    'codigo' => $codigo_generado,//$codigo[$contP],
                    'nombre_empresa' => $nombre_empresa[$contP],
                    'mes' => $mes[$contP],
                    'anio' => $anio[$contP],
                    'ruc' => $ruc[$contP],
                    'cantidad' => $cantidad[$contP],
                    'tipo_banca' => $tipo_banca[$contP],
                    'porcentaje' => $porcentaje[$contP],
                    'ft' => ($cantidad[$contP] * $porcentaje[$contP]) / 100,
                    'courier' => $courier[$contP],
                    'total' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'saldo' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'descripcion' => $descripcion[$contP],
                    'nota' => $nota[$contP],
                    'sobre_valida' => $validasobres[$contP],
                    'estado' => '1',//,
                ]);

                $contP++;

                //ACTUALIZAR DEUDA
                $cliente = Cliente::find($request->cliente_id);
                //
                $dateMinWhere = Carbon::now()->subDays(60)->format('d/m/Y');
                $dateMin = Carbon::now()->subDays(30)->format('d/m/Y');
                $dateMax = Carbon::now()->format('d/m/Y');

                $valido_deudas_mes = Pedido::where("pedidos.cliente_id", $request->cliente_id)
                    ->where("pedidos.estado", "1")
                    ->where("pedidos.pago", "0")
                    //->between("pedidos.estado","1")
                    ->whereBetween('pedidos.created_at', [$dateMinWhere, $dateMax])
                    ->where("pedidos.created_at", "<", $dateMin)->count();
                if ($valido_deudas_mes > 0) {
                    $cliente->update([
                        'deuda' => '1',
                        'pidio' => '1'
                    ]);

                } else {
                    $cliente->update([
                        'deuda' => '0',
                        'pidio' => '1'
                    ]);
                }
            }
            DB::commit();
            $html = $pedido->id;
        } catch (\Throwable $th) {
            throw $th;
        }
        return response()->json(['html' => $html]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($pedido)
    {
        //ver pedido anulado y activo
        $pedido = Pedido::with('cliente')->join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->leftJoin('users as ub','pedidos.user_reg','ub.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->leftJoin('pedidos_anulacions as pea','pedidos.id','pea.pedido_id')
            ->leftJoin('users as peau', 'pea.user_id_administrador', 'peau.id')
            ->select(
                'pedidos.*',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha',
                'ub.name as subio_pedido',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.courier',
                'dp.ft',
                'dp.descripcion',
                'dp.nota',
                'dp.adjunto',
                'dp.total',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion',
                'dp.saldo as diferencia',

                'peau.name as usersanulpar',
                'pea.created_at as fecsolicitudaaaa',
                'pea.updated_at as fecconfirmaaaaa',
                'pea.motivo_sol_admin',
            )
            //->activo()
            ->where('pedidos.id', $pedido)
            ->orderBy('pedidos.created_at', 'DESC')
            ->firstOrFail();

        $deudaTotal = DetallePedido::query()->activo()->whereIn('pedido_id', $pedido->cliente->pedidos()->activo()->pluck("id"))->sum("saldo");
        $adelanto = PagoPedido::query()->activo()->where('pedido_id', $pedido->id)->sum('abono');

        $imagenes = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->where('estado', '1')->get();

        $imagenesatencion = ImagenAtencion::where('pedido_id', $pedido->id)->where('estado', '=', '1')
            ->orderByDesc('estado')
            ->get();

        $motivo_anulado_parcial=[];
        if($pedido->condicion_code==Pedido::ANULADO_PARCIAL_INT)
        {
            $pedidos_a=PedidosAnulacion::where('pedido_id','=',$pedido->id)->get();
            foreach ($pedidos_a as $anulacion_p)
            {
                $estado_a_asesor=$anulacion_p->estado_aprueba_asesor;
                $estado_a_encargado=$anulacion_p->estado_aprueba_encargado;
                $estado_a_administrador=$anulacion_p->estado_aprueba_administrador;
                $estado_a_jefeop=$anulacion_p->estado_aprueba_jefeop;
                if($estado_a_asesor=='1')
                {
                    $motivo_anulado_parcial[] = [
                        'usu_motivo'=>'Asesor',
                        'motivo'=>$anulacion_p->motivo_solicitud,
                    ];
                }
                if($estado_a_encargado=='1')
                {
                    $motivo_anulado_parcial[] = [
                        'usu_motivo'=>'Encargado',
                        'motivo'=>$anulacion_p->motivo_sol_encargado,
                    ];
                }
                if($estado_a_administrador=='1')
                {
                    $motivo_anulado_parcial[] = [
                        'usu_motivo'=>'Administrador',
                        'motivo'=>$anulacion_p->motivo_sol_admin,
                    ];
                }
                if($estado_a_jefeop=='1')
                {
                    $motivo_anulado_parcial[] = [
                        'usu_motivo'=>'Jefe Operaciones',
                        'motivo'=>$anulacion_p->motivo_jefeop_admin,
                    ];
                }
            }
        }
        //dd($motivo_anulado_parcial);

        return view('pedidos.show', compact('pedido', 'imagenes', 'imagenesatencion', 'adelanto', 'deudaTotal','motivo_anulado_parcial'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        $mirol = Auth::user()->rol;
        $meses = [
            "ENERO" => 'ENERO',
            "FEBRERO" => 'FEBRERO',
            "MARZO" => 'MARZO',
            "ABRIL" => 'ABRIL',
            "MAYO" => 'MAYO',
            "JUNIO" => 'JUNIO',
            "JULIO" => 'JULIO',
            "AGOSTO" => 'AGOSTO',
            "SEPTIEMBRE" => 'SEPTIEMBRE',
            "OCTUBRE" => 'OCTUBRE',
            "NOVIEMBRE" => 'NOVIEMBRE',
            "DICIEMBRE" => 'DICIEMBRE',
        ];

        $anios = [
            "2020" => '2020',
            "2021" => '2021',
            "2022" => '2022',
            "2023" => '2023',
            "2024" => '2024',
            "2025" => '2025',
            "2026" => '2026',
            "2027" => '2027',
            "2028" => '2028',
            "2029" => '2029',
            "2030" => '2030',
            "2031" => '2031',
        ];

        $porcentajes = Porcentaje::where('porcentajes.cliente_id', $pedido->cliente_id)
            ->get();

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.name as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.courier',
                'dp.ft',
                'dp.descripcion',
                'dp.nota',
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion as condiciones',
                'pedidos.created_at as fecha'
            ])
            ->where('pedidos.estado', '1')
            ->where('pedidos.id', $pedido->id)
            ->where('dp.estado', '1')
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.name',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.cantidad',
                'dp.tipo_banca',
                'dp.porcentaje',
                'dp.courier',
                'dp.ft',
                'dp.descripcion',
                'dp.nota',
                'dp.adjunto',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at'
            )
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        if (auth()->user()->rol == User::ROL_ADMIN) {
            $rucs = Ruc::join('clientes as c', 'rucs.cliente_id', 'c.id')
                ->select([
                    'rucs.num_ruc as num_ruc',
                    DB::raw(" concat(rucs.num_ruc,'',rucs.empresa ) as empresa")
                ])
                ->where('rucs.cliente_id', $pedido->cliente_id)
                ->pluck('empresa', 'num_ruc');
        } else {
            $ruc = $pedido->ruc;
            $rucn = $pedido->empresas;
            $rucs = Ruc::join('clientes as c', 'rucs.cliente_id', 'c.id')
                ->select([
                    'rucs.num_ruc as num_ruc',
                    DB::raw(" concat(rucs.num_ruc,'  ',rucs.empresa ) as empresa")
                ])
                ->where('rucs.cliente_id', $pedido->cliente_id)
                ->where('rucs.num_ruc', $ruc)
                ->pluck('empresa', 'num_ruc');
        }

        $imagenes = ImagenPedido::where('imagen_pedidos.pedido_id', $pedido->id)->get();

        return view('pedidos.edit', compact('pedido', 'pedidos', 'meses', 'anios', 'porcentajes', 'imagenes', 'mirol', 'rucs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {/*return $request->all();*/
        $detallepedido = DetallePedido::where('pedido_id', $pedido->id)->first();
        try {
            DB::beginTransaction();

            // ALMACENANDO DETALLES
            $codigo = $request->codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;
            $contP = 0;

            $files = $request->file('adjunto');
            $destinationPath = base_path('public/storage/adjuntos/');
            $cont = 0;


            if (isset($files)) {
                foreach ($files as $file) {
                    $file_name = Carbon::now()->second . $file->getClientOriginalName(); //Get file original name
                    $file->move($destinationPath, $file_name);

                    ImagenPedido::create([
                        'pedido_id' => $pedido->id,
                        'adjunto' => $file_name,
                        'estado' => '1'
                    ]);

                    $cont++;
                }
            }

            while ($contP < count((array)$codigo)) {
                $detallepedido->update([
                    'codigo' => $codigo[$contP],
                    'nombre_empresa' => $nombre_empresa[$contP],
                    'mes' => $mes[$contP],
                    'anio' => $anio[$contP],
                    'ruc' => $ruc[$contP],
                    'cantidad' => $cantidad[$contP],
                    'tipo_banca' => $tipo_banca[$contP],
                    'porcentaje' => $porcentaje[$contP],
                    'ft' => ($cantidad[$contP] * $porcentaje[$contP]) / 100,
                    'courier' => $courier[$contP],
                    'total' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'saldo' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'descripcion' => $descripcion[$contP],
                    'nota' => $nota[$contP]
                ]);

                $contP++;
            }

            //ACTUALIZAR PORCENTAJE EN CLIENTE
            $porcentaje = Porcentaje::where('cliente_id', $pedido->cliente_id)
                ->where('nombre', $detallepedido->tipo_banca);
            $porcentaje->update([
                'porcentaje' => $detallepedido->porcentaje
            ]);

            //ACTUALIZAR MODIFICACION AL PEDIDO
            $pedido->update([
                'modificador' => 'USER' . Auth::user()->id
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            /*DB::rollback();
            dd($th);*/
        }

        if (Auth::user()->rol == "Asesor") {
            return redirect()->route('pedidos.mispedidos')->with('info', 'actualizado');
        } else
            return redirect()->route('pedidos.index')->with('info', 'actualizado');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::find($pedido->id);
        $pedido->update([
            'motivo' => $request->motivo,
            'responsable' => $request->responsable,
            'condicion' => 'ANULADO',
            'modificador' => 'USER' . Auth::user()->id,
            'estado' => '0'
        ]);

        $detalle_pedidos->update([
            'estado' => '0'
        ]);

        //ACTUALIZAR QUE CLIENTE NO DEBE
        $cliente = Cliente::find($pedido->cliente_id);

        $pedido_deuda = Pedido::where('cliente_id', $pedido->cliente_id)//CONTAR LA CANTIDAD DE PEDIDOS QUE DEBE
        ->where('pagado', '0')
            ->count();
        if ($pedido_deuda == 0) {//SINO DEBE NINGUN PEDIDO EL ESTADO DEL CLIENTE PASA A NO DEUDA(CERO)
            $cliente->update([
                'deuda' => '0'
            ]);
        }

        return redirect()->route('pedidos.index')->with('info', 'eliminado');
    }

    public function destroyid(Request $request)
    {
        if (!$request->hiddenID) {
            $html = '';
        } else {
            $pedido = Pedido::findOrFail($request->hiddenID);
            $filePaths = [];
            $files = $request->attachments;
            if (is_array($files)) {
                foreach ($files as $file) {
                    if ($file instanceof UploadedFile) {
                        $filePaths[] = $file->store("pedidos_adjuntos", "pstorage");
                    }
                }
            }

            $pedidosanulacion=PedidosAnulacion::where('pedido_id',$pedido->id);
            $contpedanulacions=$pedidosanulacion->count();
            if ($contpedanulacions==1){
                $pedidosanulacion->update([
                    'estado_aprueba_jefeop' => 1,
                ]);
            }

            setting()->load();
            foreach ($filePaths as $index => $path) {
                $key = "pedido." . $pedido->id . ".adjuntos_file." . $index;
                $keyd = "pedido." . $pedido->id . ".adjuntos_disk." . $index;
                setting([
                    $key => $path,
                    $keyd => 'pstorage'
                ]);
            }
            setting()->save();

            /**
             * tipo_banca
             * ELECTRONICA - sin banca
             * ELECTRONICA - banca
             * FISICO - sin banca
             * FISICA - sin banca
             * ELECTRONICA - bancarizado
             */
            //$is_fisico = $pedido->detallePedido()->where('detalle_pedidos.tipo_banca', 'like', 'FISICO%')->count();
            if (/*$is_fisico == 0 &&*/ $pedido->condicion_code == Pedido::ATENDIDO_INT) {
                //pendiente de anulacion
                $pedido->update([
                    'motivo' => $request->motivo,
                    'responsable' => $request->responsable,
                    'pendiente_anulacion' => 1,
                    'path_adjunto_anular' => null,
                    'path_adjunto_anular_disk' => 'pstorage',
                    'modificador' => 'USER' . Auth::user()->id,
                    'fecha_anulacion' => now(),
                ]);
                $html = '';
            } else {
                $pedido->update([
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

                $detalle_pedidos = $pedido->detallePedidos()->update([
                    'estado' => '0'
                ]);
                //anular correciones tbm
                //->where('celular', 'like', '%' . $q . '%')
                $correct = Correction::where('code', 'like', '' . $pedido->codigo . '%')->get();
                foreach ($correct as $correction) {
                    $correction->update(
                        ['estado' => 0]
                    );
                }

                $html = $detalle_pedidos;
            }
            //Cliente::createSituacionByCliente($pedido->cliente_id);
            //PostUpdateSituacion::dispatchSync($pedido->cliente_id);
            event(new PedidoAnulledEvent($pedido));


        }
        return response()->json(['html' => $html]);
    }

    public function destroyidpedidoadjuntooperaciones(Request $request)
    {
        if (!$request->hiddenID) {
            $html = '';
        } else {
            ImagenAtencion::where("pedido_id", $request->hiddenID)->update([
                'estado' => '0'
            ]);

            //$detalle_pedidos = DetallePedido::find($request->hiddenID);
            //$detalle_pedidos = DetallePedido::where('pedido_id',$request->hiddenID)->first() ;

            /*$detalle_pedidos->update([
                'estado' => '0'
            ]);*/

            $html = $request;
        }
        return response()->json(['html' => $html]);
    }

    public function Restaurarid(Request $request)
    {
        /************
         * BUSCAMOS EL PEDIDO
         */
        $pedido = Pedido::findOrFail($request->hiddenID);
        /************
         * BUSCAMOS EL GRUPO O PAQUETE DEL PEDIDO
         */
        $grupo_pedido = $pedido->direcciongrupo;


        if (!$request->hiddenID) {
            $html = '';
        } else {
            $pedido->detallePedido()->update([
                'estado' => '1',
            ]);
            if ($grupo_pedido != null) {

                $pedido->update([
                    'condicion' => Pedido::POR_ATENDER,
                    'condicion_code' => Pedido::POR_ATENDER_INT,
                    'condicion_envio' => Pedido::RECEPCION_COURIER,
                    'condicion_envio_code' => Pedido::RECEPCION_COURIER_INT,
                    'condicion_envio_at' => now(),
                    'modificador' => 'USER' . Auth::user()->id,
                    'estado' => '1',
                    'pendiente_anulacion' => '0'
                ]);
                if ($pedido->estado_sobre == 1) {
                    GrupoPedido::createGroupByPedido($pedido, false, true);
                }
                $grupo_pedido->update([
                    'motorizado_status' => '0',
                    'estado' => '0',
                ]);
            } else {
                $pedido->update([
                    'condicion' => Pedido::POR_ATENDER,
                    'condicion_code' => Pedido::POR_ATENDER_INT,
                    'modificador' => 'USER' . Auth::user()->id,
                    'estado' => '1',
                    'pendiente_anulacion' => '0'
                ]);
            }

            $html = '1';
        }

        return response()->json(['html' => $html]);
    }

    public function viewVentas()
    {
        return view('ventas.reportes.index');
    }

    public function MisPedidos()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $mirol = Auth::user()->rol;

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.misPedidos', compact('destinos', 'superasesor', 'dateMin', 'dateMax', 'mirol'));
    }

    public function mispedidostabla(Request $request)
    {
        $pedidos = null;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'dp.cantidad as cantidad',
                'dp.ruc as ruc',
                'pedidos.condicion_envio as condicion_env',
                'pedidos.condicion_envio',
                'pedidos.condicion as condiciones',
                'pedidos.condicion_code',

                /*'pedidos.envio',*/
                'pedidos.direccion',
                'pedidos.destino',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pendiente_anulacion',
                'dp.saldo as diferencia',
                'pedidos.pagado as condicion_pa',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'pedidos.estado'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion_code', [Pedido::POR_ATENDER_INT, Pedido::EN_PROCESO_ATENCION_INT, Pedido::ATENDIDO_INT, Pedido::ANULADO_INT]);


        if (Auth::user()->rol == "Asesor") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);


        } else if (Auth::user()->rol == "Super asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        }
        //$pedidos=$pedidos->get();

        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('action', function ($pedido) {

                $btn = ' <div>

  <ul class="" aria-labelledby="dropdownMenuButton">';

                $btn = $btn . '
                <a href="' . route('pedidosPDF', data_get($pedido, 'id')) . '" class="btn-sm dropdown-item" target="_blank"><i class="fa fa-file-pdf text-primary"></i> Ver PDF</a>';
                $btn = $btn . '<a href="' . route('pedidos.show', data_get($pedido, 'id')) . '" class="btn-sm dropdown-item"><i class="fas fa-eye text-success"></i> Ver pedido</a>';

                if ($pedido->estado > 0) {

                    if (Auth::user()->rol == "Super asesor" || Auth::user()->rol == "Administrador") {
                        if (!$pedido->pendiente_anulacion) {
                            $btn = $btn . '<a href="' . route('pedidos.edit', $pedido->id) . '" class="btn-sm dropdown-item"><i class="fas fa-edit text-warning" aria-hidden="true"></i> Editar pedido</a>';
                        }
                    }

                    if (Auth::user()->rol == "Administrador") {
                        if ($pedido->condicion_envio_code != Pedido::ENTREGADO_CLIENTE_INT) {
                            if (!$pedido->pendiente_anulacion) {
                                $btn = $btn . '<a href="" class="btn-sm dropdown-item" data-target="#modal-delete" data-toggle="modal" data-delete="' . $pedido->id . '"><i class="fas fa-trash-alt text-danger"></i> Anular pedido</a>';
                            }
                        }
                    }


                }

                $btn = $btn . '</ul></div>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function Pagados()//PEDIDOS PAGADOS
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                /* DB::raw('sum(dp.total) as total'), */
                'dp.total as total',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado as condicion_pa',//'pa.condicion as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                /* 'pedidos.created_at as fecha' */
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha')
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->where('pa.condicion', Pago::PAGO)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.icelular',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        $miidentificador = User::where("id", Auth::user()->id)->first()->identificador;

        return view('pedidos.pagados', compact('pedidos', 'superasesor', 'dateMin', 'dateMax', 'miidentificador'));
    }

    public function Pagadostabla()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('pago_pedidos as pp', 'pedidos.id', 'pp.pedido_id')
            ->join('pagos as pa', 'pp.pago_id', 'pa.id')
            ->select(
                'pedidos.id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion as condiciones',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado as condicion_pa',
                DB::raw('(select pago.condicion from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha')
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->where('pa.condicion', Pago::PAGO)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.icelular',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.motivo',
                'pedidos.responsable',
                'pedidos.pagado',
                'pa.condicion',
                'pedidos.created_at')
            ->orderBy('pedidos.created_at', 'DESC');
        //->get();


        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('action', function ($pedido) {
                $btn = '';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function SinPagos()//PEDIDOS POR COBRAR
    {

        $miidentificador = User::where("id", Auth::user()->id)->first()->identificador;

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.sinPagos', compact('superasesor', 'miidentificador'));
    }

    public function SinPagostabla()
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'c.id as cliente_id',
                'c.nombre as nombres',
                'c.icelular as icelulares',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.estado_sobre',
                'pedidos.condicion as condiciones',
                'pedidos.condicion_code',
                'pedidos.condicion_envio',
                'pedidos.condicion_envio_code',
                'pedidos.motivo',
                'pedidos.pendiente_anulacion',
                'pedidos.responsable',
                'pedidos.pagado as condicion_pa',
                'pedidos.created_at as fecha',
                'dp.saldo as diferencia',
                DB::raw('(select pago.condicion_code from pago_pedidos pagopedido inner join pedidos pedido on pedido.id=pagopedido.pedido_id and pedido.id=pedidos.id inner join pagos pago on pagopedido.pago_id=pago.id where pagopedido.estado=1 and pago.estado=1 order by pagopedido.created_at desc limit 1) as condiciones_aprobado'),
                'pedidos.estado_ruta',
                'pedidos.estado'
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.pagado', '<>', '2')
            ->where('pedidos.da_confirmar_descarga', '1')
            ->orderBy('pedidos.created_at', 'DESC');
        if (Auth::user()->rol == "Operario") {
            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $pedidos->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('users.rol', 'Operario')
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');
            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $pedidos = $pedidos->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == "Asesor") {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);
        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);
        } else if (Auth::user()->rol == "Super asesor") {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $usersasesores);

        }else if (Auth::user()->rol == User::ROL_ASISTENTE_PUBLICIDAD) {
            $pedidos = $pedidos->WhereIn('u.identificador', ['15','16','17','18','19']);
        }

        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('condicion_envio_color', function ($pedido) {
                return Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
            })
            ->editColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                if ($pedido->pendiente_anulacion == '1') {
                    $badge_estado .= '<span class="badge badge-success">' . Pedido::PENDIENTE_ANULACION . '</span>';
                    return $badge_estado;
                }
                if ($pedido->condicion_code == '4' || $pedido->estado == '0') {
                    return '<span class="badge badge-danger">ANULADO</span>';
                }
                if ($pedido->estado_sobre == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin: 0px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span>';

                }
                if ($pedido->estado_ruta == '1') {
                    $badge_estado .= '<span class="badge badge-success w-50" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span>';
                }
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $badge_estado .= '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                return $badge_estado;
            })
            ->addColumn('action', function ($pedido) {
                $btn = '';

                return $btn;
            })
            ->rawColumns(['action', 'condicion_envio'])
            ->make(true);

    }


    public function EnAtenciontabla(Request $request)
    {
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'c.nombre as nombres',
                'c.celular as celulares',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'dp.total as total',
                'pedidos.condicion',
                DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            ])
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion', Pedido::EN_PROCESO_ATENCION)
            ->groupBy(
                'pedidos.id',
                'c.nombre',
                'c.celular',
                'u.identificador',
                'dp.codigo',
                'dp.nombre_empresa',
                'dp.total',
                'pedidos.condicion',
                'pedidos.created_at',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.fecha_recepcion'
            )
            ->orderBy('pedidos.created_at', 'DESC');
            /*->get();*/

        if (Auth::user()->rol == User::ROL_OPERARIO) {
            $asesores = User::where('users.rol', User::ROL_ASESOR )
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos=$pedidos->WhereIn('pedidos.user_id', $asesores);
        } else if (Auth::user()->rol == User::ROL_JEFE_OPERARIO) {
            $operarios = User::where('users.rol', User::ROL_OPERARIO)
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::where('users.rol', User::ROL_ASESOR)
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos=$pedidos->WhereIn('pedidos.user_id', $asesores);
        }

        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('action', function ($pedido) {
                $btn = '';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function EnAtencion()
    {
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $condiciones = [
            "POR ATENDER" => Pedido::POR_ATENDER,
            "EN PROCESO ATENCION" => Pedido::EN_PROCESO_ATENCION,
            "ATENDIDO" => Pedido::ATENDIDO
        ];

        if (Auth::user()->rol == "Operario") {
            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->WhereIn('u.identificador', $asesores)
                //->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', Pedido::EN_PROCESO_ATENCION)
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('users.rol', 'Operario')
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');


            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->WhereIn('u.identificador', $asesores)
                //->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', Pedido::EN_PROCESO_ATENCION)
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        } else {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'c.nombre as nombres',
                    'c.celular as celulares',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    /* DB::raw('sum(dp.total) as total'), */
                    'dp.total as total',
                    'pedidos.condicion',
                    /* 'pedidos.created_at as fecha', */
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', Pedido::EN_PROCESO_ATENCION)
                ->groupBy(
                    'pedidos.id',
                    'c.nombre',
                    'c.celular',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        $imagenes = ImagenAtencion::get();
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pedidos.enAtencion', compact('dateMin', 'dateMax', 'pedidos', 'condiciones', 'imagenes', 'superasesor'));
    }


    public function cargarAtendidos(Request $request)//pedidoscliente
    {
        if (Auth::user()->rol == "Operario") {
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.operario', Auth::user()->id)
                ->where('pedidos.condicion', Pedido::ATENDIDO)
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('u.jefe', Auth::user()->id)
                ->where('pedidos.condicion', Pedido::ATENDIDO)
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        } else {
            $pedidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id as id',
                    'u.name as users',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'pedidos.condicion as estado',
                    DB::raw('DATE_FORMAT(pedidos.created_at, "%d/%m/%Y") as fecha'),
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->where('pedidos.condicion', Pedido::ATENDIDO)
                ->groupBy(
                    'pedidos.id',
                    'u.name',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.envio',
                    'pedidos.condicion_envio',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.fecha_recepcion'
                )
                ->orderBy('pedidos.created_at', 'DESC')
                ->get();
        }

        //return datatables($pedidos)->toJson();
    }


    public
    function Atender(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id', $pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'condicion' => $request->condicion,
            'modificador' => 'USER' . Auth::user()->id
        ]);

        if ($request->condicion == "3") {
            $pedido->update([
                'notificacion' => 'Pedido atendido'
            ]);

            event(new PedidoAtendidoEvent($pedido));
        }

        /* $files = $request->file('envio_doc'); */
        /* $destinationPath = base_path('public/storage/adjuntos/'); */

        $files = $request->file('adjunto');
        $destinationPath = base_path('public/storage/adjuntos/');

        $cont = 0;

        if (isset($files)) {
            foreach ($files as $file) {
                $file_name = Carbon::now()->second . $file->getClientOriginalName();
                $file->move($destinationPath, $file_name);

                ImagenAtencion::create([
                    'pedido_id' => $pedido->id,
                    'adjunto' => $file_name,
                    'estado' => '1'
                ]);

                $cont++;
            }
        }

        $detalle_pedidos->update([
            'envio_doc' => '1',
            'fecha_envio_doc' => $fecha,
            'cant_compro' => $request->cant_compro,
            'atendido_por' => Auth::user()->name,
            'atendido_por_id' => Auth::user()->id,
        ]);

        /* if ($request->hasFile('envio_doc')){
            $file_name = Carbon::now()->second.$files->getClientOriginalName();
            $files->move($destinationPath , $file_name);

            $detalle_pedidos->update([
                'envio_doc' => $file_name,
                'fecha_envio_doc' => $fecha,
                'cant_compro' => $request->cant_compro,
            ]);
        }
        else{
            $detalle_pedidos->update([
                'cant_compro' => $request->cant_compro,
            ]);
        } */

        return redirect()->route('operaciones.poratender')->with('info', 'actualizado');
    }


    public
    function Enviar(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id', $pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '1',
            'modificador' => 'USER' . Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
        ]);

        return redirect()->route('operaciones.atendidos')->with('info', 'actualizado');
    }


    public
    function Destino(Request $request, Pedido $pedido)
    {
        $pedido->update([
            'destino' => $request->destino,
            'modificador' => 'USER' . Auth::user()->id
        ]);

        return redirect()->route('envios.index')->with('info', 'actualizado');
    }

    public
    function SinEnviar(Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::where('pedido_id', $pedido->id)->first();
        $fecha = Carbon::now();

        $pedido->update([
            'envio' => '3',//SIN ENVIO
            'condicion_envio' => 3,
            'modificador' => 'USER' . Auth::user()->id
        ]);

        $detalle_pedidos->update([
            'fecha_envio_doc_fis' => $fecha,
            'fecha_recepcion' => $fecha,
            'atendido_por' => Auth::user()->name,
            'atendido_por_id' => Auth::user()->id,
        ]);

        return redirect()->route('operaciones.atendidos')->with('info', 'actualizado');
    }


    public
    function DescargarAdjunto($adjunto)
    {
        $destinationPath = base_path("public/storage/adjuntos/" . $adjunto);
        /* $destinationPath = storage_path("app/public/adjuntos/".$pedido->adjunto); */

        return response()->download($destinationPath);
    }

    public
    function DescargarGastos($adjunto)
    {
        $destinationPath = base_path("public/storage/gastos/" . $adjunto);

        return response()->download($destinationPath);
    }

    public function changeImg(Request $request)
    {
        $item = $request->item;
        $pedido = $request->pedido;
        $file = $request->file('adjunto');

        if (isset($file)) {
            $destinationPath = base_path('public/storage/entregas/');
            $file_name = Carbon::now()->second . $file->getClientOriginalName();
            $file->move($destinationPath, $file_name);
            $html = $file_name;

            DetallePedido::where('pedido_id', $pedido)
                ->update([
                    'foto' . $item => $file_name
                ]);
        } else {
            $html = "";
        }

        return response()->json(['html' => $html]);
    }


    public
    function Recibir(Pedido $pedido)
    {
        $pedido->update([
            'envio' => '2',
            'modificador' => 'USER' . Auth::user()->id
        ]);

        return redirect()->route('envios.index')->with('info', 'actualizado');
    }


    public
    function EnviarPedido(Request $request, Pedido $pedido)//'notificacion' => 'Nuevo pedido creado'
    {
        $detalle_pedidos = DetallePedido::where('pedido_id', $pedido->id)->first();

        $pedido->update([
            'condicion_envio' => $request->condicion,
            'trecking' => $request->trecking,
            'modificador' => 'USER' . Auth::user()->id
        ]);

        if ($request->condicion == "3") {
            $pedido->update([
                'notificacion' => 'Pedido entregado'
            ]);

            event(new PedidoEntregadoEvent($pedido));
        }

        $files = $request->file('foto1');
        $files2 = $request->file('foto2');

        $destinationPath = base_path('public/storage/entregas/');

        if ($request->hasFile('foto1') && $request->hasFile('foto2')) {
            $file_name = Carbon::now()->second . $files->getClientOriginalName();
            $file_name2 = Carbon::now()->second . $files2->getClientOriginalName();

            $files->move($destinationPath, $file_name);
            $files2->move($destinationPath, $file_name2);

            $detalle_pedidos->update([
                'foto1' => $file_name,
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        } else if ($request->hasFile('foto1') && $request->foto2 == null) {
            $file_name = Carbon::now()->second . $files->getClientOriginalName();
            $files->move($destinationPath, $file_name);

            $detalle_pedidos->update([
                'foto1' => $file_name,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        } else if ($request->foto1 == null && $request->hasFile('foto2')) {
            $file_name2 = Carbon::now()->second . $files2->getClientOriginalName();
            $files2->move($destinationPath, $file_name2);

            $detalle_pedidos->update([
                'foto2' => $file_name2,
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        } else {
            $detalle_pedidos->update([
                'fecha_recepcion' => $request->fecha_recepcion,
                'atendido_por' => Auth::user()->name,
                'atendido_por_id' => Auth::user()->id,
            ]);
        }

        if ($request->vista == 'ENTREGADOS') {
            return redirect()->route('envios.enviados')->with('info', 'actualizado');
        }

        return redirect()->route('envios.index')->with('info', 'actualizado');
    }

    public
    function DescargarImagen($imagen)
    {
        $destinationPath = base_path("public/storage/entregas/" . $imagen);

        return response()->download($destinationPath);
    }

    public
    function eliminarFoto1(Pedido $pedido)
    {
        $detallepedido = DetallePedido::find($pedido->id);
        $detallepedido->update([
            'foto1' => null
        ]);
        return redirect()->route('envios.enviados')->with('info', 'actualizado');
    }

    public
    function eliminarFoto2(Pedido $pedido)
    {
        $detallepedido = DetallePedido::find($pedido->id);
        $detallepedido->update([
            'foto2' => null
        ]);
        return redirect()->route('envios.enviados')->with('info', 'actualizado');
    }

    public
    function validadContenidoPedido(Request $request)
    {
        $pedidos_repetidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->select(
                'pedidos.id',
                'u.identificador',
                'pedidos.user_id',
                'pedidos.cliente_id',
                'pedidos.codigo',
                'pedidos.condicion_code',
                'dp.mes',
                'dp.anio',
                'dp.ruc',
                'dp.nombre_empresa',
                'dp.cantidad'
            //'dp.tipo_banca',
            //'dp.porcentaje',
            //'dp.courier',

            )
            ->where('u.identificador', $request->asesor)
            ->where('pedidos.cliente_id', $request->cliente)
            ->where('dp.mes', $request->mes)
            ->where('dp.anio', $request->ano)
            ->where('dp.cantidad', $request->cantidad)
            //->where('dp.tipo_banca', $request->banca)
            //->where('dp.porcentaje', $request->porcentaje)
            //->where('dp.courier', $request->courier)
            ->where('dp.ruc', $request->ruc)
            ->where('dp.nombre_empresa', $request->nombre_empresa)
            ->whereIn('pedidos.id',
                DetallePedido::query()
                    ->select('detalle_pedidos.pedido_id')
                    ->whereRaw('pedidos.id=detalle_pedidos.pedido_id')
                    ->where('detalle_pedidos.tipo_banca', '=', $request->ptipo_banca)
                    ->getQuery()
            )
            ->limit(5)
            ->get();

        return response()->json([
            'is_repetido' => $pedidos_repetidos->count() > 0,
            'coincidencia' => $pedidos_repetidos,
            'codigos' => $pedidos_repetidos->map(function (Pedido $p) {
                if ($p->condicion_code == 4) {
                    return "<span class='text-danger'>" . $p->codigo . "</span>";
                } else {
                    return "<span class='text-dark'>" . $p->codigo . "</span>";
                }
            })->join(', '),
        ]);
    }

    public function ConfirmarAnular(Request $request)
    {
        if ($request->get('action') == 'confirm_anulled_cancel') {
            $pedido = Pedido::findOrFail($request->pedido_id);
            /*if ($pedido->pendiente_anulacion != '1') {
                return response()->json([
                    "success" => 0,
                ]);
            }*/

            $pedidosanulacion=PedidosAnulacion::where('pedido_id',$request->pedido_id)->where('state_solicitud',1);
            $contpedanulacions=$pedidosanulacion->count();
            if ($contpedanulacions==1)
            {
                $pedidosanulacion=$pedidosanulacion->first();
                $pedidosanulacion->update([
                    'state_solicitud'=>0,
                ]);
                if ($pedidosanulacion->tipo=='C')
                {
                    $pedido->update([
                        'motivo' => "",
                        'responsable' => "",
                        'pendiente_anulacion' => 0,
                        'path_adjunto_anular' => "",
                        'path_adjunto_anular_disk' => "",
                        'modificador' => "",
                        'fecha_anulacion' => null,
                        'condicion'=>"",
                    ]);
                }
                else if ($pedidosanulacion->tipo=='F')
                {
                    $pedido->update([
                        'motivo' => "",
                        'condicion'=>"",
                    ]);
                }
                else if ($pedidosanulacion->tipo=='Q')
                {
                    $pedidodetail= DetallePedido::where('pedido_id',$request->pedido_id);
                    $pedido->update([
                        'motivo' => "",
                        'pagado' => 1,
                        'condicion'=>"",
                    ]);

                    $pedidodetail->update([
                        'cantidad' => $pedidosanulacion->cantidad,
                        'saldo' => $pedidosanulacion->difanterior,
                    ]);
                }
            }else{
                $pedido->update([
                    'pendiente_anulacion' => '0',
                    'user_anulacion_id' => \auth()->id(),
                    'fecha_anulacion_denegada' => now(),
                ]);
            }


            return response()->json([
                "success" => 1,'pedido'=>$pedido,'pedidosanulacion'=>$pedidosanulacion
            ]);
        }
        $this->validate($request, [
            'pedido_id' => 'required|integer',
            'attachments' => 'array',
            'attachments.*' => 'required|file',
        ]);
        $pedido = Pedido::findOrFail($request->pedido_id);
        if ( !in_array($pedido->condicion,[Pedido::PENDIENTE_ANULACION_PARCIAL,Pedido::PENDIENTE_ANULACION_COBRANZA])){
            if ($pedido->pendiente_anulacion != '1') {
                return response()->json([
                    "success" => 0,
                ]);
            }
        }

        $filePaths = [];
        $files = $request->attachments;
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $filePaths[] = $file->store("pedidos_notacredito", "pstorage");
                }
            }
        }
        setting()->load();
        foreach ($filePaths as $index => $path) {
            $key = "pedido." . $pedido->id . ".nota_credito_file." . $index;
            $keyd = "pedido." . $pedido->id . ".nota_credito_disk." . $index;
            setting([
                $key => $path,
                $keyd => 'pstorage'
            ]);
        }
        setting()->save();
        $pedidosanulacion=PedidosAnulacion::where('pedido_id',$request->pedido_id)->where('state_solicitud',1);
        $contpedanulacions=$pedidosanulacion->count();
        if ($contpedanulacions==1){
            $pedidosanulacion=PedidosAnulacion::where('pedido_id',$request->pedido_id)->where('state_solicitud',1)->first();
            if ($pedidosanulacion->tipo=='C'){
                $pedido->update([
                    'condicion' => 'ANULADO',
                    'condicion_code' => Pedido::ANULADO_INT,
                    'user_anulacion_id' => Auth::user()->id,
                    'fecha_anulacion_confirm' => now(),
                    'estado' => '0',
                    'pendiente_anulacion' => '0',
                ]);
                $pedido->detallePedidos()->update([
                    'estado' => '0'
                ]);
                $pedidosanulacion->update([
                    'user_id_jefeop'=>Auth::user()->id,
                    'motivo_jefeop_admin'=>Pedido::ANULADO,
                    'estado_aprueba_jefeop'=>1,
                ]);
            }else if ($pedidosanulacion->tipo=='F'){
                $pedido->update([
                    'condicion' => Pedido::ANULADO_PARCIAL,
                ]);
                $pedidosanulacion->update([
                    'user_id_jefeop'=>Auth::user()->id,
                    'motivo_jefeop_admin'=>Pedido::ANULADO_PARCIAL,
                    'estado_aprueba_jefeop'=>1,
                ]);
            }else if ($pedidosanulacion->tipo=='Q'){
                $pedido->update([
                    'condicion' => Pedido::ANULACION_COBRANZA,
                ]);
                $pedidosanulacion->update([
                    'user_id_jefeop'=>Auth::user()->id,
                    'motivo_jefeop_admin'=>Pedido::ANULACION_COBRANZA,
                    'estado_aprueba_jefeop'=>1,
                ]);
            }

        }else{
            $pedido->update([
                'condicion' => 'ANULADO',
                'condicion_code' => Pedido::ANULADO_INT,
                'user_anulacion_id' => Auth::user()->id,
                'fecha_anulacion_confirm' => now(),
                'estado' => '0',
                'pendiente_anulacion' => '0',
            ]);
            $pedido->detallePedidos()->update([
                'estado' => '0'
            ]);
        }


        $correct = Correction::where('code', 'like', '' . $pedido->codigo . '-C%')->get();
        foreach ($correct as $correction) {
            $correction->update(
                ['estado' => 0]
            );
        }

        event(new PedidoAnulledEvent($pedido));

        return response()->json([
            "success" => 1,'contpedanulacions'=>$contpedanulacions,'pedido'=>$pedido,'pedido'=>$pedidosanulacion
        ]);
    }

    public function Revertiraenviocourier(Request $request)
    {
        //llega pedido
        //llega grupo
        //ENTREGADO CLIENTE  DESTRUIR GRUPOS y VOLVER A ENVIO COURIER - JEFE OPE
        $fecha = Carbon::now();
        if ($request->tipoajax == 'pedido') {
            $pedido = Pedido::where("id", $request->aenviocourierrevertir)->first();
            $detalle_pedidos = DetallePedido::where('pedido_id', $pedido->id)->first();
            $pedido->update([
                'condicion_envio' => Pedido::ENVIO_COURIER_JEFE_OPE,
                'condicion_envio_code' => Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                'condicion_envio_at' => now(),
                'condicion' => Pedido::ENVIO_COURIER_JEFE_OPE,
                'condicion_code' => Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                'modificador' => 'USER' . Auth::user()->id
            ]);
            /*$pedido->detallePedidos()->activo()->update([
                "cant_compro" => 0
            ]);
            //liberar adjuntos
            $imagenesatencion_ = ImagenAtencion::where("pedido_id", $request->ajefeoperevertir);//->where("confirm", '0');
            $imagenesatencion_->update([
                'estado' => '0'
            ]);*/

        } else if ($request->tipoajax == 'grupo') {
            $grupo = DireccionGrupo::where("id", $request->aenviocourierrevertir)->first();
            $grupo->update([
                'condicion_envio' => Pedido::ENVIO_COURIER_JEFE_OPE,
                'condicion_envio_code' => Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                'foto1' => '',
                'foto2' => '',
                'foto3' => '',
                //'condicion_envio_at'=>now(),
                //'condicion' => Pedido::ENVIO_COURIER_JEFE_OPE,
                //'condicion_code' => Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                //'modificador' => 'USER' . Auth::user()->id
            ]);
            $pedido = Pedido::where('direccion_grupo', $request->aenviocourierrevertir)
                ->update([
                    'condicion_envio' => Pedido::ENVIO_COURIER_JEFE_OPE,
                    'condicion_envio_code' => Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                    //'condicion_envio_at'=>now(),
                ]);
        }
        return response()->json(['html' => $request->aenviocourierrevertir]);
    }


    public function jsonDistritos(Request $request)
    {
        $distritos_recojo = null;
        if ($request->destino == 'LIMA') {
            $distritos_recojo = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                ->where('estado', '1')
                //->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
                ->select([
                    'distrito',
                    DB::raw("concat(distrito,' - ',zona) as distritonam"),
                    'zona'
                ])
                ->where('zona', '!=', 'OLVA')
                ->orderBy('distrito')->get();
        } else if ($request->destino == 'OLVA') {
            $distritos_recojo = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                ->where('estado', '1')
                //->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
                ->select([
                    'distrito',
                    DB::raw("concat(distrito,' - ',zona) as distritonam"),
                    'zona'
                ])
                ->where('zona', '=', 'OLVA')
                ->orderBy('distrito')->get();
        }
        if ($distritos_recojo) {
            return $distritos_recojo;
        } else {
            return '';
        }

    }


    public function recojolistclientes(Request $request)
    {
        $pedidos = null;

        $idrequest = $request->cliente_id;
        $idpedido = $request->pedido;
        $consultaPedido = Pedido::where('id', $idpedido)->first();
        $direccion_grupo = $consultaPedido->direccion_grupo;
        /*$celularClienteRecibe=$consultaPedido->env_celular_cliente_recibe;
        $cantidad=$consultaPedido->env_cantidad;
        $tracking=$consultaPedido->env_tracking;
        $referencia=$consultaPedido->env_referencia;
        $numRegistro=$consultaPedido->env_numregistro;
        $rotulo=$consultaPedido->env_rotulo;
        $observacion=$consultaPedido->env_observacion;
        $gmLink=$consultaPedido->env_gmlink;
        $importe=$consultaPedido->env_importe;
        $zona=$consultaPedido->env_zona_asignada;
        $destino=$consultaPedido->env_destino;
        $direction=$consultaPedido->env_direccion;
        $nombredecliente=$consultaPedido->env_nombre_cliente_recibe;
        $distrito=$consultaPedido->env_distrito;*/

        $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->select(
                [
                    'pedidos.id as pedidoid',
                    'c.id as clienteid',
                    'dp.codigo',
                    'dp.nombre_empresa',
                ]
            )
            ->where('pedidos.cliente_id', $idrequest)->where('pedidos.condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT);
        //->where('pedidos.cliente_id', $idrequest)->where('direccion_grupo',$direccion_grupo);
        //->consultarecojo($celularClienteRecibe,$cantidad,$tracking,$referencia,$numRegistro, $rotulo,$observacion,$gmLink,$importe, $zona,$destino, $direction,$nombredecliente,$distrito)//;
        if ($request->pedidosNotIn) {
            $pedidos = $pedidos->whereNotIn('pedidos.id', [$request->pedidosNotIn]);
        }

        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->make(true);
    }

    public function getdireecionentrega(Request $request)
    {
        $codigo_pedido = $request->codigo_pedido;//userid de asesor
        $pedido = Pedido::where('id', $codigo_pedido)->first();

        $operario = User::where('id', $pedido->user_id)->first()->operario;
        $jefeop = User::where('id', $operario)->first()->jefe;

        //$result_direccion=User::where('id',$jefeop)->first()->id;
        $result_direccion = Directions::query()->where('user_id', $jefeop)->first()->direccion_recojo;

        $totalpedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->select(
                [
                    'pedidos.id as pedidoid',
                    'c.id as clienteid',
                    'dp.codigo',
                    'dp.nombre_empresa',
                ]
            )
            //->where('pedidos.cliente_id', $request->codigo_cliente)->where('direccion_grupo',$pedido->direccion_grupo)
            ->where('pedidos.cliente_id', $request->codigo_cliente)
            ->where('condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT);
        if (!!$request->pedidosNotIn)
            $totalpedidos = $totalpedidos->whereNotIn('pedidos.id', [$request->pedidosNotIn]);
        $totalpedidos = $totalpedidos->count();

        return $result_direccion . '|' . $totalpedidos;

    }

    public  function  getContadoresOlva(Request $request){

        $pedidos_provincia1 = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            ->whereIn('direccion_grupos.courier_estado', ['CONFIRMACION EN TIENDA','EN ALMACEN','DESPACHADO','REGISTRADO',])
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
            ]);

        add_query_filtros_por_roles_pedidos($pedidos_provincia1, 'users.identificador');
        $contadorOlvaIndex=$pedidos_provincia1->count();


        $pedidos_provincia2 = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            ->whereIn('direccion_grupos.courier_estado', ['ASIGNADO','MOTIVADO','NO ENTREGADO',]);
        add_query_filtros_por_roles_pedidos($pedidos_provincia2, 'users.identificador');
        $contadorOlvaNoentregado=$pedidos_provincia2->count();

        $pedidos_provincia3 = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ])
            ->whereIn('direccion_grupos.courier_estado', ['SINIESTRADO',]);

        add_query_filtros_por_roles_pedidos($pedidos_provincia3, 'users.identificador');
        $contadorOlvaExtraviado=$pedidos_provincia3->count();

        $pedidos_provincia4 = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'direccion_grupos.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
                Pedido::NO_ENTREGADO_OLVA_INT,
            ]);
            //->whereIn('direccion_grupos.courier_estado', ['SINIESTRADO',]);
        add_query_filtros_por_roles_pedidos($pedidos_provincia4, 'users.identificador');
        $contadorOlvaSeguimiento=$pedidos_provincia4->count();

        return response()->json([
            'contadorOlvaIndex' => $contadorOlvaIndex
            ,'contadorOlvaNoentregado' => $contadorOlvaNoentregado
            ,'contadorOlvaExtraviado' => $contadorOlvaExtraviado
            ,'contadorOlvaSeguimiento' => $contadorOlvaSeguimiento
        ]);
        /*return 0;*/
    }
}
