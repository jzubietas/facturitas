<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use App\View\Components\common\courier\AutorizarRutaMotorizado;
use Illuminate\Http\Request;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Message;
use App\Notifications\NewMessage;

class NotificationsController extends Controller
{
    /**
     * Get the new notification data for the navbar notification.
     *
     * @param Request $request
     * @return Array
     */

    public function getNotificationsPedidosAtender(Request $request)
    {
        $contador_pedidos_atender = Pedido::where('condicion_code', 1)->count();

        return [
            'icon' => 'fas fa-envelope',
            'label' => $contador_pedidos_atender,
            'label_color' => 'danger',
            'icon_color' => 'white',
            'dropdown' => '',
        ];
    }

    public function getNotificationsData(Request $request)
    {
        // For the sake of simplicity, assume we have a variable called
        // $notifications with the unread notifications. Each notification
        // have the next properties:
        // icon: An icon for the notification.
        // text: A text for the notification.
        // time: The time since notification was created on the server.
        // At next, we define a hardcoded variable with the explained format,
        // but you can assume this data comes from a database query.

        $notifications = [
            [
                'icon' => 'fas fa-fw fa-envelope',//'fas fa-bell',
                'text' => count(auth()->user()->unreadNotifications) . ' nuevas notificaciones',
                'time' => rand(0, 10) . ' minutes',
            ],
        ];

        // Now, we create the notification dropdown main content.

        $dropdownHtml = '';
        $devoluciones = [];
        if (\Auth::check()) {
            if (\Auth::user()->rol == \App\Models\User::ROL_ADMIN) {
                $devoluciones = Devolucion::query()->with(['cliente', 'pago', 'asesor'])->noAtendidos()->orderByDesc('created_at')->get();
                foreach ($devoluciones as $key => $devolucion) {
                    $icon = "<i class='mr-2 fas fa-fw fa-envelope'></i>";

                    $time = "<span class='float-right text-muted text-sm'>
                       {$devolucion->created_at->diffForHumans()}
                     </span>";

                    $dropdownHtml .= "<a href='" . route('pagos.devolucion', $devolucion) . "' class='dropdown-item'>
                             {$icon}
                             <span class='text-wrap'>
                              Pago por devolver a <b>{$devolucion->cliente->nombre}</b> un valor de <b>{$devolucion->amount_format}</b>
</span>
                             {$time}
                              </a>";

                    if ($key < count($devoluciones) - 1) {
                        $dropdownHtml .= "<div class='dropdown-divider'></div>";
                    }
                }
            }
        }

        /* foreach ($notifications as $key => $not) {
            $icon = "<i class='mr-2 {$not['icon']}'></i>";

            $time = "<span class='float-right text-muted text-sm'>
                       {$not['time']}
                     </span>";

            $dropdownHtml .= "<a href='#' class='dropdown-item'>
                                {$icon}{$not['text']}{$time}
                              </a>";

            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        } */

        foreach (auth()->user()->unreadNotifications as $key => $not) {
            $icon = "<i class='mr-2 fas fa-fw fa-envelope'></i>";

            $time = "<span class='float-right text-muted text-sm'>
                       {$not['created_at']->diffForHumans()}
                     </span>";

            $dropdownHtml .= "<a href='/notifications' class='dropdown-item'>
                                {$icon}{$not['data']['asunto']}{$time}
                              </a>";

            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }

        /*********
         * PEDIDOS POR ATENDER
         */
        $contador_pedidos_atender = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->whereIn('pedidos.condicion_envio_code', [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT])
            ->poratenderestatus();

        if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);

        }
        if (Auth::user()->rol == User::ROL_ASESOR) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == User::ROL_OPERARIO) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == User::ROL_LLAMADAS) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == User::ROL_JEFE_OPERARIO) {
            $operarios = User::where('users.rol', User::ROL_OPERARIO)
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::whereIN('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);


        }

        $contador_pedidos_atender = $contador_pedidos_atender->count();

        /*********
         * PEDIDOS ATENDIDOS
         */

        // Estado de pediddos
        $contador_pedidos_atendidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            //->atendidos()
            ->noPendingAnulation()
            ->where('da_confirmar_descarga', '0')
            ->whereNotIn('pedidos.condicion_envio_code', [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT]);

        if (Auth::user()->rol == User::ROL_ASESOR) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == User::ROL_OPERARIO) {

            $asesores = User::whereIN('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == User::ROL_LLAMADAS) {

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == User::ROL_JEFE_OPERARIO) {
            $operarios = User::where('users.rol', User::ROL_OPERARIO)
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::whereIN('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);


        }

        $contador_pedidos_atendidos = $contador_pedidos_atendidos->count();

        /*********
         * PEDIDOS ATENDIDOS OPERACIONES
         */

        // Estado de pediddos operaciones
        $contador_pedidos_atendidos_operacion = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'pedidos.correlativo as id2',
                'u.identificador as users',
                'dp.codigo as codigos',
                'dp.nombre_empresa as empresas',
                'pedidos.condicion',
                'pedidos.condicion_code',
                'pedidos.da_confirmar_descarga',
                DB::raw('(DATE_FORMAT(pedidos.created_at, "%Y-%m-%d %h:%i:%s")) as fecha'),
                'pedidos.envio',
                'pedidos.destino',
                'pedidos.condicion_envio',
                'dp.envio_doc',
                DB::raw('(DATE_FORMAT(dp.fecha_envio_doc, "%Y-%m-%d %h:%i:%s")) as fecha_envio_doc'),
                'dp.cant_compro',
                'dp.atendido_por',
                //'u.jefe',
                DB::raw(" (select u2.name from users u2 where u2.id=u.jefe) as jefe "),
                DB::raw('DATE_FORMAT(dp.fecha_envio_doc_fis, "%d/%m/%Y") as fecha_envio_doc_fis'),
                'dp.fecha_recepcion',
                DB::raw(" (select count(ii.id) from imagen_atencions ii where ii.pedido_id=pedidos.id and ii.estado=1) as adjuntos ")
            )
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion_envio_code', Pedido::ATENDIDO_OPE_INT);
        //->where('pedidos.envio', 0);


        if (Auth::user()->rol == "Operario") {

            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('users.rol', 'Operario')
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);


        }

        $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->count();


        /************
         * Pedidos pendientes de anulación
         */

        /*
        $contador_pedidos_atendidos = Pedido::where('estado', '1')
            ->where('condicion_envio_code', Pedido::ATENDIDO_INT)
            ->count(); */

        $contador_pedidos_pen_anulacion = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pendiente_anulacion', 1)
            ->where('pedidos.estado', 1);

        if (Auth::user()->rol == "Operario") {

            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_pen_anulacion = $contador_pedidos_pen_anulacion->WhereIn('u.identificador', $asesores);


        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('users.rol', 'Operario')
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_pen_anulacion = $contador_pedidos_pen_anulacion->WhereIn('u.identificador', $asesores);


        }

        $contador_pedidos_pen_anulacion = $contador_pedidos_pen_anulacion->count();

        $contador_jefe_op = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion_code', Pedido::ATENDIDO_INT)
            ->whereIn('pedidos.condicion_envio_code', [Pedido::ENVIADO_OPE_INT, Pedido::ENTREGADO_SIN_SOBRE_OPE_INT])
            ->count();

        $contador_sobres_entregados = Pedido::where('estado', 1)
            ->where('condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT)
            ->count();

        $contador_sobres_confirmar_recepcion_motorizado = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.condicion_envio_code', Pedido::ENVIO_MOTORIZADO_COURIER_INT)
            ->activo()
            ->count();

        $contador_sobres_confirmar_recepcion = Pedido::where('estado', 1)
            //->join('users as u', 'u.id', 'c.user_id')
            ->where('condicion_envio_code', Pedido::ENVIO_COURIER_JEFE_OPE_INT)
            ->count();


        $en_motorizados_count = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
            ->where('direccion_grupos.estado', '1');
        if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
            $en_motorizados_count = $en_motorizados_count->where('direccion_grupos.motorizado_id', '=', auth()->id());
        }

        $en_motorizados_confirmar_count = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_MOTORIZADO_INT)
            ->where('direccion_grupos.estado', '1');

        $sobres_devueltos = Pedido::join('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->select([
                'pedidos.*',
                'direccion_grupos.fecha_salida as grupo_fecha_salida',
                'direccion_grupos.motorizado_status',
                'direccion_grupos.motorizado_sustento_text',
                'direccion_grupos.motorizado_sustento_foto',
            ])
            ->whereIn('direccion_grupos.motorizado_status', [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO, Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO])
            //->where('direccion_grupos.estado', '1')
            //->activo()
            ->whereNotNull('direccion_grupos.fecha_salida')
            ->count();
        if ($sobres_devueltos > 0) {
            $icono_sobres_devueltos = "fa fa-exclamation-triangle text-warning warning";
        } else {
            $icono_sobres_devueltos = "";
        }


        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'clientes.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            ])
            ->whereNull('direccion_grupos.courier_failed_sync_at')
            ->where('direccion_grupos.distribucion', 'OLVA')
            ->where('direccion_grupos.motorizado_status', '0')
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
            ]);
        if (user_rol(User::ROL_ASESOR) || user_rol(User::ROL_ASESOR_ADMINISTRATIVO)) {
            $pedidos_provincia->whereNull('direccion_grupos.add_screenshot_at');
        }
        add_query_filtros_por_roles_pedidos($pedidos_provincia, 'users.identificador');
        $contador_encargado_tienda_agente =$pedidos_provincia->count();

        return [
            'icon' => 'fas fa-envelope',
            'label' => count(auth()->user()->unreadNotifications) + count($devoluciones),
            'label_color' => 'danger',
            'icon_color' => 'white',
            'dropdown' => $dropdownHtml,
            'contador_pedidos_atender' => $contador_pedidos_atender,
            'contador_pedidos_atendidos' => $contador_pedidos_atendidos,
            'contador_pedidos_atendidos_operacion' => $contador_pedidos_atendidos_operacion,
            'contador_pedidos_pen_anulacion' => $contador_pedidos_pen_anulacion,
            'contador_sobres_entregados' => $contador_sobres_entregados,
            'contador_sobres_confirmar_recepcion' => $contador_sobres_confirmar_recepcion,
            'contador_sobres_confirmar_recepcion_motorizado' => $contador_sobres_confirmar_recepcion_motorizado,
            'contador_jefe_op' => $contador_jefe_op,
            'contador_en_motorizados_count' => $en_motorizados_count->count(),
            'contador_en_motorizados_confirmar_count' => $en_motorizados_confirmar_count->count(),
            'contador_sobres_devueltos' => $icono_sobres_devueltos,
            'contador_encargado_tienda_agente' => $contador_encargado_tienda_agente,
            'authorization_courier' => \Blade::renderComponent(new AutorizarRutaMotorizado())
        ];
    }

    public function index()
    {
        $postNotifications = auth()->user()->unreadNotifications;
        $devoluciones = [];
        if (\Auth::check()) {
            if (\Auth::user()->rol == \App\Models\User::ROL_ADMIN) {
                $devoluciones = Devolucion::query()->with(['cliente', 'pago', 'asesor'])->noAtendidos()->get();
            }
        }
        //return $devoluciones;
        return view('notifications.index', compact('postNotifications', 'devoluciones'));
    }

    public function markNotification(Request $request)
    {
        auth()->user()->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })->markAsRead();
        return response()->noContent();
    }
}
