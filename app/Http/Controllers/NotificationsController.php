<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Correction;
use App\Models\DetalleContactos;
use App\Models\Devolucion;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use App\View\Components\common\courier\AutorizarRutaMotorizado;
use Carbon\Carbon;
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

        $notifications = [
            [
                'icon' => 'fas fa-fw fa-envelope',//'fas fa-bell',
                'text' => count(auth()->user()->unreadNotifications) . ' nuevas notificaciones',
                'time' => rand(0, 10) . ' minutes',
            ],
        ];

        $dropdownHtml = '';
        if (\Auth::check()) {
            if (\Auth::user()->rol == User::ROL_ADMIN) {
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



        $contador_correcciones = Correction::join('users as u','u.id','corrections.asesor_id')
            ->where('corrections.estado', 1)
            ->where('corrections.condicion_envio_code', Pedido::CORRECCION_OPE_INT);

        if (Auth::user()->rol == "Operario") {
            $asesores = User::whereIN('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $contador_correcciones=$contador_correcciones->WhereIn('u.identificador', $asesores);
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
            $contador_correcciones=$contador_correcciones->WhereIn('u.identificador', $asesores);
        }

        $en_motorizados_count = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
            ->where('direccion_grupos.estado', '1');
        if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
            $en_motorizados_count = $en_motorizados_count->where('direccion_grupos.motorizado_id', '=', auth()->id());
        }



        $sobres_devueltos = Pedido::join('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->select([
                'pedidos.*',
                'direccion_grupos.fecha_salida as grupo_fecha_salida',
                'direccion_grupos.motorizado_status',
                'direccion_grupos.motorizado_sustento_text',
                'direccion_grupos.motorizado_sustento_foto',
            ])
            ->whereIn('direccion_grupos.motorizado_status', [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO, Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO])
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


        $alertas=Alerta::noFinalize()
            ->noReadTime(now()->subMinutes(10))
            ->withCurrentUser()->get()->filter(fn(Alerta $alerta) => ($alerta->date_at == null || Carbon::parse($alerta->date_at)->subHour() <= now()))->values();
        return [
            'icon' => 'fas fa-envelope',
            'label' => '0',
            'label_color' => 'danger',
            'icon_color' => 'white',
            'dropdown' => $dropdownHtml,
            'contador_pedidos_atender' => 0,
            'contador_pedidos_atendidos' => 0,
            'contador_pedidos_atendidos_operacion' => 0,
            'contador_pedidos_pen_anulacion' => 0,
            'contador_sobres_entregados' => 0,
            'contador_correcciones' => 0,
            'contador_sobres_confirmar_recepcion' => 0,
            'contador_sobres_confirmar_recepcion_motorizado' => 0,
            'contador_jefe_op' => 0,
            'contador_en_motorizados_count' => 0,
            'contador_en_motorizados_confirmar_count' => 0,
            'contador_sobres_devueltos' => 0,
            'contador_encargado_tienda_agente' => 0,
            'contador_contactos_registrados' => 0,
            'authorization_courier' => \Blade::renderComponent(new AutorizarRutaMotorizado()),
            'alertas' => $alertas,
        ];
    }

    public function index()
    {
        $postNotifications = auth()->user()->unreadNotifications;
        $devoluciones = [];
        if (\Auth::check()) {
            if (\Auth::user()->rol == User::ROL_ADMIN) {
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
