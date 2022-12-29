<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
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
        $contador_pedidos_atender = Pedido::where('condicion_code',1)->count();

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
            ->poratenderestatus();

            if(Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO){

                $asesores = User::whereIn('users.rol', [User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

                $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);

            }
            if(Auth::user()->rol == User::ROL_ASESOR){

                $asesores = User::whereIn('users.rol', [User::ROL_ASESOR])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

                $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);

            }else if(Auth::user()->rol == User::ROL_OPERARIO){

                $asesores = User::whereIn('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                    ->where('users.estado', '1')
                    ->Where('users.operario', Auth::user()->id)
                    ->select(
                        DB::raw("users.identificador as identificador")
                    )
                    ->pluck('users.identificador');

                $contador_pedidos_atender = $contador_pedidos_atender->WhereIn('u.identificador', $asesores);


            }else if(Auth::user()->rol == User::ROL_LLAMADAS){

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


            } else {
                $contador_pedidos_atender = $contador_pedidos_atender;

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
            ->whereNotIn('pedidos.condicion_code', [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT]);

        if(Auth::user()->rol == User::ROL_ASESOR){

                $asesores = User::whereIn('users.rol', [User::ROL_ASESOR])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

                $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);

        }else if(Auth::user()->rol == User::ROL_OPERARIO){

            $asesores = User::whereIN('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos = $contador_pedidos_atendidos->WhereIn('u.identificador', $asesores);


        }else if(Auth::user()->rol == User::ROL_LLAMADAS){

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


        } else {
            $contador_pedidos_atendidos = $contador_pedidos_atendidos;
        }

        $contador_pedidos_atendidos = $contador_pedidos_atendidos->count();

        /*********
         * PEDIDOS ATENDIDOS
         */

        // Estado de pediddos
        $contador_pedidos_atendidos_operacion = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion_code', Pedido::ATENDIDO_INT)
            ->where('pedidos.envio', 0);

        if(Auth::user()->rol == User::ROL_ASESOR){

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);

        }else if(Auth::user()->rol == User::ROL_OPERARIO){

            $asesores = User::whereIN('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);


        }else if(Auth::user()->rol == User::ROL_LLAMADAS){

            $asesores = User::whereIn('users.rol', [User::ROL_ASESOR, User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->Where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);


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

            $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->WhereIn('u.identificador', $asesores);


        } else {
            $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion;
        }

        $contador_pedidos_atendidos_operacion = $contador_pedidos_atendidos_operacion->count();
        /************
         * Pedidos pendientes de anulación
         */

        /*
        $contador_pedidos_atendidos = Pedido::where('estado', '1')
            ->where('condicion_envio_code', Pedido::ATENDIDO_INT)
            ->count(); */

        $contador_pedidos_pen_anulacion = Pedido::where('pendiente_anulacion',1)
            ->where('estado',1)
            ->count();

        $contador_jefe_op = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion_code', Pedido::ATENDIDO_INT)
            ->whereIn('pedidos.condicion_envio_code', [Pedido::ATENDIDO_JEFE_OPE_INT, Pedido::ENTREGADO_SIN_SOBRE_OPE_INT])
            ->count();

        $contador_sobres_entregados = Pedido::where('estado',1)
            ->where('condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT)
            ->count();

        return [
            'icon' => 'fas fa-envelope',
            'label' => count(auth()->user()->unreadNotifications) + count($devoluciones),
            'label_color' => 'danger',
            'icon_color' => 'white',
            'dropdown' => $dropdownHtml,
            'contador_pedidos_atender' => $contador_pedidos_atender,
            'contador_pedidos_atendidos'=>$contador_pedidos_atendidos,
            'contador_pedidos_atendidos_operacion' => $contador_pedidos_atendidos_operacion,
            'contador_pedidos_pen_anulacion' => $contador_pedidos_pen_anulacion,
            'contador_sobres_entregados' => $contador_sobres_entregados,
            'contador_jefe_op' => $contador_jefe_op


        ];
    }

    public function index()
    {
        $postNotifications = auth()->user()->unreadNotifications;
        $devoluciones=[];
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
