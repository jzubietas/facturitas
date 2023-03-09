<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AnulacionController extends Controller
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

    public function pedidosanulaciones()
    {
        return view('pedidos.anulaciones.index');
    }
    public function indexanulacionestabla(Request $request)
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
            ->where('pedidos.estado',1)
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
}
