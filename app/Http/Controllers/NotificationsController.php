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
        $devoluciones = [];
        if (\Auth::check()) {
            if (\Auth::user()->rol == User::ROL_ADMIN) {
              $devoluciones = Devolucion::query()->with(['cliente', 'pago', 'asesor'])->noAtendidos()->orderByDesc('created_at')->get();
                foreach ($devoluciones as $key => $devolucion) {
                    $icon = "<i class='mr-2 fas fa-fw fa-envelope text-danger'></i>";
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
            }elseif (\Auth::user()->rol == User::ROL_ASESOR){
              $devoluciones = Devolucion::query()
                ->with(['cliente', 'pago', 'asesor'])
                ->devueltos()
                ->where('asesor_id',\Auth::user()->id)
                ->orderByDesc('created_at')->get();
              foreach ($devoluciones as $key => $devolucion) {
                $icon = "<i class='mr-2 fas fa-fw fa-envelope-open text-primary'></i>";
                $time = "<span class='float-right text-muted text-sm'>
                       {$devolucion->created_at->diffForHumans()}
                     </span>";

                $dropdownHtml .= "<a href='" . route('pagos.devolucion', $devolucion) . "' class='dropdown-item'>
                                         {$icon}
                                           <span class='text-wrap'>
                                              Pago devuelto a <b>{$devolucion->cliente->nombre}</b> un valor de <b>{$devolucion->amount_format}</b>
                                          </span>
                                         {$time}
                                       </a>";
                if ($key < count($devoluciones) - 1) {
                  $dropdownHtml .= "<div class='dropdown-divider'></div>";
                }
              }
            }elseif (\Auth::user()->rol == User::ROL_ENCARGADO){
              $encargado=User::where('rol',User::ROL_ENCARGADO)->activo()->where('id',\Auth::user()->id)->first();
              $asesores=User::where('rol',User::ROL_ASESOR)->activo()->where('supervisor',$encargado->id)->select(
                DB::raw("users.id as id")
              )->pluck('users.id');
              $devoluciones = Devolucion::query()
                ->with(['cliente', 'pago', 'asesor'])
                ->devueltos()
                ->whereIn('asesor_id',$asesores)
                ->orderByDesc('created_at')->get();
              foreach ($devoluciones as $key => $devolucion) {
                if ($key<=3){
                  $icon = "<i class='mr-2 fas fa-fw fa-envelope-open text-primary'></i>";
                  $time = "<span class='float-right text-muted text-sm'>
                       {$devolucion->created_at->diffForHumans()}
                     </span>";

                  $dropdownHtml .= "<a href='" . route('pagos.devolucion', $devolucion) . "' class='dropdown-item'>
                                         {$icon}
                                           <span class='text-wrap'>
                                              Pago devuelto a <b>{$devolucion->cliente->nombre}</b> un valor de <b>{$devolucion->amount_format}</b>
                                          </span>
                                         {$time}
                                       </a>";
                  if ($key < count($devoluciones) - 1) {
                    $dropdownHtml .= "<div class='dropdown-divider'></div>";
                  }
                }
              }
            }
        }


        foreach (auth()->user()->unreadNotifications as $key => $not) {
            $icon = "<i class='mr-2 fas fa-fw fa-envelope text-success'></i>";
            $time = "<span class='float-right text-muted text-smfloat-right text-muted text-sm'>
                       {$not['created_at']->diffForHumans()}
                     </span>";

            $dropdownHtml .= "<a href='/notifications' class='dropdown-item'>
                                {$icon}
                                <span class='text-wrap'>
                                    {$not['data']['asunto']}
                                </span>
                                {$time}
                              </a>";
            /*if ($key < count($not) - 1) {*/
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            /*}*/
        }

      /*********
       * PEDIDOS POR ATENDER
       */
      $contador_pedidos_atender = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
        ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
        ->where('pedidos.estado', '1')
        ->where('dp.estado', '1')
        ->whereIn('pedidos.condicion_envio_code', [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT])
         ->whereNotIn('pedidos.condicion', [Pedido::EN_PROCESO_ATENCION])
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
      else if (Auth::user()->rol == User::ROL_ASESOR) {

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
         * PEDIDOS ATENCION
         */
        $pedidos_atencion = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->where('pedidos.estado', '1')
            ->where('dp.estado', '1')
            ->where('pedidos.condicion', Pedido::EN_PROCESO_ATENCION);
        if (Auth::user()->rol == User::ROL_OPERARIO) {
            $asesores_atencion = User::where('users.rol', User::ROL_ASESOR )
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos_atencion=$pedidos_atencion->WhereIn('pedidos.user_id', $asesores_atencion);
        } else if (Auth::user()->rol == User::ROL_JEFE_OPERARIO) {
            $operario_atencions = User::where('users.rol', User::ROL_OPERARIO)
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores_atencion = User::where('users.rol', User::ROL_ASESOR)
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operario_atencions)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $pedidos_atencion=$pedidos_atencion->WhereIn('pedidos.user_id', $asesores_atencion);
        }
        $contador_pedidos_atencion=$pedidos_atencion->count();
      /*********
       * PEDIDOS ATENDIDOS
       */

      // Estado de pediddos
      $contador_pedidos_atendidos = Pedido::join('users as u', 'pedidos.user_id', 'u.id')
        ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
        ->activo()->segunRolUsuario([User::ROL_ADMIN, User::ROL_ENCARGADO, User::ROL_ASESOR, User::ROL_LLAMADAS, User::ROL_JEFE_LLAMADAS])
        ->noPendingAnulation()
          ->where('da_confirmar_descarga', 0)
          ->whereNotIn('pedidos.condicion_envio_code', [Pedido::POR_ATENDER_OPE_INT, Pedido::EN_ATENCION_OPE_INT,Pedido::CORRECCION_OPE_INT]);


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
          ['pedidos.id',
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
          ])
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

      $contador_correcciones=$contador_correcciones->count();

      $contador_sobres_confirmar_recepcion = Pedido::where('estado', 1)
        ->where('condicion_envio_code', Pedido::ENVIO_COURIER_JEFE_OPE_INT)
        ->count();
      $en_motorizados_confirmar_count = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
        ->join('users as u', 'u.id', 'c.user_id')
        ->whereIn('direccion_grupos.condicion_envio_code', [Pedido::CONFIRM_MOTORIZADO_INT,Pedido::RECIBIDO_RECOJO_CLIENTE_INT])
        ->where('direccion_grupos.estado', '1');
      $en_motorizados_confirmar_count=$en_motorizados_confirmar_count->count();

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
      $contador_encargado_tienda_agente =$pedidos_provincia->count();
        //=========================================================================================================================
      $contadorContactosRegistrados = DetalleContactos::join('clientes as c', 'detalle_contactos.codigo_cliente', 'c.id')
        ->join('users as u', 'c.user_id', 'u.id')
        ->whereIn('guardado', [0, 1])
      ->whereIn('confirmado', [0, 1])
      ->where('reconfirmado', 0);

      if (Auth::user()->rol == "Llamadas") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.llamada', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');
        $contadorContactosRegistrados = $contadorContactosRegistrados->WhereIn("u.identificador", $usersasesores);


      } else if (Auth::user()->rol == "Jefe de llamadas") {
        /*$usersasesores = User::where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->where('users.llamada', Auth::user()->id)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');

        $contadorContactosRegistrados = $contadorContactosRegistrados->WhereIn("u.identificador", $usersasesores);*/
      } elseif (Auth::user()->rol == "Asesor") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.identificador', Auth::user()->identificador)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');
        $contadorContactosRegistrados = $contadorContactosRegistrados->WhereIn("u.identificador", $usersasesores);

      } else if (Auth::user()->rol == "Encargado") {
        $usersasesores = User::where('users.rol', 'Asesor')
          ->where('users.estado', '1')
          ->where('users.supervisor', Auth::user()->id)
          ->select(
            DB::raw("users.identificador as identificador")
          )
          ->pluck('users.identificador');

        $contadorContactosRegistrados = $contadorContactosRegistrados->WhereIn("u.identificador", $usersasesores);
      } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
        //$asesorB=User::activo()->where('identificador','=','B')->pluck('id')
        $contadorContactosRegistrados = $contadorContactosRegistrados->Where("u.identificador", '=', 'B');
      }

      $contadorContactosRegistrados=$contadorContactosRegistrados->count();

        $alertas=Alerta::noFinalize()
            ->noReadTime(now()->subMinutes(10))
            ->withCurrentUser()->get()->filter(fn(Alerta $alerta) => ($alerta->date_at == null || Carbon::parse($alerta->date_at)->subHour() <= now()))->values();
        return [
            'icon' => 'fas fa-envelope',
            'label' => count(auth()->user()->unreadNotifications) + count($devoluciones),
            'label_color' => 'danger',
            'icon_color' => 'white',
            'dropdown' => $dropdownHtml,
            'contador_pedidos_atender' => $contador_pedidos_atender, //Pedidos /Pedidos por Atender -Operaciones/Pedidos por Atender
            'contador_pedidos_atencion' => $contador_pedidos_atencion, //Pedidos Atencion
            'contador_pedidos_atendidos' => $contador_pedidos_atendidos,//Pedidos /Pedidos Atendidos
            'contador_pedidos_atendidos_operacion' => $contador_pedidos_atendidos_operacion, //Operaciones/Pedidos listo para envio
            'contador_pedidos_pen_anulacion' => $contador_pedidos_pen_anulacion, //Operaciones/Pendiente Anulacion
            'contador_sobres_entregados' => 0, //No se usa
            'contador_correcciones' => $contador_correcciones,//Operaciones /Bandeja de correcciones
            'contador_sobres_confirmar_recepcion' => $contador_sobres_confirmar_recepcion, //Courier/Recepción de sobres
            'contador_sobres_confirmar_recepcion_motorizado' => 0,//No se usa
            'contador_jefe_op' => 0,//No se usa
            'contador_en_motorizados_count' => 0,//No se usa
            'contador_en_motorizados_confirmar_count' => $en_motorizados_confirmar_count, //Courier/Confirmar foto
            'contador_sobres_devueltos' => $icono_sobres_devueltos, //Motorizado/Sobres por Devolver
            'contador_encargado_tienda_agente' => $contador_encargado_tienda_agente, //Pedidos/Olva Tienda/Agente
            'contador_contactos_registrados' => $contadorContactosRegistrados,//NavBar / Listado Clientes
            'authorization_courier' => \Blade::renderComponent(new AutorizarRutaMotorizado()),
            'alertas' => $alertas,
        ];
    }

    public function index()
    {
        //$postNotifications = auth()->user()->unreadNotifications;
        $postNotifications=[];
        $devoluciones = [];
        if (\Auth::check()) {
            if (\Auth::user()->rol == User::ROL_ADMIN) {
                $devoluciones = Devolucion::query()->with(['cliente', 'pago', 'asesor'])->noAtendidos()->get();
            }else if (\Auth::user()->rol == User::ROL_ASESOR) {
              $devoluciones = Devolucion::query()->with(['cliente', 'pago', 'asesor'])
                ->devueltos()
                ->where('asesor_id',\Auth::user()->id)
                ->orderByDesc('created_at')->get();
            }else if (\Auth::user()->rol == User::ROL_ENCARGADO) {
              $encargados=User::where('rol',User::ROL_ENCARGADO)->activo()->where('id',\Auth::user()->id)->first();
              $asesores=User::where('rol',User::ROL_ASESOR)->activo()->where('supervisor',$encargados->id)->select(
                DB::raw("users.id as id")
              )->pluck('users.id');
              $devoluciones = Devolucion::query()
                ->with(['cliente', 'pago', 'asesor'])
                ->devueltos()
                ->whereIn('asesor_id',$asesores)
                ->orderByDesc('created_at')->get();

            }
        }
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

    public  function  descargaDevolucion(Request $request){
      $devoluciones = Devolucion::where('id',$request->devolucion_id)->first();
      $devoluciones->update([
        'status' => Devolucion::DESCARGADO,
      ]);
      return response()->json(['datos' => $devoluciones]);
    }
}
