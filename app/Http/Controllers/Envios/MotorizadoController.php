<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\Distrito;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MotorizadoController extends Controller
{

    //estado motorizado
    public function index(Request $request)
    {

        if ($request->fechaconsulta != null) {
            try {
                $fecha_consulta = Carbon::parse($request->fechaconsulta);
            } catch (\Exception $ex) {
                $fecha_consulta = now();
            }
        } else {
            $fecha_consulta = now();
        }


        if ($request->has('datatable')) {
            //request tab  //enmotorizado//
            $query = DireccionGrupo::
            join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->when($fecha_consulta != null, function ($query) use ($fecha_consulta) {
                    $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta);
                })
                ->select([
                    'direccion_grupos.*',
                ]);

            if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
                $query = $query->where('direccion_grupos.motorizado_id', '=', auth()->id());
            }

            $tab = ($request->tab ?: '');
            switch ($tab) {
                case 'entregado':
                    $query
                        ->where('direccion_grupos.estado', '1')
                        ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_MOTORIZADO_INT);
                    break;
                case 'nocontesto':
                    $query
                        ->where('direccion_grupos.estado', '1')
                        ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
                        ->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_NO_CONTESTO);
                    break;
                case 'observado':
                    $query->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT);
                    $query->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_OBSERVADO);
                    break;
                default:
                  //EN MOTORIZADO
                  //return $tab;
                    $query
                        ->where('direccion_grupos.estado', '1')
                        ->whereIn('direccion_grupos.condicion_envio_code',
                          [
                            Pedido::MOTORIZADO_INT
                            ,Pedido::RECOJO_MOTORIZADO_INT
                            ,Pedido::RECIBIDO_RECOJO_CLIENTE_INT
                            ,Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT
                            ,Pedido::ENTREGADO_RECOJO_COURIER_INT
                          ] );
                        //->whereNotIn('direccion_grupos.motorizado_status', [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO]);
            }
            //add_query_filtros_por_roles($query, 'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->editColumn('gmlink', function ($pedido) {
                    if ($pedido->gmlink != null && \Str::contains($pedido->gmlink, 'http')) {
                        return '<a href="' . $pedido->gmlink . '" target="_blank"><i class="fa fa-external-link"></i>Ir al Link</a>';
                    } else {
                        return '--';
                    }
                })
                ->editColumn('fecha_salida', function ($pedido) {
                    if ($pedido->fecha_salida != null) {
                        return Carbon::parse($pedido->fecha_salida)->format('d-m-Y');
                    } else {
                        return '';
                    }
                })
                ->editColumn('fecha_recepcion', function ($pedido) {
                    if ($pedido->fecha_recepcion != null) {
                        return Carbon::parse($pedido->fecha_recepcion)->format('d-m-Y h:i A');
                    } else {
                        return '';
                    }
                })
                ->editColumn('condicion_envio', function ($pedido) {
                    $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);

                    return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                })
                ->editColumn('codigos', function ($pedido) {
                    return collect(explode(',', $pedido->codigos))
                        ->map(fn($c, $index) => "<span>" . ($index + 1) . ") <b>$c</b></span>")
                        ->join("<br>");
                })
                ->editColumn('producto', function ($pedido) {
                    return collect(explode(',', $pedido->producto))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('direccion', function ($pedido) {
                    return collect(explode(',', $pedido->direccion))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('referencia', function ($pedido) {
                    /*
                     var datal = "";
                            if (row.destino == 'LIMA') {
                                return data;
                            } else if (row.destino == 'PROVINCIA') {
                                var urladjunto = '{{ route("pedidos.descargargastos", ":id") }}'.replace(':id', data);
                                datal = datal + '<p><a href="' + urladjunto + '">' + data + '</a><p>';
                                return datal;
                            }
                     */

                  if ($pedido->condicion_envio == Pedido::CONFIRMAR_RECOJO_MOTORIZADO){
                    return $pedido->referencia;
                  }else {
                    return collect(explode(',', $pedido->referencia))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                  }

                })
                ->addColumn('action', function ($pedido) use ($tab) {

                    $btn = '<ul class="list-unstyled mt-sm-20">';
                    switch ($tab) {
                        case 'entregado':
                        case 'nocontesto':
                        case 'observado':
                            if ($pedido->estado = 1 && ($pedido->condicion_envio_code == Pedido::MOTORIZADO_INT || $pedido->condicion_envio_code == Pedido::CONFIRM_MOTORIZADO_INT)) {
                                if ($pedido->cambio_direccion_at == null) {
                                    if ($pedido->reprogramacion_at == null) {
                                        $btn .= '<li class="pt-8">
                                <button class="btn btn-sm text-white btn-danger"
                                data-jqconfirm="revertir"
                                data-jqconfirm-id="' . $pedido->id . '"
                                data-jqconfirm-action="' . route('envios.motorizados.revertir', $pedido->id) . '"
                                >
                                    <i class="fas fa-undo text-white"></i>
                                    Revertir
                                </button>
                            </li>';
                                    }
                                }
                            }
                            break;
                        default:break;

                    }
                    switch ($tab) {
                        case 'entregado':
                            break;
                        case 'nocontesto':
                            $btn .= '<li class="pt-8">
                                    <button class="btn btn-sm text-white bg-success" data-motorizado-history="no_contesto"
                                    data-jqconfirm-action="' . route('direcciongrupo.no-contesto.get-sustentos-adjuntos', $pedido->id) . '">
                                        <i class="fa fa-motorcycle text-white" aria-hidden="true"></i>
                                        Ver adjuntos
                                    </button>
                                </li>';
                            break;
                        case 'observado':
                            if ($pedido->reprogramacion_at == null) {
                                $btn .= '<li class="pt-8">
                                <button class="btn btn-sm text-white btn-info"
                                data-jqconfirm="reprogramar"
                                data-jqconfirm-id="' . $pedido->id . '"
                                data-jqconfirm-action="' . route('envios.motorizados.reprogramar', $pedido->id) . '"
                                >
                                    Reprogramar
                                </button>
                            </li>';
                            }
                            break;
                        default:
                          if($pedido->condicion_envio_code==Pedido::MOTORIZADO_INT)
                          {
                            $btn .= '<li class="pt-8">
                                    <button class="btn btn-sm text-white bg-success" data-jqconfirm="general" data-jqconfirm-id="' . $pedido->id . '">
                                        <i class="fa fa-motorcycle text-white" aria-hidden="true"></i>
                                        Entregado
                                    </button>
                                </li>';
                            $btn .= '<li class="pt-8">
                                <button class="btn btn-sm text-white btn-danger" data-jqconfirm="no_contesto" data-jqconfirm-id="' . $pedido->id . '">
                                    <i class="fas fa-phone-slash text-white"></i>
                                    No contesta
                                </button>
                            </li>';
                            $btn .= '<li class="pt-8">
                                <button class="btn btn-sm text-white btn-dark" data-jqconfirm="observado" data-jqconfirm-id="' . $pedido->id . '">
                                    <i class="fas fa-eye text-white"></i>
                                    Observado
                                </button>
                            </li>';
                          }else if($pedido->condicion_envio_code==Pedido::RECOJO_MOTORIZADO_INT){
                            $btn.='<li class="pt-8">';
                              $btn.='<button class="btn btn-sm text-white btn-info" type="button"
                                      data-backdrop="static" data-keyboard="false"
                                      data-toggle="modal" data-target="#modal_recojomotorizado" data-direccion_grupo="' . $pedido->id . '">';
                              $btn.='ENTREGAR';
                              $btn.='</button>';
                            $btn.='</li>';

                          }else if($pedido->condicion_envio_code==Pedido::RECIBIDO_RECOJO_CLIENTE_INT){

                          }elseif($pedido->condicion_envio==Pedido::CONFIRMAR_RECOJO_MOTORIZADO){
                            $btn.='<li class="pt-8">';
                            $btn.='<button class="btn btn-sm text-white btn-info" type="button" data-toggle="modal"
                                        data-target="#modal_recojoenviarope" data-direccion_grupo="' . $pedido->id . '">';
                            $btn.='ENVIAR A OPE';
                            $btn.='</button>';
                            $btn.='</li>';
                          }

                          break;

                    }
                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio', 'gmlink', 'codigos', 'producto', 'direccion', 'referencia'])
                ->toJson();
        }
        return view('envios.motorizado.index', compact('fecha_consulta'));
    }

    //estado motorizado confirmar
    public function confirmar(Request $request)
    {
        $users_motorizado = User::where('rol', 'MOTORIZADO')->where('estado', '1')->pluck('name', 'id');
        if ($request->has('datatable')) {
            $query = DireccionGrupo::/*join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')*/
            join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->whereIn('direccion_grupos.condicion_envio_code',
                  [
                    Pedido::CONFIRM_MOTORIZADO_INT
                    ,Pedido::RECIBIDO_RECOJO_CLIENTE_INT
                    ,Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT
                  ])
                ->whereNotIn('direccion_grupos.condicion_envio_code',[ Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT])
                ->where('direccion_grupos.estado', '1')
                ->select([
                    'direccion_grupos.id',
                    'u.identificador as identificador',
                    DB::raw(" (select 'LIMA') as destino "),
                    'direccion_grupos.celular',
                    'direccion_grupos.nombre',
                    'direccion_grupos.cantidad',
                    'direccion_grupos.codigos',
                    'direccion_grupos.producto',
                    'direccion_grupos.direccion',
                    'direccion_grupos.referencia',
                    'direccion_grupos.observacion',
                    'direccion_grupos.distrito',
                    DB::raw('DATE_FORMAT(direccion_grupos.fecha_recepcion, "%Y-%m-%d") as fecha'),
                    'direccion_grupos.destino as destino2',
                    'direccion_grupos.distribucion',
                    'direccion_grupos.condicion_envio',
                    'direccion_grupos.subcondicion_envio',
                    'direccion_grupos.condicion_sobre',
                    'direccion_grupos.correlativo as correlativo',
                    'direccion_grupos.foto1',
                    'direccion_grupos.foto2',
                    'direccion_grupos.foto3'
                ]);
            //add_query_filtros_por_roles($query, 'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->editColumn('codigos', function ($pedido) {
                    return collect(explode(',', $pedido->codigos))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('producto', function ($pedido) {
                    return collect(explode(',', $pedido->producto))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('direccion', function ($pedido) {
                    return collect(explode(',', $pedido->direccion))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('referencia', function ($pedido) {
                    return collect(explode(',', $pedido->referencia))
                        ->map(fn($c, $index) => ($index + 1) . ") <b>$c</b>")
                        ->join("<br>");
                })
                ->editColumn('condicion_envio', function ($pedido) {
                    $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                    return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                })
                ->addColumn('action', function ($pedido) {
                    $btn = '<ul class="list-unstyled pl-0">';

                    if($pedido->condicion_envio==Pedido::CONFIRMAR_RECOJO_MOTORIZADO)
                    {
                    }
                    else if($pedido->condicion_envio==Pedido::RECIBIDO_RECOJO_CLIENTE)
                    {
                      $btn.='<li class="pt-8">';
                      $btn.='<button class="btn btn-xs text-white btn-success" type="button" data-toggle="modal"
                              data-imagen1="'.\Storage::disk('pstorage')->url($pedido->foto1)  .'"
                              data-imagen2="'.\Storage::disk('pstorage')->url($pedido->foto2)  .'"
                              data-imagen3="'.\Storage::disk('pstorage')->url($pedido->foto3)  .'"
                              data-target="#modal_confirmrecojomotorizado" data-direccion_grupo="' . $pedido->id . '">';
                      $btn.='Confirmar fotos';
                      $btn.='</button>';
                      $btn.='</li>';

                    }else{
                      $btn .= '<li><button href="" class="btn btn-sm text-secondary text-left"
 data-target="' . route('operaciones.confirmarmotorizadoconfirm', ['hiddenMotorizadoEntregarConfirm' => $pedido->id]) . '"
  data-toggle="jqConfirm"
   data-entregar-confirm="' . $pedido->id . '"
    data-destino="' . $pedido->destino . '"
     data-fechaenvio="' . $pedido->fecha . '"
      data-codigos="' . $pedido->codigos . '"
      data-imagen1="' . \Storage::disk('pstorage')->url($pedido->foto1) . '"
      data-imagen2="' . \Storage::disk('pstorage')->url($pedido->foto2) . '"
      data-imagen3="' . \Storage::disk('pstorage')->url($pedido->foto3) . '">
                                        <i class="fas fa-camera text-success"></i> Confirmar fotos
                                    </button></li>';
                      $btn .= '<li><button class="btn btn-sm text-danger  text-left" data-jqconfirm="' . $pedido->id . '" data-jqconfirm-type="revertir"><i class="fas fa-arrow-left text-danger"></i> Revertir</button></li>';
                    }

                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio', 'codigos', 'producto', 'direccion', 'referencia'])
                ->toJson();
        }
        return view('envios.motorizado.confirmar', compact('users_motorizado'));
    }

    //estado confirmar cliente
    public function confirmar_cliente(Request $request)
    {
        if ($request->has('datatable')) {
            $query = DireccionGrupo::/*join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')*/
            join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_VALIDADA_CLIENTE_INT)
                ->where('direccion_grupos.estado', '1')
                ->select([
                    'direccion_grupos.id',
                    'u.identificador as identificador',
                    DB::raw(" (select 'LIMA') as destino "),
                    'direccion_grupos.celular',
                    'direccion_grupos.nombre',
                    'direccion_grupos.cantidad',
                    'direccion_grupos.codigos',
                    'direccion_grupos.producto',
                    'direccion_grupos.direccion',
                    'direccion_grupos.referencia',
                    'direccion_grupos.observacion',
                    'direccion_grupos.distrito',
                    DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                    'direccion_grupos.destino as destino2',
                    'direccion_grupos.distribucion',
                    'direccion_grupos.condicion_envio',
                    'direccion_grupos.subcondicion_envio',
                    'direccion_grupos.condicion_sobre',
                    'direccion_grupos.correlativo as correlativo'
                ]);
            add_query_filtros_por_roles($query, 'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) {
                    $btn = '';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('envios.motorizado.confirmar_cliente');
    }

    public function revertir(DireccionGrupo $grupo)
    {
        $grupo->update([
            'condicion_envio' => Pedido::MOTORIZADO,
            'condicion_envio_code' => Pedido::MOTORIZADO_INT,
            'condicion_envio_at' => now(),
            'motorizado_status' => 0,
            'motorizado_sustento_text' => '',
            'motorizado_sustento_foto' => '',
            'fecha_recepcion' => null,
        ]);
        $grupo->pedidos()->activo()->update([
            'condicion_envio' => Pedido::MOTORIZADO,
            'condicion_envio_code' => Pedido::MOTORIZADO_INT,
            'condicion_envio_at' => now(),
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    public function reprogramar(DireccionGrupo $grupo, Request $request)
    {
        $this->validate($request, [
            'fecha_salida' => 'required|date',
            'adjunto' => 'required|file',
        ], [
            'fecha_salida.required' => 'La fecha de reprogramacion es requerida',
            'fecha_salida.date' => 'La fecha de reprogramacion no tiene el formato correcto',
            'adjunto.required' => 'Es requerido una captura de pantalla',
            'adjunto.file' => 'La captura de pantalla debe ser un archivo',
        ]);

        $grupo->update([
            'reprogramacion_at' => Carbon::parse($request->fecha_salida),
            'reprogramacion_solicitud_user_id' => \auth()->id(),
            'reprogramacion_solicitud_at' => now(),
        ]);

        $grupo->addMedia($request->file('adjunto'))
            ->toMediaCollection('reprogramacion_adjunto');

        DireccionGrupo::addSolicitudAuthorization($grupo, 'reprogramacion');
        return $grupo;
    }

    public function devueltos(Request $request)
    {
        $motorizados = User::select([
            'id',
            'zona',
            DB::raw(" (select count(*) from pedidos inner join direccion_grupos b on pedidos.direccion_grupo=b.id where b.motorizado_status in (" . Pedido::ESTADO_MOTORIZADO_OBSERVADO . "," . Pedido::ESTADO_MOTORIZADO_NO_CONTESTO . "," . Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO . ") and b.motorizado_id=users.id and b.estado=1) as devueltos")
        ])
            ->where('rol', '=', User::ROL_MOTORIZADO)
            ->whereNotNull('zona')
            ->activo()
            ->get();

        $pedidos_observados_count = Pedido::join('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->join('users', 'users.id', 'direccion_grupos.motorizado_id')
            ->select([
                'pedidos.*',
                'direccion_grupos.fecha_salida as grupo_fecha_salida',
                'direccion_grupos.motorizado_status',
                'users.zona',
            ])
            ->whereIn('direccion_grupos.motorizado_status', [
                Pedido::ESTADO_MOTORIZADO_OBSERVADO,
                Pedido::ESTADO_MOTORIZADO_NO_CONTESTO,
                Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO
            ])
            //->where('direccion_grupos.estado', '1')
            //->activo()
            //->whereNotNull('direccion_grupos.fecha')
            ->whereNotNull('direccion_grupos.fecha_salida')
            ->whereNotNull('direccion_grupos.motorizado_id')
            ->get()
            ->groupBy('zona')
            ->map(function ($pedidos) {
                $total = 0;
                foreach ($pedidos as $pedido) {
                    if ($pedido->grupo_fecha_salida != null) {
                        $fecha_salida = Carbon::parse($pedido->grupo_fecha_salida)->startOfDay();
                        $fecha = now()->startOfDay();
                        if ($fecha_salida > $fecha) {
                            $count = 0;
                        } else {
                            $count = $fecha_salida->diffInDays($fecha);
                        }
                        if ($count > 3) {
                            $total++;
                        }
                    }
                }
                return $total;
            })
            ->filter(fn($p) => $p > 0)
            ->map(fn($total, $zona) => $zona . ' debe ' . $total);

        return view('envios.motorizado.sobresdevueltos', compact('motorizados', 'pedidos_observados_count'));
    }

    public function devueltos_datatable(Request $request)
    {
        $pedidos_observados = Pedido::join('direccion_grupos', 'pedidos.direccion_grupo', 'direccion_grupos.id')
            ->select([
                'pedidos.*',
                'direccion_grupos.fecha_salida as grupo_fecha_salida',
                'direccion_grupos.motorizado_status',
                'direccion_grupos.motorizado_sustento_text',
                'direccion_grupos.motorizado_sustento_foto',
                'direccion_grupos.reprogramacion_at',
                'direccion_grupos.reprogramacion_accept_at',
            ])
            ->whereIn('direccion_grupos.motorizado_status', [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO, Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO])
            ->where('direccion_grupos.estado', '1')//analizar si da error , consideraba anulados tbm
            //->activo()
            ->whereNotNull('direccion_grupos.fecha_salida')
            ->where('direccion_grupos.motorizado_id', $request->motorizado_id)
            ->orderBy('direccion_grupos.fecha_salida');

        return datatables()->query(DB::table($pedidos_observados))
            ->addColumn('ordering_data', function ($pedido) {
                if ($pedido->estado = 0 || $pedido->pendiente_anulacion) {
                    return 4;
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO) {
                    return 1;
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO) {
                    return 3;
                } else {
                    return 2;
                }
            })
            ->editColumn('codigo', function ($pedido) {
                if ($pedido->estado = 0 || $pedido->pendiente_anulacion) {
                    return '<div class="p-2">' . $pedido->codigo . '</div>';
                } else {
                    return '<div class="p-2">' . $pedido->codigo . '</div>';
                }
            })
            ->editColumn('grupo_fecha_salida', function ($pedido) {
                return Carbon::parse($pedido->grupo_fecha_salida)->format('d-m-Y h:i A');
            })
            ->addColumn('detalle', function ($pedido) {
                $html = '';
                if ($pedido->estado = 0 || $pedido->pendiente_anulacion) {
                    $html .= '<div class="p-2">ANULADO</div>';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO && $pedido->reprogramacion_at != null) {
                    $html .= '<div class="p-2">OBSERVADO <b class="badge badge-dark">REPROGRAMAR</b></div>';
                    //$html .= '<button data-toggle="jqconfirmtext" data-target="' . $pedido->motorizado_sustento_text . '" class="btn btn-light btn-sm"><i class="fa fa-envelope-open-text"></i> Ver Sustento</button>';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO) {
                    $html .= '<div class="p-2">OBSERVADO</div>';
                    //$html .= '<button data-toggle="jqconfirmtext" data-target="' . $pedido->motorizado_sustento_text . '" class="btn btn-light btn-sm"><i class="fa fa-envelope-open-text"></i> Ver Sustento</button>';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO) {
                    $html .= '<div class="p-2">NO RECIBIDO</div>';
                    //$html .= '<button data-toggle="jqconfirmtext" data-target="' . $pedido->motorizado_sustento_text . '" class="btn btn-light btn-sm"><i class="fa fa-envelope-open-text"></i> Ver Sustento</button>';
                } else {
                    $html .= '<div class="p-2">NO CONTESTA</div>';
                    //$html .= '<button data-toggle="jqconfirmfoto" data-target="' . \Storage::disk('pstorage')->url($pedido->motorizado_sustento_foto) . '" class="btn btn-light btn-sm"><i class="fa fa-photo-video"></i>Ver foto</button>';
                }
                return $html;
            })
            ->addColumn('Ver', function ($pedido) {
                $html = '';
                if ($pedido->estado = 0 || $pedido->pendiente_anulacion) {
                    $html .= '';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO) {
                    $html .= '<button data-toggle="jqconfirmtext" data-target="' . $pedido->motorizado_sustento_text . '" class="btn btn-light btn-sm font-12"><i class="fa fa-envelope-open-text"></i> Ver Sustento</button>';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_CONTESTO) {
                    $html .= '<button data-toggle="jqconfirmfoto" data-target="' . \Storage::disk('pstorage')->url($pedido->motorizado_sustento_foto) . '" class="btn btn-light btn-sm font-12"><i class="fa fa-photo-video"></i>Ver foto</button>';
                } else if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO) {
                    $html .= '';
                }
                return $html;
            })
            ->addColumn('situacion_color', function ($pedido) {
                if ($pedido->grupo_fecha_salida != null) {
                    $fecha_salida = Carbon::parse($pedido->grupo_fecha_salida)->startOfDay();
                    $fecha = now()->startOfDay();
                    if ($fecha_salida > $fecha) {
                        $count = 0;
                    } else {
                        $count = $fecha_salida->diffInDays($fecha);
                    }

                    if ($count > 3) {
                        return '#e74c3c';
                    } elseif ($count >= 3) {
                        return '#fd7e14';
                    } elseif ($count >= 2) {
                        return '#ffc107';
                    } elseif ($count >= 1) {
                        return 'rgb(255 255 234)';
                    } else {
                        return '';
                    }
                }
                return '';
            })
            ->addColumn('action', function ($pedido) {
                $btn = '';
                //if (auth()->user()->can('envios.enviar')):

                $btn .= '<ul class="list-unstyled pl-0" data-group="' . $pedido->direccion_grupo . '">';

                $btn .= '<li>
                                <button type="button"
                                data-target="' . route('envios.devueltos.recibir', $pedido->id) . '"
                                data-toggle="jqconfirm"  class="' . ($pedido->reprogramacion_at != null ? 'border border-primary' : '') . ' btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button>
                            </li>';

                if ($pedido->motorizado_status == Pedido::ESTADO_MOTORIZADO_OBSERVADO && (in_array($pedido->motorizado_sustento_text,['No entregado por el motorizado','No entregado por el motorizado (hecho por el sistema)']))) {
                    $btn .= '<li>
                                <button type="button"
                                data-target="' . route('envios.devueltos.recibir', ['pedido' => $pedido->id, 'action' => 'send_motorizado']) . '"
                                data-toggle="jqconfirmmotorizado"  class="' . ($pedido->reprogramacion_at != null ? 'border border-primary' : '') . ' w-100 btn btn-info mt-2 btn-sm">
                                <i class="fa fa-motorcycle "></i>
</button>
                            </li>';
                }
                $btn .= '</ul>';
                //endif;

                return $btn;
            })
            ->orderColumn('ordering_data', 'ASC')
            ->rawColumns(['action', 'codigo', 'detalle', 'Ver'])
            ->make(true);
    }

    public function devueltos_recibir(Request $request, Pedido $pedido)
    {
        $action = $request->get('action');
        /*********
         * IDENTIFICAMOS AL GRUPO
         */
        $grupo = $pedido->direcciongrupo;

        if ($action == 'send_motorizado') {
            if ($grupo->pedidos()->count() > 1) {
                $newgrupo = $grupo->replicate();

                $newgrupo->save();
                $pedido->update([
                    'direccion_grupo' => $newgrupo->id,
                ]);
                DireccionGrupo::restructurarCodigos($grupo);
                DireccionGrupo::restructurarCodigos($newgrupo);
                $grupo = $newgrupo;
            }

            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::MOTORIZADO_INT, [
                'fecha_salida' => now(),
                'fecha_salida_old_at' => $grupo->fecha_salida,
                'motorizado_status' => 0,
                'motorizado_sustento_text' => '',
            ]);
            return $grupo;
        } else {
            /**************
             * CREAMOS EL GRUPO TEMPORAL
             */
            $pgroup = GrupoPedido::createGroupByPedido($pedido, false, true);

            if ($grupo != null) {
                if ($grupo->pedidos()->activo()->count() <= 1) {
                    $grupo->update([
                        'estado' => 0,
                    ]);
                    if ($pedido->estado = 0) {
                        $grupo->update([
                            'motorizado_status' => Pedido::ESTADO_MOTORIZADO_RE_RECIBIDO,
                        ]);
                    } else {
                        $grupo->update([
                            'motorizado_status' => 0,
                        ]);
                    }
                } else {
                    $pedido->update([
                        'direccion_grupo' => null
                    ]);
                }
                DireccionGrupo::restructurarCodigos($grupo);
            } else {
                $pedido->update([
                    'direccion_grupo' => null
                ]);
            }

            return $pgroup;
        }
    }

    public function Enviosrecepcionmotorizado()
    {
        $motorizados = User::select([
            'id',
            'zona',
            DB::raw(" (select count(*) from pedidos inner join direccion_grupos b on pedidos.direccion_grupo=b.id where b.motorizado_status in (" . Pedido::ESTADO_MOTORIZADO_OBSERVADO . "," . Pedido::ESTADO_MOTORIZADO_NO_CONTESTO . ") and b.motorizado_id=users.id and b.estado=1) as devueltos")
        ])->where('rol', '=', User::ROL_MOTORIZADO)
            ->whereNotNull('zona')
            ->activo()
            ->get();

        $users_motorizado = User::where('rol', 'MOTORIZADO')->where('estado', '1')->pluck('name', 'id');
        //$fecha_consulta = Carbon::now()->format('d/m/Y');
        $fecha_consulta = Carbon::now()->format('Y-m-d');
        $condiciones = [
            "1" => 1,
            "2" => 2,
            "3" => 3
        ];

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->pluck('distrito', 'distrito');

        $direcciones = DireccionEnvio::join('direccion_pedidos as dp', 'direccion_envios.id', 'dp.direccion_id')
            ->select('direccion_envios.id',
                'direccion_envios.distrito',
                'direccion_envios.direccion',
                'direccion_envios.referencia',
                'direccion_envios.nombre',
                'direccion_envios.celular',
                'dp.pedido_id as pedido_id',
            )
            ->where('direccion_envios.estado', '1')
            ->where('dp.estado', '1')
            ->get();
        $departamento = Departamento::where('estado', "1")
            ->pluck('departamento', 'departamento');
        $superasesor = User::where('rol', 'Super asesor')->count();

        $ver_botones_accion = 1;

        if (Auth::user()->rol == "Asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Super asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Encargado") {
            $ver_botones_accion = 1;
        } else {
            $ver_botones_accion = 1;
        }
        if (auth()->user()->rol == User::ROL_MOTORIZADO || \request()->has('show_motorizado_dev')) {
            return view('envios.recepcionMotorizado', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor', 'ver_botones_accion', 'departamento', 'fecha_consulta', 'users_motorizado', 'motorizados'));
        } elseif (in_array(auth()->user()->rol, [User::ROL_ADMIN, User::ROL_JEFE_COURIER])) {
            return view('envios.recepcionMotorizado_index', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor', 'ver_botones_accion', 'departamento', 'fecha_consulta', 'users_motorizado', 'motorizados'));
        }
    }

    public function Enviosrecepcionmotorizadotabla(Request $request)
    {
        $tipo_consulta = $request->consulta;
        $fecha_actual = Carbon::now()->startOfDay();

        //SI ES QUE EXISTE UNA FECHA
        if ($request->fechaconsulta != null) {
            try {
                $fecha_consulta = Carbon::parse($request->fechaconsulta)->startOfDay();
            } catch (\Exception $ex) {
                $fecha_consulta = now()->startOfDay();
            }
        } else {
            $fecha_consulta = now()->startOfDay();
        }

        //OBTENEMOS EL CODIGO DE CONDICION
        if ($request->condicion != null) {
            $url_tabla = $request->condicion;
        } else {
            $url_tabla = null;
        }


        if ($tipo_consulta == "pedido") {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'pedidos.id',
                    'pedidos.cliente_id',

                    'u.identificador as users',
                    'u.id as user_id',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.codigo',
                    'pedidos.codigos_confirmados',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'pedidos.created_at as fecha_salida_recepcion_motorizado',
                    'pedidos.devuelto',
                    'pedidos.cant_devuelto',
                    'pedidos.returned_at',
                    'pedidos.observacion_devuelto',
                    DB::raw("DATEDIFF(DATE(NOW()), DATE(pedidos.created_at)) AS dias")
                ])
                ->where('pedidos.estado', '1')
                ->whereIn('pedidos.condicion_envio_code', [$request->condicion])
                ->where('dp.estado', '1');
        } else if ($tipo_consulta == "paquete") {

            $pedidos = null;
            $filtros_code = null;
            $filtros_code = explode(",", $url_tabla);
            //return  $filtros_code;
            if( in_array('19',$filtros_code) )
            {
              $filtros_code = explode(",", $url_tabla.','.Pedido::ENVIO_RECOJO_MOTORIZADO_COURIER_INT);
              //array_push($filtros_code,);
            }else if( in_array('18',$filtros_code) )
            {
              $filtros_code = explode(",", $url_tabla.','.Pedido::RECEPCION_RECOJO_MOTORIZADO_INT);
              //array_push($filtros_code,);
              //return $filtros_code;
            }

            $grupos = DireccionGrupo::select([
                'direccion_grupos.*',
                'direccion_grupos.fecha_recepcion_motorizado as fecha_salida_recepcion_motorizado',
                'u.identificador as user_identificador',
                //DB::raw(" (select 'LIMA') as destino "),
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha_formato'),
            ])
                ->join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                //->where('direccion_grupos.condicion_envio_code', Pedido::REPARTO_COURIER_INT)
                //->whereIn('direccion_grupos.condicion_envio_code', [Pedido::ENVIO_MOTORIZADO_COURIER_INT,Pedido::RECEPCION_MOTORIZADO_INT])
                ->whereIn('direccion_grupos.condicion_envio_code', $filtros_code)
                ->when($fecha_consulta != null, function ($query) use ($fecha_consulta) {
                    $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta);
                })
                ->where('direccion_grupos.motorizado_status', '=', 0)
                ->activo();

            if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
                $grupos->where('direccion_grupos.motorizado_id', \auth()->id());
            }

            return Datatables::of(DB::table($grupos))
                ->addIndexColumn()
                ->addColumn('condicion_envio_color', function ($grupo) {
                    return Pedido::getColorByCondicionEnvio($grupo->condicion_envio);
                })
                ->editColumn('fecha_salida', function ($grupo) {
                    try {
                        return Carbon::parse($grupo->fecha_salida)->format('d-m-Y');
                    } catch (\Exception $ex) {
                        return $grupo->fecha_salida;
                    }
                })
                ->editColumn('condicion_envio', function ($grupo) {
                    $color = Pedido::getColorByCondicionEnvio($grupo->condicion_envio);

                    $badge_estado = '';
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">Direccion agregada</span>';

                    $badge_estado .= '<span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span>';
                    $badge_estado .= '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $grupo->condicion_envio . '</span>';
                    return $badge_estado;
                })
                ->addColumn('action', function ($direcciongrupo) use ($fecha_consulta, $fecha_actual) {
                    $btn = '';

                    $btn .= '<ul class="list-unstyled pl-0">';

                    if ( in_array($direcciongrupo->condicion_envio_code,[Pedido::ENVIO_MOTORIZADO_COURIER_INT,Pedido::ENVIO_RECOJO_MOTORIZADO_COURIER_INT]) )
                    {

                        if ($fecha_actual == $fecha_consulta) {
                            $count = Pedido::query()->where('direccion_grupo', $direcciongrupo->id)->count();

                            $btn .= ' <li>
                                            <button
                                            data-recibido="1"
                                            data-btncolor="orange"
                                            data-btntext="Recibido"
                                            data-count="' . $count . '"
                                            data-target="' . route('envios.recepcionmotorizado.pedidos', $direcciongrupo->id) . '"
                                            data-target-post="' . route('envios.recepcionarmotorizado', ['hiddenEnvio' => $direcciongrupo->id, 'hiddenAccion' => 'recibir']) . '"
                                            data-toggle="jqconfirm" class="btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button>
                                        </li>';

                            if( in_array($direcciongrupo->condicion_envio_code,[Pedido::ENVIO_MOTORIZADO_COURIER_INT]) )
                            {
                              if ($count == 1) {
                                $btn .= ' <li>
                                            <button
                                            data-recibido="0"
                                            data-btncolor="red"
                                            data-btntext="No recibido"
                                            data-target="' . route('envios.recepcionarmotorizado') . '"
                                            data-target-post="' . route('envios.recepcionarmotorizado', ['hiddenEnvio' => $direcciongrupo->id, 'hiddenAccion' => 'rechazar']) . '"
                                            data-count="' . $count . '"
                                            data-toggle="jqconfirm" class="btn btn-danger btn-sm mt-8"><i class="fa fa-times-circle-o" aria-hidden="true"></i>No recibido</button>
                                        </li>';
                              } else {
                                $btn .= ' <li>
                                            <button
                                            data-recibido="0"
                                            data-btncolor="red"
                                            data-btntext="No recibido"
                                            data-count="' . $count . '"
                                            data-target="' . route('envios.recepcionmotorizado.pedidos', $direcciongrupo->id) . '"
                                            data-target-post="' . route('envios.recepcionarmotorizado', ['hiddenEnvio' => $direcciongrupo->id, 'hiddenAccion' => 'rechazar']) . '"
                                            data-toggle="jqconfirm" class="btn btn-danger btn-sm mt-8"><i class="fa fa-times-circle-o" aria-hidden="true"></i>No recibido</button>
                                        </li>';
                              }
                            }



                        } else {
                            $btn .= '<li>
                                <button disabled class="btn btn-warning btn-sm"><i class="fas fa-check-circle"></i> Recibido</button>
                            </li>';
                            $btn .= ' <li>
                        <button disabled class="btn btn-danger btn-sm mt-8"><i class="fa fa-times-circle-o" aria-hidden="true"></i>No recibido</button>
                    </li>';
                        }

                    } else if ( in_array($direcciongrupo->condicion_envio_code,[Pedido::RECEPCION_MOTORIZADO_INT,Pedido::RECEPCION_RECOJO_MOTORIZADO_INT]) ) {
                        if ($fecha_actual == $fecha_consulta) {
                            if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
                                if (count(DireccionGrupo::getSolicitudAuthorization($direcciongrupo->motorizado_id)) == 0) {
                                    $btn .= '<li>
                                <button class="btn btn-sm text-secondary" data-target="#modal-confirmacion" data-toggle="modal" data-ide="' . $direcciongrupo->id . '" data-entregar-confirm="' . $direcciongrupo->id . '" data-destino="' . $direcciongrupo->destino . '" data-fechaenvio="' . $direcciongrupo->fecha . '" data-codigos="' . $direcciongrupo->codigos . '">
                                    <i class="fas fa-envelope text-success"></i> Iniciar ruta
                                </button>
                            </li>';
                                } else {
                                    $btn .= '<li>
                                <button class="btn btn-sm text-secondary" disabled title="No autorizado">
                                    <i class="fas fa-envelope text-success"></i> Iniciar ruta
                                </button>
                            </li>';
                                }
                            } else {
                                $btn .= '<li>
                                <button class="btn btn-sm text-secondary" data-target="#modal-confirmacion" data-toggle="modal" data-ide="' . $direcciongrupo->id . '" data-entregar-confirm="' . $direcciongrupo->id . '" data-destino="' . $direcciongrupo->destino . '" data-fechaenvio="' . $direcciongrupo->fecha . '" data-codigos="' . $direcciongrupo->codigos . '">
                                    <i class="fas fa-envelope text-success"></i> Iniciar ruta
                                </button>
                            </li>';

                            }

                        }
                    }

                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio'])
                ->make(true);

        }

    }


    public function EnviosrecepcionmotorizadotablaGeneral(Request $request)
    {
        $tipo_consulta = $request->consulta;
        $fecha_actual = Carbon::now()->startOfDay();
        $fecha_consulta = $request->fechaconsulta;
        $url_tabla = $request->vista;

        if ($tipo_consulta == "pedido") {
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'pedidos.id',
                    'pedidos.cliente_id',
                    'u.identificador as users',
                    'u.id as user_id',
                    'dp.codigo as codigos',
                    'dp.nombre_empresa as empresas',
                    'dp.total as total',
                    'pedidos.condicion',
                    'pedidos.created_at as fecha',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.codigo',
                    'pedidos.codigos_confirmados',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'pedidos.created_at as fecha_salida_recepcion_motorizado',
                    'pedidos.devuelto',
                    'pedidos.cant_devuelto',
                    'pedidos.returned_at',
                    'pedidos.observacion_devuelto',
                    DB::raw("DATEDIFF(DATE(NOW()), DATE(pedidos.created_at)) AS dias")
                ])
                ->where('pedidos.estado', '1')
                ->whereIn('pedidos.condicion_envio_code', [$request->condicion])
                ->where('dp.estado', '1');
        } else if ($tipo_consulta == "paquete") {
            $pedidos = null;
            $filtros_code = explode(",", $request->vista);
            array_push($filtros_code,Pedido::ENVIO_RECOJO_MOTORIZADO_COURIER_INT);
            //return $filtros_code;


            $grupos = DireccionGrupo::select([
                'direccion_grupos.*',
                'direccion_grupos.fecha_recepcion_motorizado as fecha_salida_recepcion_motorizado',
                'u.identificador as user_identificador',
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha_formato'),
            ])
                ->join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->whereIn('direccion_grupos.condicion_envio_code', $filtros_code)
                ->whereDate('direccion_grupos.fecha_salida', $request->fechaconsulta)
                ->where('direccion_grupos.motorizado_id', $request->motorizado_id)
                ->where('direccion_grupos.distribucion', 'LIKE', '%' . $request->ZONA . '%')
                ->activo();

            return Datatables::of(DB::table($grupos))
                ->addColumn('codigos', function ($grupo) {
                    return collect(explode(',', $grupo->codigos))->trim()->join("<br>");
                })
                ->addColumn('condicion_envio_color', function ($grupo) {
                    return Pedido::getColorByCondicionEnvio($grupo->condicion_envio);
                })
                ->editColumn('condicion_envio', function ($grupo) {
                    $color = Pedido::getColorByCondicionEnvio($grupo->condicion_envio);

                    $badge_estado = '';
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span>';

                    $badge_estado .= '<span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span>';
                    $badge_estado .= '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $grupo->condicion_envio . '</span>';
                    return $badge_estado;
                })
                ->editColumn('distrito', function ($pedido) {

                    if ($pedido->distribucion == 'OLVA') {
                        $html = "";
                        if ($pedido->observacion) {
                            $html .= collect(explode(',', $pedido->observacion))
                                ->trim()
                                ->unique()
                                ->map(fn($observacion) => '<a class="btn btn-icon p-0" target="_blank" href="' . \Storage::disk('pstorage')->url($observacion) . '">
<i class="fa fa-file-pdf"></i>
Ver Rotulo</a>')
                                ->join('');
                        }
                        return $html;
                    }
                    return $pedido->distrito;
                })
                ->addColumn('action', function ($direcciongrupo) use ($fecha_consulta, $fecha_actual) {
                    $btn = '';

                    $btn .= '<ul class="list-unstyled pl-0">';

                    $count = Pedido::query()->where('direccion_grupo', $direcciongrupo->id)->count();
                    $btn .= ' <li>
                                            <button
                                            data-recibido="0"
                                            data-btncolor="red"
                                            data-btntext="Retornar"
                                            data-count="' . $count . '"
                                            data-target="' . route('envios.recepcionmotorizado.pedidos', $direcciongrupo->id) . '"
                                            data-target-post="' . route('envios.recepcionarmotorizado', ['hiddenEnvio' => $direcciongrupo->id, 'hiddenAccion' => 'retornar_para_sindireccion']) . '"
                                            data-toggle="jqconfirm" class="btn btn-danger btn-sm mt-8"
                                            style="font-size: 8px;"><i class="fa fa-times-circle-o" aria-hidden="true"></i>Retornar</button>
                                        </li>';

                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio', 'distrito', 'codigos'])
                ->make(true);
        }
    }

    public function getPedidos($grupo)
    {
        $grupo = DireccionGrupo::with(['pedidos.detallePedido'])->findOrFail($grupo);
        return response()->json([
            'grupo' => $grupo,
        ]);
    }


    public function ComparacionMotorizado(Request $request)
    {
        $grupos = Pedido::select([
            'pedidos.codigo'])
            ->join('direccion_grupos', 'direccion_grupos.id', 'pedidos.direccion_grupo')
            ->where('direccion_grupos.estado', 1)
            ->where('direccion_grupos.condicion_envio_code', 19)
            ->whereDate('direccion_grupos.fecha_salida', Carbon::parse($request->fechaconsulta))
            ->where('direccion_grupos.motorizado_id', $request->motorizado_id)
            ->where('direccion_grupos.motorizado_status', 0)
            //->where('direccion_grupos.distribucion', 'LIKE', '%' . $request->zona . '%')
            ->activo()
            ->get();


        if ($grupos->count()) {
            return response()->json([
                'grupo' => $grupos->pluck('codigo'),
                'codigo' => 1
            ]);
        } else {
            return response()->json([
                'grupo' => [],
                'codigo' => 0
            ]);
        }
    }

  public function MotorizadoRecojo(Request $request)
  {
    $envio = DireccionGrupo::where("id", $request->entrega_motorizado_recojo)->first();
    $envio->update([
      'modificador' => 'USER' . Auth::user()->id,
      'condicion_envio' => Pedido::RECIBIDO_RECOJO_CLIENTE,
      'condicion_envio_code' => Pedido::RECIBIDO_RECOJO_CLIENTE_INT,
      'condicion_envio_at' => now(),
    ]);

    $envio->pedidos()->activo()->update([
      'condicion_envio_code' => Pedido::RECIBIDO_RECOJO_CLIENTE_INT,
      'condicion_envio_at' => now(),
      'condicion_envio' => Pedido::RECIBIDO_RECOJO_CLIENTE,
      'fecha_salida' => $request->fecha_salida,
      'cambio_direccion_at' => null
    ]);

    //$files1 = $request->file('foto1');
    //$files2 = $request->file('foto2');
    //$files3 = $request->file('foto3');

    //$destinationPath = base_path('public/storage/entregas/');

    // Carbon::now()->second . $files1->getClientOriginalName();
    if ($request->hasFile('foto1'))
    {
      $file_name_1 = $request->file('foto1')->store('motorizado_recojo', 'pstorage');
      $envio->update([
        'foto1' => $file_name_1,
      ]);
    }
    if ($request->hasFile('foto2'))
    {
      $file_name_2 = $request->file('foto2')->store('motorizado_recojo', 'pstorage');
      $envio->update([
        'foto2' => $file_name_2,
      ]);
    }
    if ($request->hasFile('foto3'))
    {
      $file_name_3 = $request->file('foto3')->store('motorizado_recojo', 'pstorage');
      $envio->update([
        'foto3' => $file_name_3,
      ]);
    }


    return response()->json(['html' => $request->entrega_motorizado_recojo]);

  }

  public function motorizadoRecojoenviarcourier(Request $request)
  {
    $envio = DireccionGrupo::where("id", $request->input_recojoenviarcourier)->first();

    DireccionGrupo::cambiarCondicionEnvio($envio, Pedido::ENTREGADO_RECOJO_COURIER_INT);
    PedidoMovimientoEstado::create([
      'pedido' => $request->input_recojoenviarope,
      'condicion_envio_code' => Pedido::ENTREGADO_RECOJO_COURIER_INT,
      'fecha_salida'=>now(),
      'notificado' => 0
    ]);

    return response()->json(['html' => $envio->id]);
  }


}
