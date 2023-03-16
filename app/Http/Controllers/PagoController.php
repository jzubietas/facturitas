<?php

namespace App\Http\Controllers;

use App\Events\PagoEvent;
use App\Models\Cliente;
use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\Devolucion;
use App\Models\MovimientoBancario;
use App\Models\EntidadBancaria;
use App\Models\CuentaBancaria;
use App\Models\Titular;
use App\Models\Pago;
use App\Models\PagoPedido;
use App\Models\Pedido;
use App\Models\User;
use App\Notifications\DevolucionApproved;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagosobservados_cantidad = Pago::where('user_id', Auth::user()->id)//PAGOS OBSERVADOS
        ->where('estado', '1')
            ->where('condicion', Pago::OBSERVADO)
            ->count();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pagos.index', compact('pagosobservados_cantidad', 'superasesor'));
    }

    public function indextablahistorial(Request $request)
    {
        $query = null;
        $pedido = $request->pedido;
        $pagoid = $request->pago;
        $array_pago[] = ($pagoid);
        //return $array_pago;
        $query = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select(['pagos.id as id',
                'u.identificador as users',
                'c.celular',
                'pagos.observacion',
                'pagos.total_cobro',
                'pagos.condicion',
                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ppp.estado=1 and ped.pagado in (1,2)) as codigos "),
                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago "),
                'pagos.created_at',
            ])
            ->whereIn('pagos.condicion', [Pago::PAGO, Pago::ADELANTO, Pago::ABONADO])
            ->whereNotIn('pagos.id', $array_pago)
            ->where('pagos.estado', '1');

        $pagos = $query->get();


        return Datatables::of($pagos)
            ->addIndexColumn()
            ->addColumn('action', function ($pago) {
                $btn = '';

                $btn = $btn . '<a href="' . route('pagos.show', $pago['id']) . '" class="btn btn-info btn-sm">Ver</a>';


                return $btn;
            })
            ->rawColumns(['action','id2'])
            ->make(true);
    }

    public function indextabla(Request $request)
    {

        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select(['pagos.id as id',
                'pagos.correlativo as id2',
                DB::raw("concat(c.celular,'-',c.icelular) as ccliente"),//Agreado para hacer que busque el cliente
                'u.identificador as users',
                'c.icelular',
                'c.celular',
                'pagos.observacion',
                'pagos.total_cobro',
                'pagos.condicion',
                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ppp.estado=1 and ped.pagado in (1,2)) as codigos "),
                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago "),
                'pagos.created_at',
            ])
            ->whereIn('pagos.condicion', [Pago::PAGO, Pago::ADELANTO, Pago::ABONADO,Pago::PENDIENTE,Pago::OBSERVADO])
            ->where('pagos.estado', '1');

        if (Auth::user()->rol == 'Llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)

                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == 'Jefe de llamadas') {

            $pagos=$pagos->whereNotIn('u.identificador',['B','ADMIN'] );


        } else if (Auth::user()->rol == "Encargado") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);


        } else if (Auth::user()->rol == "Asesor") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', \auth()->user()->identificador)
                ->pluck('identificador');
            $pagos = $pagos->WhereIn('u.identificador', $usersasesores);

        }

        //$pagos = $pagos->get();
        return datatables()->query(DB::table($pagos))
            ->addIndexColumn()
            /*->editColumn('id', function ($pago) {
                //$cv=$pago->cantidad_voucher;
                //$cp=$pago->cantidad_pedido;
                //$unido= ( ($cv>1)? 'V':'I' )+''+( ($cp>1)? 'V':'I' );
                $fecha_created=Carbon::parse($pago->created_at);
                $dd=$fecha_created->format('d');
                $mm=$fecha_created->format('m');

                $unido=$dd.$mm;
                if($pago->id<10){
                    return 'PAG000'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else if($pago->id<100){
                    return 'PAG00'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else if($pago->id<1000){
                    return 'PAG0'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else{
                    return 'PAG'.$pago->users.'-'.$unido.'-'.$pago->id;
                }

            })*/
            ->addColumn('action', function ($pago) {
                $btn = '';
                if (Auth::user()->rol == "Administrador") {
                    $btn = $btn . '<a href="' . route('pagos.show', data_get($pago, 'id')) . '" class="btn btn-info btn-sm">Ver</a>';
                    //$btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';

                } else if (Auth::user()->rol == "Encargado") {
                    $btn = $btn . '<a href="' . route('pagos.show', data_get($pago, 'id')) . '" class="btn btn-info btn-sm">Ver</a>';
                    //$btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';

                } else if (Auth::user()->rol == "Asesor") {
                    $btn = $btn . '<a href="' . route('pagos.show', data_get($pago, 'id')) . '" class="btn btn-info btn-sm">Ver</a>';
                    //$btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';

                } else {
                    $btn = $btn . '<a href="' . route('pagos.show', data_get($pago, 'id')) . '" class="btn btn-info btn-sm">Ver</a>';
                    //$btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';

                }

                /*if($pago["condicion"]=='PAGO')
                {
                    $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pago['id'].'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                }*/

                return $btn;
            })
            ->rawColumns(['action','id'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create(Request $request)
    {
        $idcliente_request = "";


        $mirol = Auth::user()->rol;
        $users = null;

        if ($mirol == 'Llamadas') {
            $users = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->get();
            //->pluck('users.identificador', 'users.id');
        } else if ($mirol == 'Jefe de llamadas') {

            $users = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->get();
            //->pluck('users.identificador', 'users.id');
        } else if ($mirol == 'Asesor') {

            $users = User:: where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.id', Auth::user()->id)
                ->get();
            //->pluck('users.identificador', 'users.id');
        } else {
            $users = User::where('estado', '1')->get();//->pluck('identificador', 'id');
        }


        $clientes = null;
        if (Auth::user()->rol == "Administrador") {
            // Parámetro id de cliente
            if (request()->get('id')) {
                $clientes = Cliente::where('estado', '1')
                    ->where('id', request()->id)
                    ->where('tipo', '1')
                    ->get();
            } else {
                $clientes = Cliente::where('estado', '1')
                    //->where('user_id', Auth::user()->id)
                    ->where('tipo', '1')
                    ->get();
            }
        } /*elseif(Auth::user()->rol == "Llamadas"){

        }*/
        else {
            // Parámetro id de cliente
            if (request()->get('id')) {
                $clientes = Cliente::where('estado', '1')
                    ->where('user_id', Auth::user()->id)
                    ->where('id', request()->id)
                    ->where('tipo', '1')
                    ->get();
            } else {
                $clientes = Cliente::where('estado', '1')
                    ->where('user_id', Auth::user()->id)
                    ->where('tipo', '1')
                    ->get();
            }
        }

        $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select('pedidos.id','dp.codigo')
            ->where('pedidos.estado', '1')
            ->where('pedidos.pago', '0')
            ->get();
        $bancos = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            //"SCOTIABANK" => 'SCOTIABANK',
            //"PICHINCHA" => 'PICHINCHA',
        ];

        $bancos_procedencia = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            //"SCOTIABANK" => 'SCOTIABANK',
            //"PICHINCHA" => 'PICHINCHA',
            "OTROS" => 'OTROS',
        ];

        $tipotransferencia = [
            "TRANSFERENCIA" => 'TRANSFERENCIA',
            "DEPOSITO" => 'DEPOSITO',
            "INTERBANCARIO" => 'INTERBANCARIO',
            "GIRO" => 'GIRO',
            "YAPE" => 'YAPE',
            "PLIN" => 'PLIN',
            "TUNKI" => 'TUNKI',
        ];

        $titulares = [
            "EPIFANIO SOLANO HUAMAN" => 'EPIFANIO SOLANO HUAMAN',
            "ALFREDO ALEJANDRO GABRIEL MONTALVO" => 'ALFREDO ALEJANDRO GABRIEL MONTALVO',
            "SABINA LACHOS" => 'SABINA LACHOS',
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS',
        ];

        return view('pagos.create', compact('idcliente_request', 'clientes', 'pedidos', 'bancos', 'tipotransferencia', 'titulares', 'users', 'bancos_procedencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function pedidoscliente(Request $request)
    {
        $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        if ($request->cliente_id) {
            $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select('pedidos.id',
                    'dp.codigo',
                    'dp.total',
                    'dp.saldo')
                ->where('pedidos.cliente_id', $request->cliente_id)
                /* ->where('pedidos.pago', '0') */
                ->where('pedidos.pagado', '<>', '2')
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                ->get();

            foreach ($pedidos as $pedido) {
                $saldo_mostrar = $pedido->saldo;
                $saldo_mostrar = str_replace(',', '.', $saldo_mostrar);
                $html .= '<option value="' . $pedido->id . '_' . $pedido->codigo . '_' . $pedido->total . '_' . $pedido->saldo . '">Código: ' . $pedido->codigo . ' - Total: S/' . $pedido->total . ' - Saldo: S/' . $pedido->saldo . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function pedidosclientetabla(Request $request)
    {
        $pedidos = null;
        if (!$request->cliente_id) {
        } else {
            $idrequest = explode("_", $request->cliente_id);
            $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select('pedidos.id',
                    'dp.codigo',
                    'dp.total',
                    'dp.saldo',
                    'dp.saldo as diferencia'
                )
                ->where('pedidos.cliente_id', $idrequest)
                ->where('pedidos.pagado', '<>', '2')
                ->where('pedidos.estado', '1')
                ->where('pedidos.pendiente_anulacion', '0')
                ->where('dp.estado', '1')
                ->where('dp.total', '>', '0')
                ->where('dp.saldo', '>', '0');
            //->get();
            //return $request->perdonar_deuda;
            if (!$request->perdonar_deuda) {

            } else {
                $pedidos->where("pedidos.pago", "1")
                    ->where("pedidos.pagado", "1");

            }

            if (!$request->perdonar_currier) {

            } else {

                $pedidos->where("pedidos.pago", "1")
                    ->where("pedidos.pagado", "1")
                    ->WhereBetween("dp.saldo", ['11', '13'])
                    ->orWhereBetween("dp.saldo", ['17', '19']);
            }

            $pedidos = $pedidos->get();

            return Datatables::of($pedidos)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function asesorespago(Request $request)
    {

        {
            $html = '<option value="">' . trans('---- SELECCIONAR TODOS ----') . '</option>';
            $users = User::whereIn('rol', ['Asesor', 'Super asesor'])//where('rol', 'Asesor')
            ->where('estado', '1')
                ->get();

            foreach ($users as $user) {
                $html .= '<option value="' . $user->id . '">Asesor: ' . $user->identificador . ' - ' . $user->name . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function clientescreatepago(Request $request)
    {

        {
            //return request()->get('user_id');
            $html = '<option value="">' . trans('---- SELECCIONAR TODOS ----') . '</option>';

            if (Auth::user()->rol == "Administrador") {
                // Parámetro id de cliente
                if (request()->get('user_id')) {
                    $clientes = Cliente::where('estado', '1')
                        ->where('user_id', request()->get('user_id'))
                        ->where('tipo', '1')
                        //->where('deuda', '1')
                        ->get();
                } else {
                    $clientes = Cliente::where('estado', '1')
                        //->where('user_id', Auth::user()->id)
                        ->where('tipo', '1')
                        //->where('deuda', '1')
                        ->get();
                }
            } else {
                if (request()->get('user_id')) {
                    $clientes = Cliente::where('estado', '1')
                        //->where('user_id', Auth::user()->id)
                        ->where('user_id', request()->get('user_id'))
                        ->where('tipo', '1')
                        //->where('deuda', '1')
                        ->get();
                } else {
                    $clientes = Cliente::where('estado', '1')
                        ->where('user_id', Auth::user()->id)
                        ->where('tipo', '1')
                        //->where('deuda', '1')
                        ->get();
                }
            }

            foreach ($clientes as $cliente) {
                $html .= '<option value="' . $cliente->id . '">' . $cliente->nombre . ' - ' . $cliente->celular . '</option>';
            }
        }
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $contPedidos = 0;
        $contPedidosfor = 0;
        $pedido_id = $request->pedido_id;
        $pedidos_pagados_total = $request->checktotal;
        $pedidos_pagados_total_ar = array();
        $pedidos_pagados_parcial = $request->checkadelanto;
        $pedidos_pagados_parcial_ar = array();
        $saldo = $request->numberdiferencia;

        if (count((array)$pedido_id) > 0) {

            foreach ($pedido_id as $pedido_id_key => $pedido_id_value) {
                if (count((array)$pedidos_pagados_total)) {
                    if (array_key_exists($pedido_id_value, $pedidos_pagados_total)) {
                        $pedidos_pagados_total_ar[$pedido_id_value]["checked"] = 1;
                    } else {
                        $pedidos_pagados_total_ar[$pedido_id_value]["checked"] = 0;
                    }
                } else {
                    $pedidos_pagados_total_ar[$pedido_id_value]["checked"] = 0;
                }
                $pedidos_pagados_total_ar[$pedido_id_value]["pedido_id"] = $pedido_id[$pedido_id_key];
                $pedidos_pagados_total_ar[$pedido_id_value]["total_parcial"] = 'total';
                $pedidos_pagados_total_ar[$pedido_id_value]["saldo"] = $saldo[$pedido_id_value];
            }

            foreach ($pedido_id as $pedido_id_key => $pedido_id_value) {

                if (count((array)$pedidos_pagados_parcial)) {
                    if (array_key_exists($pedido_id_value, $pedidos_pagados_parcial)) {
                        $pedidos_pagados_parcial_ar[$pedido_id_value]["checked"] = 1;
                    } else {
                        $pedidos_pagados_parcial_ar[$pedido_id_value]["checked"] = 0;
                    }
                } else {
                    $pedidos_pagados_parcial_ar[$pedido_id_value]["checked"] = 0;
                }
                $pedidos_pagados_parcial_ar[$pedido_id_value]["pedido_id"] = $pedido_id[$pedido_id_key];
                $pedidos_pagados_parcial_ar[$pedido_id_value]["total_parcial"] = 'parcial';
                $pedidos_pagados_parcial_ar[$pedido_id_value]["saldo"] = $saldo[$pedido_id_value];
            }

            foreach ($pedido_id as $pedido_id_key => $pedido_id_value) {
                if ($saldo[$pedido_id_value] <= 3) {
                    $pedidos_pagados_total_ar[$pedido_id_value]["checked"] = 1;
                    $pedidos_pagados_parcial_ar[$pedido_id_value]["checked"] = 0;
                }

            }

            $pedidos_pagados_parcial = $pedidos_pagados_parcial_ar;
            $pedidos_pagados_total = $pedidos_pagados_total_ar;
        }
        if ($request->accion_perdonar == "1") {

            /*$request->validate([
                'imagen' => 'required',
            ]);*/

            //return $request->all();

            $pagado = $request->total_pago_pagar;

            try {
                DB::beginTransaction();
                $deuda_total = $request->total_pedido_pagar;
                $deuda_total = str_replace(',', '', $deuda_total);
                $pagado = str_replace(',', '', $pagado);

                $identi_asesor = User::where("identificador", $request->user_id)->where("unificado", "NO")->first();

                $pago = Pago::create([
                    'user_id' => $identi_asesor->id,
                    'cliente_id' => $request->cliente_id,
                    'total_cobro' => $deuda_total,
                    'total_pagado' => $pagado,
                    'condicion' => Pago::PAGO,
                    'notificacion' => 'Nuevo pago registrado',
                    'estado' => '1',
                    'subcondicion' => 'DEUDA PERDONADA'
                ]);

                //event(new PagoEvent($pago));

                $pedido_id = $request->pedido_id;
                $monto_actual = $request->numbersaldo;
                $saldo = $request->numberdiferencia;
                $contPe = 0;
                $monto_pagado_a_favor = $pagado;

                foreach ($pedido_id as $pedido_id_key => $pedido_id_value) {

                    if (count((array)$pedidos_pagados_total) > 0) {
                        if ($pedidos_pagados_total[$pedido_id_value]["checked"] == 1) {
                            $pagoPedido = PagoPedido::create([
                                'pago_id' => $pago->id,
                                'pedido_id' => $pedido_id_value,
                                'abono' => $monto_actual[$pedido_id_value] - $saldo[$pedido_id_value],
                                'estado' => '1'
                            ]);
                            $pedido = Pedido::find($pagoPedido->pedido_id);
                            $pedido->update([
                                'pago' => '1'
                            ]);
                            $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)->first();

                            $detalle_pedido->update([
                                'saldo' => $saldo[$pedido_id_value]
                            ]);

                        }
                    }
                    if (count((array)$pedidos_pagados_parcial) > 0) {
                        if ($pedidos_pagados_parcial[$pedido_id_value]["checked"] == 1) {
                            $pagoPedido = PagoPedido::create([
                                'pago_id' => $pago->id,
                                'pedido_id' => $pedido_id_value,
                                'abono' => $monto_actual[$pedido_id_value] - $saldo[$pedido_id_value],
                                'estado' => '1'
                            ]);
                            $pedido = Pedido::find($pagoPedido->pedido_id);
                            $pedido->update([
                                'pago' => '1'
                            ]);
                            $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)->first();

                            $detalle_pedido->update([
                                'saldo' => $saldo[$pedido_id_value]
                            ]);

                        }
                    }

                }

                $tipomovimiento = $request->tipomovimiento;
                $titular = $request->titular;
                $descripcion = $request->descripcion;
                $monto = $request->monto;
                $imagen = $request->imagen;
                $banco = $request->banco;
                $fecha = $request->fecha;

                $operacion = $request->operacion;
                $nota = $request->nota;

                $files = $request->file('imagen');
                $destinationPath = base_path('public/storage/pagos/');

                $cont = 0;
                $fileList = [];

                $contPa = 0;

                foreach ($monto as $monto_key => $monto_value) {
                    $file_name = $imagen[$monto_key];
                    $fileList[$monto_key] = array(
                        'file_name' => $file_name,
                    );
                }

                foreach ($monto as $monto_key => $monto_value) {
                    if (isset($fileList[$monto_key]['file_name'])) {
                        DetallePago::create([
                            'pago_id' => $pago->id,
                            'cuenta' => $tipomovimiento[$monto_key],
                            'titular' => $titular[$monto_key],
                            'operacion' => $operacion[$monto_key],
                            'observacion' => $nota[$monto_key] ?? 'N/A',
                            'monto' => $monto[$monto_key],
                            'banco' => $banco[$monto_key],
                            'fecha' => $fecha[$monto_key],
                            'fecha_deposito' => $fecha[$monto_key],
                            'imagen' => $fileList[$monto_key]['file_name'],
                            'estado' => '1'
                        ]);

                    } else {
                        DetallePago::create([
                            'pago_id' => $pago->id,
                            'cuenta' => $tipomovimiento[$monto_key],
                            'titular' => $titular[$monto_key],
                            'operacion' => $operacion[$monto_key],
                            'observacion' => $nota[$monto_key] ?? 'N/A',
                            'monto' => $monto[$monto_key],
                            'banco' => $banco[$monto_key],
                            'fecha' => $fecha[$monto_key],
                            'fecha_deposito' => $fecha[$monto_key],
                            'imagen' => 'logo_facturas.png',
                            'estado' => '1'
                        ]);
                    }

                }

                $contPedidos = 0;
                $contPT = 0;
                $contPP = 0;

                $pedido_a_pago_total = [];
                $pedido_a_pago_adelanto = [];

                if (count((array)$pedidos_pagados_total) > 0) {
                    foreach ($pedidos_pagados_total as $pedidos_pagados_total_index => $pedidos_pagados_total_index_valor) {
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pedido_id"] = $pedidos_pagados_total_index_valor["pedido_id"];
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pago_id"] = $pago->id;
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pagado"] = '2';
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["estado"] = $pedidos_pagados_total_index_valor["checked"];
                    }

                    foreach ($pedidos_pagados_total as $pedidos_pagados_total_index => $pedidos_pagados_total_index_valor) {
                        $pago_pedido_update_total = PagoPedido::where('pago_id', $pago->id)
                            ->where('pedido_id', $pedidos_pagados_total_index_valor["pedido_id"])
                            ->first();
                        if ($pedidos_pagados_total_index_valor['estado'] == 1) {
                            $pago_pedido_update_total->update([
                                'pagado' => '2'
                            ]);
                            $pedido_update_total = Pedido::find($pedidos_pagados_total_index_valor["pedido_id"]);
                            $pedido_update_total->update([
                                'pagado' => '2'
                            ]);
                        }

                    }


                }

                if (count((array)$pedidos_pagados_parcial) > 0) {
                    foreach ($pedidos_pagados_parcial as $pedidos_pagados_parcial_index => $pedidos_pagados_parcial_index_valor) {
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pedido_id"] = $pedidos_pagados_parcial_index_valor["pedido_id"];
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pago_id"] = $pago->id;
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pagado"] = '1';
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["estado"] = $pedidos_pagados_parcial_index_valor["checked"];
                    }

                    foreach ($pedidos_pagados_parcial as $pedidos_pagados_parcial_index => $pedidos_pagados_parcial_index_valor) {
                        $pago_pedido_update_adelanto = PagoPedido::where('pago_id', $pago->id)
                            ->where('pedido_id', $pedidos_pagados_parcial_index_valor["pedido_id"])
                            ->first();
                        if ($pedidos_pagados_parcial_index_valor['estado'] == 1) {
                            $pago_pedido_update_adelanto->update([
                                'pagado' => '1'
                            ]);

                            $pedido_update_adelanto = Pedido::find($pedidos_pagados_parcial_index_valor["pedido_id"]);
                            $pedido_update_adelanto->update([
                                'pagado' => '1'
                            ]);
                        }

                    }

                }

                $cliente = Cliente::find($request->cliente_id);

                $cliente->pedidos_mes_deuda = $cliente->pedidos()->activo()->noPagados()->whereIn('created_at', [now()->startOfMonth(), now()->endOfMonth()->endOfDay()])->count();
                $cliente->pedidos_mes_deuda_antes = $cliente->pedidos()->activo()->noPagados()->where('created_at', '<', now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->endOfDay())->count();


                $pedido_deuda = Pedido::where('cliente_id', $request->cliente_id)
                    ->where('pagado', '0')
                    ->count();

                if ($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes == 0) {
                    $cliente->update([
                        'deuda' => '0'
                    ]);

                } else if ($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes > 0) {
                    $cliente->update([
                        'deuda' => '1'
                    ]);

                } else if ($cliente->pedidos_mes_deuda == 0 && $cliente->pedidos_mes_deuda_antes > 0) {
                    $cliente->update([
                        'deuda' => '1'
                    ]);
                }





                DB::commit();


            } catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }


        } else {

            $request->validate([
                'imagen' => 'required',
            ]);
            if ($request->get('action') == 'devoluciones') {
                $this->validate($request, [
                    "bank_destino" => 'required',
                    "bank_number" => 'required',
                    "bank_titular" => 'required',
                ], [
                    "bank_destino.required" => 'El Banco de la cuenta del cliente es obligatorio',
                    "bank_number.required" => 'El Numero de la cuenta bancaria es obligatorio',
                    "bank_titular.required" => 'El Titular de la cuenta bancaria es obligatorio',
                ]);
            }

            try {
                DB::beginTransaction();
                $deuda_total = $request->total_pedido_pagar;
                $deuda_total = str_replace(',', '', $deuda_total);
                $pagado = $request->total_pago_pagar;
                $pagado = str_replace(',', '', $pagado);

                $identi_asesor = User::where("identificador", $request->user_id)->where("unificado", "NO")->first();

                $pago = Pago::create([
                    'user_id' => $identi_asesor->id,
                    'cliente_id' => $request->cliente_id,
                    'total_cobro' => $deuda_total,//total_pedido_pagar
                    'total_pagado' => $pagado,//total_pago_pagar
                    'condicion' => Pago::PAGO,//ADELANTO
                    'notificacion' => 'Nuevo pago registrado',
                    'estado' => '1'
                ]);

                $diferenciasaldocliente = str_replace(',', '', $request->diferencia);
                $clientesaldo = Cliente::where("id", $request->cliente_id)->first();


                //event(new PagoEvent($pago));

                $pedido_id = $request->pedido_id;
                $monto_actual = $request->numbersaldo;
                $saldo = $request->numberdiferencia;
                $contPe = 0;
                $monto_pagado_a_favor = $pagado;

                foreach ($pedido_id as $pedido_id_key => $pedido_id_value) {
                    if (count((array)$pedidos_pagados_total) > 0) {
                        if ($pedidos_pagados_total[$pedido_id_value]["checked"] == 1) {
                            $pagoPedido = PagoPedido::create([
                                'pago_id' => $pago->id,
                                'pedido_id' => $pedido_id_value,
                                'abono' => $monto_actual[$pedido_id_value] - $saldo[$pedido_id_value],
                                'estado' => '1'
                            ]);

                            $pedido = Pedido::find($pagoPedido->pedido_id);
                            $pedido->update([
                                'pago' => '1'
                            ]);
                            $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)->first();

                            $detalle_pedido->update([
                                'saldo' => $saldo[$pedido_id_value]
                            ]);

                        }
                    }
                    if (count((array)$pedidos_pagados_parcial) > 0) {
                        if ($pedidos_pagados_parcial[$pedido_id_value]["checked"] == 1) {
                            $pagoPedido = PagoPedido::create([
                                'pago_id' => $pago->id,
                                'pedido_id' => $pedido_id_value,
                                'abono' => $monto_actual[$pedido_id_value] - $saldo[$pedido_id_value],
                                'estado' => '1'
                            ]);

                            $pedido = Pedido::find($pagoPedido->pedido_id);
                            $pedido->update([
                                'pago' => '1'
                            ]);
                            $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)->first();

                            $detalle_pedido->update([
                                'saldo' => $saldo[$pedido_id_value]
                            ]);

                        }
                    }

                }

                $tipomovimiento = $request->tipomovimiento;
                $titular = $request->titular;
                $monto = $request->monto;
                $imagen = $request->imagen;
                $banco = $request->banco;
                $fecha = $request->fecha;
                $nota = $request->nota;


                $files = $request->file('imagen');
                $destinationPath = base_path('public/storage/pagos/');

                $cont = 0;
                $fileList = [];

                $contPa = 0;

                foreach ($monto as $monto_key => $monto_value) {
                    $file_name = $imagen[$monto_key];
                    $fileList[$monto_key] = array(
                        'file_name' => $file_name,
                    );
                }

                foreach ($monto as $monto_key => $monto_value) {
                    if (isset($fileList[$monto_key]['file_name'])) {
                        DetallePago::create([
                            'pago_id' => $pago->id,
                            'cuenta' => $tipomovimiento[$monto_key],
                            'titular' => $titular[$monto_key],
                            'monto' => $monto[$monto_key],
                            'banco' => $banco[$monto_key],
                            'fecha' => $fecha[$monto_key],
                            'fecha_deposito' => $fecha[$monto_key],
                            'imagen' => $fileList[$monto_key]['file_name'],
                            'estado' => '1',
                            'observacion' => $nota[$monto_key] ?? 'N/A',
                        ]);

                    } else {
                        DetallePago::create([
                            'pago_id' => $pago->id,
                            'cuenta' => $tipomovimiento[$monto_key],
                            'titular' => $titular[$monto_key],
                            'monto' => $monto[$monto_key],
                            'banco' => $banco[$monto_key],
                            'fecha' => $fecha[$monto_key],
                            'fecha_deposito' => $fecha[$monto_key],
                            'imagen' => 'logo_facturas.png',
                            'estado' => '1',
                            'observacion' => $nota[$monto_key] ?? 'N/A',
                        ]);
                    }

                }

                $contPedidos = 0;
                $contPT = 0;
                $contPP = 0;

                $pedido_a_pago_total = [];
                $pedido_a_pago_adelanto = [];

                if (count((array)$pedidos_pagados_total) > 0) {
                    foreach ($pedidos_pagados_total as $pedidos_pagados_total_index => $pedidos_pagados_total_index_valor) {
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pedido_id"] = $pedidos_pagados_total_index_valor["pedido_id"];
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pago_id"] = $pago->id;
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["pagado"] = '2';
                        $pedidos_pagados_total[$pedidos_pagados_total_index]["estado"] = $pedidos_pagados_total_index_valor["checked"];
                    }

                    foreach ($pedidos_pagados_total as $pedidos_pagados_total_index => $pedidos_pagados_total_index_valor) {
                        $pago_pedido_update_total = PagoPedido::where('pago_id', $pago->id)
                            ->where('pedido_id', $pedidos_pagados_total_index_valor["pedido_id"])
                            ->first();
                        if ($pedidos_pagados_total_index_valor['estado'] == 1) {
                            $pago_pedido_update_total->update([
                                'pagado' => '2'
                            ]);
                            $pedido_update_total = Pedido::find($pedidos_pagados_total_index_valor["pedido_id"]);
                            $pedido_update_total->update([
                                'pagado' => '2'
                            ]);
                        }

                    }


                }

                if (count((array)$pedidos_pagados_parcial) > 0) {
                    foreach ($pedidos_pagados_parcial as $pedidos_pagados_parcial_index => $pedidos_pagados_parcial_index_valor) {
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pedido_id"] = $pedidos_pagados_parcial_index_valor["pedido_id"];
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pago_id"] = $pago->id;
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["pagado"] = '1';
                        $pedidos_pagados_parcial[$pedidos_pagados_parcial_index]["estado"] = $pedidos_pagados_parcial_index_valor["checked"];
                    }

                    foreach ($pedidos_pagados_parcial as $pedidos_pagados_parcial_index => $pedidos_pagados_parcial_index_valor) {
                        $pago_pedido_update_adelanto = PagoPedido::where('pago_id', $pago->id)
                            ->where('pedido_id', $pedidos_pagados_parcial_index_valor["pedido_id"])
                            ->first();
                        if ($pedidos_pagados_parcial_index_valor['estado'] == 1) {
                            $pago_pedido_update_adelanto->update([
                                'pagado' => '1'
                            ]);

                            $pedido_update_adelanto = Pedido::find($pedidos_pagados_parcial_index_valor["pedido_id"]);
                            $pedido_update_adelanto->update([
                                'pagado' => '1'
                            ]);
                        }

                    }

                }

                $cliente = Cliente::find($request->cliente_id);


                $pedidos_mes_deuda = $cliente->pedidos()->activo()->noPagados()->whereIn('created_at', [now()->startOfMonth(), now()->endOfMonth()->endOfDay()])->count();
                $pedidos_mes_deuda_antes = $cliente->pedidos()->activo()->noPagados()->where('created_at', '<', now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->endOfDay())->count();

                $pedido_deuda = Pedido::where('cliente_id', $request->cliente_id)
                    ->where('pagado', '0')
                    ->count();

                if ($pedidos_mes_deuda > 0 && $pedidos_mes_deuda_antes == 0) {
                    $cliente->update([
                        'deuda' => '0'
                    ]);

                } else if ($pedidos_mes_deuda > 0 && $pedidos_mes_deuda_antes > 0) {
                    $cliente->update([
                        'deuda' => '1'
                    ]);

                } else if ($pedidos_mes_deuda == 0 && $pedidos_mes_deuda_antes > 0) {
                    $cliente->update([
                        'deuda' => '1'
                    ]);
                }

                if ($request->get('action') == 'devoluciones') {
                    Devolucion::query()->create([
                        "pago_id" => $pago->id,
                        "client_id" => $cliente->id,
                        "asesor_id" => $identi_asesor->id,
                        "amount" => $request->diferencia,
                        "status" => Devolucion::PENDIENTE,
                        "bank_destino" => $request->bank_destino,
                        "bank_number" => $request->bank_number,
                        "bank_titular" => $request->bank_titular,
                        "voucher_path" => null,
                    ]);
                }else if ($request->get('action') == 'saldo')
                {
                    if (!$request->diferencia) {

                    } else {
                        if ($diferenciasaldocliente > 3) {
                            $clientesaldo->update([
                                'saldo' => $diferenciasaldocliente
                            ]);
                        }
                    }
                }

                $fecha_created=Carbon::parse($pago->created_at);
                $dd=$fecha_created->format('d');
                $mm=$fecha_created->format('m');
                $unido=$dd.$mm;
                $pago->update([
                    "correlativo" => 'PAG'.$identi_asesor->identificador.'-'.$unido.'-'.$pago->id
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }

        }

        return redirect()->route('pagos.create')->with('info', 'registrado');

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)

    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select(['pagos.id',
                DB::raw(" (CASE WHEN pagos.id<10 THEN concat('PAG',u.identificador,'-',DATE_FORMAT(pagos.created_at, '%d%m'),
                                '-',pagos.id
                                )
                            WHEN pagos.id<100  THEN concat('PAG',u.identificador,'-',DATE_FORMAT(pagos.created_at, '%d%m'),
                                '-',pagos.id)
                            WHEN pagos.id<1000  THEN concat('PAG',u.identificador,'-',DATE_FORMAT(pagos.created_at, '%d%m'),
                                '-',pagos.id)
                            ELSE concat('PAG',u.identificador,'-',DATE_FORMAT(pagos.created_at, '%d%m'),
                                '-',pagos.id) END) AS id2"),
                'u.name as users',
                'c.celular', //cliente
                'c.nombre', //cliente
                'pagos.observacion',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at as fecha'
            ])
            ->where('pagos.id', $pago->id)
            ->groupBy('pagos.id',
                'u.name',
                'u.identificador',
                'c.celular',
                'c.nombre',
                'pagos.observacion',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at'
            )
            ->first();

        $pagoPedidos = PagoPedido::join('pedidos as p', 'pago_pedidos.pedido_id', 'p.id')
            ->join('detalle_pedidos as dp', 'p.id', 'dp.pedido_id')
            ->select('pago_pedidos.id',
                'dp.codigo',
                'p.id as pedidos',
                'p.condicion',
                'dp.total',
                'pago_pedidos.pagado',
                'pago_pedidos.abono'
            )
            ->where('pago_pedidos.estado', '1')
            ->where('p.estado', '1')
            ->where('dp.estado', '1')
            ->where('pago_pedidos.pago_id', $pago->id)
            ->get();

        $detallePagos = DetallePago::select(['id',
            'monto',
            'banco',
            'imagen',
            'fecha',
            'titular',
            'cuenta',
            'fecha_deposito',
            'observacion'])
            ->where('estado', '1')
            ->where('pago_id', $pago->id)
            ->get();
        //DB::raw('sum(detalle_pagos.monto) as total')
        $devoluciones = Devolucion::query()->wherePagoId($pago->id)->get();

        return view('pagos.show', compact('pagos', 'pagoPedidos', 'detallePagos', 'pago', 'devoluciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago $pago)

    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select('pagos.id',
                'u.name as users',
                'c.celular', //cliente
                'c.nombre', //cliente
                'c.saldo', //cliente
                'pagos.observacion',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at as fecha')
            ->where('pagos.id', $pago->id)
            ->groupBy('pagos.id',
                'u.name',
                'c.celular',
                'c.nombre',
                'c.saldo',
                'pagos.observacion',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at')
            ->first();
        $clientes = Cliente::where('estado', '1')
            ->where('user_id', Auth::user()->id)
            ->get();
        $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select('pedidos.id',
                'dp.codigo',
                'dp.total')
            ->where('pedidos.cliente_id', $pago->cliente_id)
            ->where('pedidos.pago', '0')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->get();
        $bancos = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            "SCOTIABANK" => 'SCOTIABANK',
            "PICHINCHA" => 'PICHINCHA',
            "YAPE" => 'YAPE',
            "PLIN" => 'PLIN',
            "TUNKI" => 'TUNKI'
        ];

        $bancos_procedencia = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            "SCOTIABANK" => 'SCOTIABANK',
            "PICHINCHA" => 'PICHINCHA',
            "OTROS" => 'OTROS',
        ];

        $listaPedidos = PagoPedido::join('pedidos as p', 'pago_pedidos.pedido_id', 'p.id')
            ->join('detalle_pedidos as dp', 'p.id', 'dp.pedido_id')
            ->select('pago_pedidos.id',
                'dp.codigo',
                'p.id as pedidos',
                'p.condicion',
                'dp.total',
                'pago_pedidos.pagado'
            )
            ->where('pago_pedidos.estado', '1')
            ->where('p.estado', '1')
            ->where('dp.estado', '1')
            ->where('pago_pedidos.pago_id', $pago->id)
            ->get();

        $listaPagos = DetallePago::
        select('id',
            'monto',
            'banco',
            'imagen',
            'fecha',
            'observacion')
            ->where('estado', '1')
            ->where('pago_id', $pago->id)
            ->get();

        $tipotransferencia = [
            "INTERBANCARIO" => 'INTERBANCARIO',
            "DEPOSITO" => 'DEPOSITO',
            "GIRO" => 'GIRO',
            "TRANSFERENCIA" => 'TRANSFERENCIA',
            "YAPE" => 'YAPE',
            "PLIN" => 'PLIN',
            "TUNKI" => 'TUNKI',
        ];

        $titulares = [
            "EPIFANIO SOLANO HUAMAN" => 'EPIFANIO SOLANO HUAMAN',
            "ALFREDO ALEJANDRO GABRIEL MONTALVO" => 'ALFREDO ALEJANDRO GABRIEL MONTALVO',
            "SABINA LACHOS" => 'SABINA LACHOS',
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS',
        ];

        return view('pagos.edit', compact('pago', 'pagos', 'clientes', 'pedidos', 'bancos', 'listaPedidos', 'listaPagos', 'tipotransferencia', 'titulares', 'bancos_procedencia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $pago)

    {
        $request->validate([
            'imagen' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // ALMACENANDO DETALLE DE PAGOS
            $monto = $request->monto;
            $banco = $request->banco;
            $fecha = $request->fecha;
            $files = $request->file('imagen');
            $destinationPath = base_path('public/storage/pagos/');

            $cont = 0;
            $fileList = [];

            foreach ($files as $file) {
                $file_name = $file->getClientOriginalName(); //Get file original name
                $fileList[$cont] = array(
                    'file_name' => $file_name,
                );
                $file->move($destinationPath, $file_name);

                $cont++;
            }

            $contPa = 0;

            while ($contPa < count((array)$monto)) {

                DetallePago::create([
                    'pago_id' => $pago->id,
                    'monto' => $monto[$contPa],
                    'banco' => $banco[$contPa],
                    'fecha' => $fecha[$contPa],
                    'imagen' => $fileList[$contPa]['file_name'],
                    'estado' => '1'
                ]);

                $contPa++;
            }
            $deuda_total = $request->total_pedidos;
            $total_pago_pagar = $request->total_pago_pagar;
            $total_pagos = $request->total_pagos;
            $pagado = ($total_pago_pagar * 1) + ($total_pagos * 1);

            if ($deuda_total - $pagado <= 3) {
                $pago->update([
                    'condicion' => Pago::PAGO,
                    'total_pagado' => $pagado,//total_pago_pagar
                    'notificacion' => 'Nuevo pago registrado',
                    'diferencia' => '0'//ACTUALIZAR LA DEUDA EN EL PAGO
                ]);

                //ACTUALIZAR QUE CLIENTE NO DEBE
                $cliente = Cliente::find($pago->cliente_id);
                $cliente->update([
                    'deuda' => '0',
                ]);

                //event(new PagoEvent($pago));
            } else {
                $pago->update([
                    'condicion' => Pago::ADELANTO,//PENDIENTE DE PAGO
                    'total_pagado' => $pagado,
                    'diferencia' => $deuda_total - $pagado//ACTUALIZAR LA DEUDA EN EL PAGO
                ]);
            }

            //ACTUALIZAR SALDO A FAVOR
            $cliente = Cliente::find($pago->cliente_id);

            $saldo = $request->saldo;
            $saldo = str_replace(',', '', $saldo);

            if ($request->saldo != null && $request->saldo != 0) {
                $cliente->update([
                    'saldo' => $saldo,
                ]);
                $pago->update([
                    'saldo' => $saldo,
                ]);
            } else {
                $cliente->update([
                    'saldo' => '0',
                ]);
                $pago->update([
                    'saldo' => '0',
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
        }
        if (Auth::user()->rol == "Asesor") {
            return redirect()->route('pagos.mispagos')->with('info', 'actualizado');
        } else
            return redirect()->route('pagos.index')->with('info', 'actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function eliminarPedido($id, Pago $pago)
    {
        $pagoPedido = PagoPedido::find($id);
        $pagoPedido->update([
            'estado' => '0'
        ]);
        return redirect()->route('pagos.edit', compact('pago'))->with('info', 'Eliminado');
    }

    public function eliminarPago($id, Pago $pago)
    {
        $detallePago = DetallePago::find($id);
        $detallePago->update([
            'estado' => '0'
        ]);
        return redirect()->route('pagos.edit', compact('pago'))->with('info', 'Eliminado');
    }

    public function destroy($pago_id)
    {
        $pago = Pago::where('id', $pago_id)->first();
        $detallePago = DetallePago::where('pago_id', $pago->id)->get();
        $pagoPedido = PagoPedido::where('pago_id', $pago->id)
            ->where('estado', '1')
            ->get();

        $pago->update([
            'estado' => '0'
        ]);

        foreach ($detallePago as $detalleP) {
            DetallePago::where('id', $detalleP->id)
                ->update([
                    'estado' => '0'
                ]);
        }

        foreach ($pagoPedido as $pagoP) {
            PagoPedido::where('id', $pagoP->id)
                ->update([
                    'estado' => '0'
                ]);

            $pedido = Pedido::find($pagoP->pedido_id);
            $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)
                //->where()
                ->where('estado', '1')
                ->get();
            //ACTUALIZA SALDO
            $detalle_pedido->update([
                //'pago' => '0',
                'saldo' => $detalle_pedido->saldo + $pagoP->abono
            ]);

            //ACTUALIZO SI PEDIDO TIENE PAGO
            if ($detalle_pedido->saldo == $detalle_pedido->total) {
                $pedido->update([
                    'pago' => 0,
                    'pagado' => 0
                ]);
            } else {
                $pedido->update([
                    'pago' => 1,
                    'pagado' => 1
                ]);
            }
        }

        return redirect()->route('pagos.index')->with('info', 'eliminado');
    }

    public function addImgTempPagoPerdonar(Request $request)
    {
        $file1 = $request->file('adjunto1');
        $file2 = $request->file('adjunto2');
        $file3 = $request->file('adjunto3');
        if (isset($file1)) {
            $destinationPath = base_path('public/storage/pagos/');
            $cont = 0;
            $file_name = Carbon::now()->second . $file1->getClientOriginalName();
            /*$fileList[$cont] = array(
                'file_name' => $file_name,
            );*/
            $file1->move($destinationPath, $file_name);
            $html = $file_name;
        }
        if (isset($file2)) {
            $destinationPath = base_path('public/storage/pagos/');
            $cont = 0;
            $file_name = Carbon::now()->second . $file2->getClientOriginalName();
            /*$fileList[$cont] = array(
                'file_name' => $file_name,
            );*/
            $file2->move($destinationPath, $file_name);
            $html = $file_name;
        }
        if (isset($file3)) {
            $destinationPath = base_path('public/storage/pagos/');
            $cont = 0;
            $file_name = Carbon::now()->second . $file3->getClientOriginalName();
            /*$fileList[$cont] = array(
                'file_name' => $file_name,
            );*/
            $file3->move($destinationPath, $file_name);
            $html = $file_name;
        }

        return response()->json(['html' => $html]);
    }

    public function addImgTemp(Request $request)
    {
        if ($request->hasFile('adjunto')) {
            $file = $request->file('adjunto');
            $destinationPath = public_path('storage/pagos/');
            $cont = 0;
            $file_name = Carbon::now()->second . $file->getClientOriginalName();
            $fileList[$cont] = array(
                'file_name' => $file_name,
            );
            $file->move($destinationPath, $file_name);
            $html = $file_name;
        } else {
            $html = "";
        }

        return response()->json(['html' => $html]);
    }

    public function changeImg(Request $request)
    {
        $dp = $request->DPConciliar;
        $file = $request->file('adjunto');
        if (isset($file)) {
            $destinationPath = base_path('public/storage/pagos/');
            $cont = 0;
            $file_name = Carbon::now()->second . $file->getClientOriginalName();
            $fileList[$cont] = array(
                'file_name' => $file_name,
            );
            $file->move($destinationPath, $file_name);
            $html = $file_name;
            //update imagen en dp

            DetallePago::where('id', $dp)
                ->update([
                    'imagen' => $file_name
                ]);

        } else {
            $html = "";
        }

        return response()->json(['html' => $html]);
        //return redirect()->route('pedidosPDF', $pedido)->with('info', 'registrado');
    }

    public function destroyid(Request $request)
    {
        //modificar primero
        $html = '';
        if (!$request->hiddenID) {
            return 'nada';
        } else {
            //$pago_id=;
            $pago_id = $request->hiddenID;
            //return $pago_id;
            /*$pago = Pago::where('id', $request->hiddenID)
                        ->where('estado', '1')
                        ->first();//solo 1*/

            $pago = Pago::where('id', $pago_id)->first();
            $detallePago = DetallePago::where('pago_id', $pago->id)->get();
            $pagoPedido = PagoPedido::where('pago_id', $pago->id)
                ->where('estado', '1')
                ->get();

            try {
                //$html.="<br>Iniciando trycatch";
                DB::beginTransaction();

                //respecto al pago cabecera
                $pago->update([
                    'estado' => '0',
                    'condicion' => Pago::PAGO
                ]);
                //$html.="<br>cambie cabecera pago ";

                //recorrido para el detallepago
                foreach ($detallePago as $detalleP) {
                    //$html.="<br>detalepago id ".$detalleP->id." a estado 0";//2725
                    //break;
                    DetallePago::where('id', $detalleP->id)
                        ->update([
                            'estado' => '0'
                        ]);

                    MovimientoBancario::where("detpago", $detalleP->id)->where("cabpago", $pago_id)
                        ->update([
                            'detpago' => "0",
                            'cabpago' => "0",
                            'pago' => "0"
                        ]);
                }
                $html = "";
                foreach ($pagoPedido as $pagoP) {
                    //$html.="<br>pagopedidos id ".$pagoP->id."  a estado 0";
                    PagoPedido::where('id', $pagoP->id)
                        ->update([
                            'estado' => '0'
                        ]);

                    $pedido = Pedido::where("id", $pagoP->pedido_id)->first();
                    //$html.="<br> el pedido ".$pagoP->pedido_id;
                    $detalle_pedido = DetallePedido::where('pedido_id', $pedido->id)
                        ->where('estado', '1')
                        ->first();

                    $detalle_pedido_saldo = $detalle_pedido->saldo * 1;
                    $detalle_pedido_total = $detalle_pedido->total * 1;//780

                    //$html.="<br> actualizo saldo ".$detalle_pedido_saldo." -> ".($pagoP->abono*1)." -> ".($detalle_pedido_saldo + $pagoP->abono*1)."a detallepedido ".$pedido->id;
                    $detalle_pedido->update([
                        'saldo' => $detalle_pedido_saldo + $pagoP->abono * 1
                    ]);

                    $detalle_pedido_saldo = $detalle_pedido->saldo * 1;

                    //$html.= "<br>pedidoid ".$pedido->id." saldonuevo ". $detalle_pedido_saldo."     total ".$detalle_pedido_total."<br>";
                    /*
                    pedidoid 3964 saldonuevo 662 total 662
                    pedidoid 4246 saldonuevo 650 total 650
                    pedidoid 4577 saldonuevo 650 total 650
                    pedidoid 4695 saldonuevo 1170 total 1170
                    pedidoid 5111 saldonuevo 780 total 780
                    */

                    if ($detalle_pedido_saldo == $detalle_pedido_total) {
                        $pedido->update([
                            'pago' => 0,
                            'pagado' => 0
                        ]);
                    } else {
                        $pedido->update([
                            'pago' => 1,
                            'pagado' => 1
                        ]);
                    }
                }
                //return  $html;

                DB::commit();

            } catch (\Throwable $th) {
                throw $th;
                $html = "error";

            }
            //return $pagoPedido;

        }
        return response()->json(['html' => $html]);
    }

    public function desabonarid(Request $request)
    {
        //modificar primero
        $html = '';
        if (!$request->hiddenDesabonar) {
            return 'nada';
        } else {
            //$pago_id=;
            //3840
            $pago_id = $request->hiddenDesabonar;


            try {
                DB::beginTransaction();

                MovimientoBancario::Where("cabpago", $pago_id)
                    ->update([
                        'detpago' => "0",
                        'cabpago' => "0",
                        'pago' => "0"
                    ]);

                DB::commit();

                Pago::where("id", $pago_id)
                    ->update([
                        'condicion' => Pago::PAGO
                    ]);

            } catch (\Throwable $th) {
                $html = "error";
                throw $th;

            }

        }
        return response()->json(['html' => $html]);
    }

    public function pagodetalleUpdate(Request $request)
    {
        //modificar primero
        if (!$request->conciliar) {
            $html = '';
            return 'nada';
        } else {
            //$pago_id=;
            $html = '';
            $detalle = $request->conciliar;

            $titular = $request->titular;
            $banco = $request->banco;
            $fecha = $request->fecha;
            $html = "";

            try {

                DB::beginTransaction();

                DetallePago::where('id', $detalle)
                    ->update([
                        'titular' => $titular,
                        'banco' => $banco,
                        'fecha' => $fecha,
                        'fecha_deposito' => $fecha
                    ]);
                $detallepago = DetallePago::where('id', $detalle)
                    ->select(
                        'banco', 'titular',
                        DB::raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'),
                        DB::raw('DATE_FORMAT(fecha, "%Y-%m-%d") as fecha_conciliar'),
                    )
                    ->first();


                DB::commit();
                $html = $detallepago;

            } catch (\Throwable $th) {
                throw $th;
                $html = "error";

            }


        }
        return response()->json(['html' => $html]);
    }

    public function MisPagosTabla(Request $request)
    {
        $pagos = null;
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select(['pagos.id as id',
                'u.identificador as users',
                'c.icelular',
                'c.celular',
                'pagos.observacion',
                'pagos.total_cobro',
                'pagos.condicion',
                DB::raw(" (CASE WHEN pagos.id<10 THEN concat('PAG',u.identificador,
                                IF ( (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1,'V','I' )  ,
                                IF ( (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1) ) >1,'V','I' ),pagos.id
                                )
                            WHEN pagos.id<100  THEN concat('PAG00',u.identificador,
                                IF ( (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1,'V','I' )  ,
                                IF ( (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1) ) >1,'V','I' ),pagos.id)
                            WHEN pagos.id<1000  THEN concat('PAG0',u.identificador,
                                IF ( (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1,'V','I' )  ,
                                IF ( (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1) ) >1,'V','I' ),pagos.id)
                            ELSE concat('PAG',u.identificador,
                                IF ( (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1,'V','I' )  ,
                                IF ( (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1) ) >1,'V','I' ),pagos.id)
                                 END) AS code"),

                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%Y-%m-%d")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha2'),
                DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ppp.estado=1 and ped.pagado in (1,2)) as codigos "),
                DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago "),
                'pagos.created_at'
            ])
            ->whereIn('pagos.condicion', [Pago::PAGO, Pago::ADELANTO, Pago::ABONADO])
            ->where('pagos.estado', '1');
        //->where('u.identificador', Auth::user()->identificador);
        //->get();

        if (Auth::user()->rol == 'Llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $pagos = $pagos->WhereIn("u.identificador", $usersasesores);

        } else if (Auth::user()->rol == 'Jefe de llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn("u.identificador", $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pagos = $pagos->WhereIn("u.identificador", $usersasesores);

        } else {
            $pagos = $pagos;
        }
        //$pagos = $pagos->get();

        return Datatables::of(DB::table($pagos))
            ->addIndexColumn()
            ->editColumn('code', function ($pago) {
                //$cv=$pago->cantidad_voucher;
                //$cp=$pago->cantidad_pedido;
                //$unido= ( ($cv>1)? 'V':'I' )+''+( ($cp>1)? 'V':'I' );
                $fecha_created=Carbon::parse($pago->created_at);
                $dd=$fecha_created->format('d');
                $mm=$fecha_created->format('m');

                $unido=$dd.$mm;
                if($pago->id<10){
                    return 'PAG000'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else if($pago->id<100){
                    return 'PAG00'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else if($pago->id<1000){
                    return 'PAG0'.$pago->users.'-'.$unido.'-'.$pago->id;
                }else{
                    return 'PAG'.$pago->users.'-'.$unido.'-'.$pago->id;
                }

            })
            ->editColumn('total_pago', function ($pago) {
                return money_f($pago->total_pago);
            })
            ->addColumn('action', function ($pago) {
                $btn = '';

                return $btn;
            })
            ->rawColumns(['action','total_pago','code'])
            ->make(true);

    }

    public function MisPagos()
    {
        $mirol = Auth::user()->rol;
        $dateMin = Carbon::now()->subDays(4)->format('d/m/Y');
        $dateMax = Carbon::now()->format('d/m/Y');

        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'c.celular',
                'pagos.observacion',
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                /* 'pagos.created_at as fecha' */
                DB::raw('DATE_FORMAT(pagos.created_at, "%d/%m/%Y") as fecha')
            )
            ->where('pagos.estado', '1')
            ->where('dpe.estado', '1')
            ->where('dpa.estado', '1')
            ->where('u.id', Auth::user()->id)
            ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'c.celular',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
            ->get();

        $pagosobservados_cantidad = Pago::where('user_id', Auth::user()->id)//PAGOS OBSERVADOS
        ->where('estado', '1')
            ->where('condicion', Pago::OBSERVADO)
            ->count();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pagos.mispagos', compact('pagos', 'pagosobservados_cantidad', 'superasesor', 'dateMin', 'dateMax', 'mirol'));
    }


    public function PagosIncompletos()
    {
        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('detalle_pagos as dpa', 'pagos.id', 'dpa.pago_id')
            ->join('pago_pedidos as pp', 'pagos.id', 'pp.pago_id')
            ->join('pedidos as p', 'pp.pedido_id', 'p.id')
            ->join('detalle_pedidos as dpe', 'p.id', 'dpe.pedido_id')
            ->select('pagos.id',
                'dpe.codigo as codigos',
                'u.name as users',
                'pagos.observacion',
                'dpe.total as total_deuda',
                DB::raw('sum(dpa.monto) as total_pago'),
                'pagos.condicion',
                'pagos.created_at as fecha'
            )
            ->where('pagos.estado', '1')
            //->where('dpe.estado', '1')
            //->where('dpa.estado', '1')
            //->where('u.identificador', Auth::user()->identificador)
            ->where('pagos.condicion', Pago::ADELANTO)
            ->groupBy('pagos.id',
                'dpe.codigo',
                'u.name',
                'pagos.observacion', 'dpe.total',
                'pagos.total_cobro',
                'pagos.condicion',
                'pagos.created_at')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pagos.pagosincompletos', compact('pagos', 'superasesor'));
    }

    //funcion pagos observados *
    public function PagosObservados(Request $request)
    {

        if ($request->has("datatable")) {
            $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
                ->join('clientes as c', 'pagos.cliente_id', 'c.id')
                ->select(['pagos.id as id',
                    'pagos.correlativo as code',
                    'u.name as users',
                    'u.identificador as identifica',
                    'pagos.observacion',
                    'pagos.total_cobro as total_deuda',
                    'pagos.total_pagado as total_pago',
                    'pagos.condicion',
                    DB::raw('(select DATE_FORMAT( MIN(dpa.fecha), "%d/%m/%Y %H:%i:%s")   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha'),
                    DB::raw('(select UNIX_TIMESTAMP(MIN(dpa.fecha))   from detalle_pagos dpa where dpa.pago_id=pagos.id and dpa.estado=1) as fecha_timestamp'),
                    DB::raw(" (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) ) as cantidad_voucher "),
                    DB::raw(" (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  ) as cantidad_pedido "),
                    DB::raw(" ( select GROUP_CONCAT(ppp.codigo) from pago_pedidos ped inner join pedidos ppp on ped.pedido_id =ppp.id where pagos.id=ped.pago_id and ped.estado=1 and ppp.estado=1 and ped.pagado in (1,2)) as codigos "),
                    DB::raw(" (select sum(ped2.abono) from pago_pedidos ped2 where ped2.pago_id =pagos.id and ped2.estado=1 and ped2.pagado in (1,2) ) as total_pago2 "),
                    'pagos.created_at'
                ])
                ->where('pagos.estado', '1')
                //->where('u.id', Auth::user()->id)
                ->where('pagos.condicion', Pago::OBSERVADO);
            if ($request->has("asesores") && !empty($request->asesores)) {
                $pagos->where('pagos.user_id', $request->asesores);
            }
            return datatables()
                ->query(DB::table($pagos))
                //->editColumn('code', function ($pago) {
                    //$cv=$pago->cantidad_voucher;
                    //$cp=$pago->cantidad_pedido;
                    //$unido= ( ($cv>1)? 'V':'I' )+''+( ($cp>1)? 'V':'I' );
                    /*$fecha_created=Carbon::parse($pago->created_at);
                    $dd=$fecha_created->format('d');
                    $mm=$fecha_created->format('m');

                    $unido=$dd.$mm;
                    if($pago->id<10){
                        return 'PAG000'.$pago->identifica.'-'.$unido.'-'.$pago->id;
                    }else if($pago->id<100){
                        return 'PAG00'.$pago->identifica.'-'.$unido.'-'.$pago->id;
                    }else if($pago->id<1000){
                        return 'PAG0'.$pago->identifica.'-'.$unido.'-'.$pago->id;
                    }else{
                        return 'PAG'.$pago->identifica.'-'.$unido.'-'.$pago->id;
                    }
                })*/
                ->addIndexColumn()
                ->addColumn('action', function ($pago) {
                    $btn = '';
                    if (Auth::user()->can('pagos.show')) {
                        $btn = $btn . '<a href="' . route('pagos.show', data_get($pago, 'id')) . '" class="btn btn-info btn-sm">Ver</a>';
                    }
                    if (Auth::user()->can('pagos.edit')) {
                        $btn = $btn . '<a href="' . route('administracion.revisar', data_get($pago, 'id')) . '" class="btn btn-success btn-sm">Editar</a>';
                    }
                    if (Auth::user()->can('pagos.destroy')) {
                        $btn = $btn . '<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="' . data_get($pago, 'id') . '"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action','code'])
                ->toJson();
        }

        // $pagos = $pagos->get();
        $pagosobservados_cantidad = Pago::where('user_id', Auth::user()->id)//PAGOS OBSERVADOS
        ->where('estado', '1')
            ->where('condicion', Pago::OBSERVADO)
            ->count();

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('pagos.pagosobservados', compact(/*'pagos',*/ 'pagosobservados_cantidad', 'superasesor'));
    }

    public function viewAlmacen()
    {
        return view('ingresos.reportes.index');
    }

    /*tabla para aprobados*/


    //public function Revisar(Pago $pago)


    public function Revisarpago(Request $request)
    {
        //$request->pago_id
        $hiddenID = $request->pago_id;
        $pago_id = $request->pago_id;
        $condiciones = array_flip([
            "PAGO" => Pago::PAGO,
            "OBSERVADO" => Pago::OBSERVADO,
            "ABONADO" => Pago::ABONADO,
            "ABONADO_PARCIAL" => Pago::ABONADO_PARCIAL
        ]);

        $cuentas = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "YAPE" => 'YAPE',
            "INTERBANK" => 'INTERBANK'
        ];

        $titulares = [
            "EPIFANIO SOLANO HUAMAN" => 'EPIFANIO SOLANO HUAMAN',
            "ALFREDO ALEJANDRO GABRIEL MONTALVO" => 'ALFREDO ALEJANDRO GABRIEL MONTALVO',
            "SABINA LACHOS" => 'SABINA LACHOS',
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS',
        ];

        $pagos = Pago::join('users as u', 'pagos.user_id', 'u.id')
            ->join('clientes as c', 'pagos.cliente_id', 'c.id')
            ->select('pagos.id',
                'u.name as users',
                'c.celular', //cliente
                'c.nombre', //cliente
                'pagos.observacion',
                //'pagos.saldo',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at as fecha')
            ->where('pagos.id', $request->pago_id)
            ->groupBy('pagos.id',
                'u.name',
                'c.celular',
                'c.nombre',
                'pagos.observacion',
                //'pagos.saldo',
                'pagos.condicion',
                'pagos.estado',
                'pagos.created_at')
            ->first();

        $pagoPedidos = PagoPedido::join('pedidos as p', 'pago_pedidos.pedido_id', 'p.id')
            ->join('detalle_pedidos as dp', 'p.id', 'dp.pedido_id')
            ->select('pago_pedidos.id',
                /* 'c.celular', //cliente
                'c.nombre', //cliente */
                'dp.codigo',
                'p.id as pedidos',
                'p.condicion',
                'dp.total',
                'pago_pedidos.pagado',
                'pago_pedidos.abono'
            )
            ->where('pago_pedidos.estado', '1')
            ->where('p.estado', '1')
            ->where('dp.estado', '1')
            ->where('pago_pedidos.pago_id', $request->pago_id)
            ->get();

        $detallePagos = DetallePago::
        select('id',
            'monto',
            'banco',
            'imagen',
            'fecha',
            'titular',
            'cuenta',
            'fecha_deposito',
            'observacion')
            ->where('estado', '1')
            ->where('pago_id', $request->pago_id)
            ->get();
        //DB::raw('sum(detalle_pagos.monto) as total')

        return view('pagos.revisarpago', compact('condiciones', 'cuentas', 'titulares', 'pagos', 'pagoPedidos', 'detallePagos', 'hiddenID', 'pago_id'));
    }

    public function updateRevisar(Request $request, Pago $pago)
    {
        /**  idpago  3028  userid 3  clienteid  1232 */
        //return $request->all();
        //return $pago;
        $fecha_aprobacion = Carbon::now()->format('Y-m-d');
        //return $request->all();
        try {
            DB::beginTransaction();

            // ACTUALIZANDO CABECERA PAGOS
            //$condicion = $request->condicion;
            /*  if pagado  a abonado   si adelanto   adelanto aprobado **/
            $condicion = $request->condicion;
            $observacion = $request->observacion;

            $pago->update([
                'condicion' => $condicion,//$condicion,
                'observacion' => $observacion
            ]);

            if ($condicion == Pago::ABONADO) {
                $pago->update([
                    'fecha_aprobacion' => $fecha_aprobacion,
                    'condicion_code' => Pago::ABONADO_CODE
                ]);
            }

            if ($condicion == Pago::OBSERVADO) {
                $pago->update([
                    'condicion_code' => Pago::OBSERVADO_CODE
                ]);
            }

            if ($condicion == Pago::PENDIENTE) {
                $pago->update([
                    'condicion_code' => Pago::PENDIENTE_CODE
                ]);
            }

            //conciliacion


            //INDICADOR DE DEUDA EN CLIENTE
            /* if($condicion == Pago::ABONADO)
            {
                $cliente = Cliente::find($pago->cliente_id);
                $cliente->update([
                        'deuda' => '0',
                    ]);
            } */

            $abono_ = 0.00;
            //return $request->all();
            $pedido_list = $request->pedido_id;
            $pedido_id_abono = $request->pedido_id_abono;
            $contpedido = 0;
            while ($contpedido < count((array)$pedido_list)) {
                $abono_ = $pedido_id_abono[$contpedido];
                if ($abono_ == 0.00) {
                    $pedido_ = Pedido::where("id", $pedido_list[$contpedido]);
                    $pedido_->update([
                        'pago' => 0,
                        'pagado' => 0,
                    ]);
                    $detallepedido_ = DetallePedido::where("id", $pedido_list[$contpedido]);
                    $detallepedido_g = $detallepedido_->first()->total;
                    $detallepedido_->update([
                        'saldo' => $detallepedido_g
                    ]);


                    $pagopedidos_ = PagoPedido::where("pedido_id", $pedido_list[$contpedido])->where("pago_id", $pago->id)->where("abono", "0.00")->first();
                    if ($pagopedidos_ != null) {
                        $pagopedidos_->update([
                            'estado' => "0"
                        ]);
                    }


                }
                $contpedido++;
            }


            $detalle_list = $request->detalle_id;
            $conciliar_list = $request->conciliar;
            $cuenta = $request->cuenta;
            $titular = $request->titular;
            $fecha_deposito = $request->fecha_deposito;
            $cont = 0;

            if($request->condicion==Pago::ABONADO){
                while ($cont < count((array)$conciliar_list)) {
                    $movimiento = MovimientoBancario::where("id", $conciliar_list[$cont]);

                    $movimiento->update([
                        'pago' => 1,
                        'detpago' => $detalle_list[$cont],
                        'cabpago' => $pago->id
                    ]);
                    $cont++;

                }

            }



            /*while ($cont < count((array)$detalle_id)) {

                DetallePago::where('id', $detalle_id[$cont])
                        ->update(array(
                                        'cuenta' => $cuenta[$cont],
                                        'titular' => $titular[$cont],
                                        'fecha_deposito' => $fecha_deposito[$cont],
                                        )
                                );

                $cont++;
            }*/

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        if($request->mover_revisar=='revisar'){
            return redirect()->route('administracion.porrevisar')->with('info', 'actualizado');
        }else if($request->mover_revisar=='observado'){
            return redirect()->route('administracion.observados')->with('info', 'actualizado');
        }else if($request->mover_revisar=='pendiente'){
            return redirect()->route('administracion.pendientes')->with('info', 'actualizado');


            //return redirect()->route('administracion.aprobados')->with('info', 'actualizado');
        }
        //return redirect()->route('administracion.porrevisar'/*,['q1'=>'12/11/2022','q2'=>'15/11/2022']*/)->with('info', 'actualizado');
    }

    public function updateRevisarpost(Request $request)
    {
        $fecha_aprobacion = Carbon::now()->format('Y-m-d');

        try {
            DB::beginTransaction();
            $condicion = $request->condicion;
            $observacion = $request->observacion;
            $pago = Pago::where('pagos.id', $request->hiddenID)->update([
                'condicion' => $condicion,
                'observacion' => $observacion
            ]);
            if ($condicion == Pago::ABONADO) {
                Pago::where('pagos.id', $request->hiddenID)->update([
                    'fecha_aprobacion' => $fecha_aprobacion,
                ]);
            }
            $detalle_id = $request->detalle_id;
            $cuenta = $request->cuenta;
            $titular = $request->titular;
            $fecha_deposito = $request->fecha_deposito;
            $cont = 0;

            while ($cont < count((array)$detalle_id)) {

                DetallePago::where('id', $detalle_id[$cont])
                    ->update(array(
                            'cuenta' => $cuenta[$cont],
                            'titular' => $titular[$cont],
                            'fecha_deposito' => $fecha_deposito[$cont],
                        )
                    );

                $cont++;
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        //return redirect()->route('administracion.porrevisar')->with('info', 'actualizado');
    }

    public function DescargarImagen($imagen)
    {
        $destinationPath = base_path("public/storage/pagos/" . $imagen);
        /* $destinationPath = storage_path("app/public/adjuntos/".$pedido->adjunto); */

        return response()->download($destinationPath);
    }

    public function DescargarImgDevolucion(Request $request)
    {
      $destinationPath = base_path("storage/app/public/" . $request->imagen);
      return response()->download($destinationPath);
    }

    public function perdonardeuda(Request $request)
    {
        //return $request->all();
        $pedidos = $request->pedidos;
        if (!$request->pedidos) {
            return '0';
        } else {
            $array_pedidos = explode(",", $pedidos);

            $rol = Auth::user()->rol;

            $user_id = null;

            try {
                DB::beginTransaction();

                $cliente_perdondarcourier = Cliente::query()->where('nombre', 'COURIER PERDONADO')->first();


                $pago = Pago::create([
                    'user_id' => $cliente_perdondarcourier->user_id,
                    'cliente_id' => $cliente_perdondarcourier->id,
                    'total_cobro' => '0',
                    'total_pagado' => '0',
                    'condicion' => Pago::PAGO,
                    'notificacion' => 'Nuevo pago registrado',
                    'estado' => '1',
                    'observacion' => $request->observacion,
                    'subcondicion' => 'COURIER PERDONADO'
                ]);

                $saldos = 0.00;

                foreach ($array_pedidos as $pedido_id) {
                    $pedido = Pedido::find($pedido_id);

                    $pedido->update([
                        'pagado' => '2',
                    ]);

                    $detallePedido = DetallePedido::where("pedido_id", $pedido_id)->first();

                    $saldos = $saldos + ($detallePedido->saldo * 1);

                    $pagoPedido = PagoPedido::create([
                        'pago_id' => $pago->id,
                        'pedido_id' => $pedido_id,
                        'abono' => ($detallePedido->saldo),
                        'estado' => '1',
                        'pagado' => '2'
                    ]);

                    $detallePedido->update([
                        'saldo' => '0'
                    ]);

                }

                DetallePago::create([
                    'pago_id' => $pago->id,
                    'cuenta' => '',
                    'titular' => '',
                    'monto' => $saldos,
                    'banco' => '',
                    'bancop' => '',
                    'obanco' => '',
                    'fecha' => Carbon::now(),
                    'fecha_deposito' => Carbon::now(),
                    'imagen' => '',
                    'estado' => '1'
                ]);
                $identi_asesor = User::where("id", $cliente_perdondarcourier->user_id)->where("unificado", "NO")->first();
                $fecha_created=Carbon::parse($pago->created_at);
                $dd=$fecha_created->format('d');
                $mm=$fecha_created->format('m');
                $unido=$dd.$mm;
                $valorcorrelativo='PAG'.$identi_asesor->identificador.'-'.$unido.'-'.$pago->id;
                $pago->update([
                    "correlativo" => 'PAG'.$identi_asesor->identificador.'-'.$unido.'-'.$pago->id
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }

            return response()->json(['html' => $pago->id,'pagos' => $pago,'valorcorrelativo'=>$valorcorrelativo]);


        }

    }

    public function TitularesBanco(Request $request)
    {
        $titulares_a = null;
        if (!$request->banco) {

        } else {

            $titulares_a = CuentaBancaria::join('titulares as t', 't.id', 'cuenta_bancarias.titular')
                ->join('entidad_bancarias as b', 'b.id', 'cuenta_bancarias.banco')
                ->where('cuenta_bancarias.estado', '1')
                ->where('cuenta_bancarias.tipo', 'AHORROS')
                ->where('b.nombre', $request->banco)
                ->select(
                    't.nombre',
                    'cuenta_bancarias.numero'
                );


        }
        $titulares_a = $titulares_a->get();

        return response()->json(['html' => $titulares_a]);

    }

    public function devolucion(Request $request, Devolucion $devolucion)
    {
        $devolucion->load([
            'cliente',
            'pago',
            'asesor.asesoroperario',
            'asesor.encargado',
        ]);
        $bancos = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            "SCOTIABANK" => 'SCOTIABANK',
            "PICHINCHA" => 'PICHINCHA',
        ];

        /*if ($devolucion->status == Devolucion::DEVUELTO) {
            return redirect()->route("pagos.show", $devolucion->pago);
        }*/
        if ($request->has("read_notification")) {
            $notification = DatabaseNotification::query()->find($request->get('read_notification'));
            if ($notification != null) {
                $notification->markAsRead();
            }
        }

        $pagoPedidos = PagoPedido::join('pedidos as p', 'pago_pedidos.pedido_id', 'p.id')
            ->join('detalle_pedidos as dp', 'p.id', 'dp.pedido_id')
            ->select('pago_pedidos.id',
                'dp.codigo',
                'p.id as pedidos',
                'p.condicion',
                'dp.total',
                'pago_pedidos.pagado',
                'pago_pedidos.abono'
            )
            ->where('pago_pedidos.estado', '1')
            ->where('p.estado', '1')
            ->where('dp.estado', '1')
            ->where('pago_pedidos.pago_id', $devolucion->pago->id)
            ->get();

        $detallePagos = DetallePago::select('id',
            'monto',
            'banco',
            'imagen',
            'fecha',
            'titular',
            'cuenta',
            'fecha_deposito',
            'observacion')
            ->where('estado', '1')
            ->where('pago_id', $devolucion->pago->id)
            ->get();
        return view('pagos.devolucion.index', compact('devolucion', 'bancos', 'pagoPedidos', 'detallePagos'));
    }

    public function devolucionUpdate(Request $request, Devolucion $devolucion)
    {

        $this->validate($request, [
            'voucher' => 'required|file|image',
            //"bank_destino" => 'required',
            // "bank_number" => 'required',
            "num_operacion" => 'nullable',
        ]);

        $devolucion->load([
            'cliente',
            'pago',
            'asesor.asesoroperario',
            'asesor.encargado',
        ]);

        DB::transaction(function () use ($request, $devolucion) {
            $path = $request->file('voucher')->store('pagos_devolucion', 'pstorage');
            $devolucion->update([
                //"bank_destino" => $request->bank_destino,
                //"bank_number" => $request->bank_number,
                "num_operacion" => $request->num_operacion,
                "voucher_path" => $path,
                "status" => Devolucion::DEVUELTO,
                "returned_at" => now(),
            ]);
            /*$asesores = User::whereIn('rol', [User::ROL_ASESOR, User::ROL_LLAMADAS])
                ->activo()
                ->get();

            foreach ($asesores as $asesor) {
                $asesor->notify(new DevolucionApproved($devolucion));
            }*/
        });
        return redirect()->route("pagos.show", $devolucion);
    }

    public function validadContenidoPago(Request $request)
    {
        // $request->all();
        //return $request->titular;
        $titular_c=trim($request->titular);
        $fecha_c=trim($request->fecha);
        $monto_c=trim($request->monto);
        $monto_c=str_replace(',','',$monto_c);

        $pago_ids=Pago::query()->distinct()->select('pagos.id')
            ->where('pagos.estado','=', '1')
            ->join('users as u', 'u.id',  'pagos.user_id')
            ->where('cliente_id', $request->cliente_id)->get();
        //return $pago_ids;
        //return $titular_c;
        //return $request->fecha;
        $pagos_repetidos = DetallePago::
            //->with(['pago', 'pago.user'])
            where('banco', $request->banco)
            ->where('titular', $request->titular)
            ->where('detalle_pagos.monto', $monto_c)
            ->where('detalle_pagos.fecha', $fecha_c)//2023-01-18
            ->whereIn('pago_id',$pago_ids)
            ->limit(5)
            ->get();

        //return $pagos_repetidos;

        return response()->json([
            'codigos' => $pagos_repetidos->map(function ($dp) {
                return $dp->pago->code_id;
            })->join(', '),
            'is_repetido' => $pagos_repetidos->count() > 0,
            'coincidencia' => $pagos_repetidos,
        ]);
    }

    public  function getDataPagoReciente(Request $request){
        /*return $request->all();*/
        $detalle_pagos=DetallePago::join('pedidos as pe','detalle_pagos.pago_id','pe.id')
        ->join('clientes as cl','pe.cliente_id','cl.id')
        ->select(['detalle_pagos.*'])
        ->where('cl.id',$request->cliente_id)
        ->orderby('detalle_pagos.created_at','DESC')
        ->limit(1)
        ->get();
        return response()->json(['det_pagos' => $detalle_pagos]);
    }
}
