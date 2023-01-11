<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotorizadoController extends Controller
{

    //estado motorizado
    public function index(Request $request)
    {

        $fecha_consulta = Carbon::now()->format('Y-m-d');

        if ($request->fechaconsulta != null) {
            $fecha_consulta_rec = Carbon::createFromFormat('d/m/Y', $request->fechaconsulta)->format('Y-m-d');
            dd($fecha_consulta_rec);
        } else {
            $fecha_consulta_rec = null;
        }


        if ($request->has('datatable')) {
            $query = DireccionGrupo::/*join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')*/
            join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->when($fecha_consulta_rec != null, function ($query) use ($fecha_consulta_rec) {
                    $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta_rec);
                })
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
                    'direccion_grupos.correlativo as correlativo',
                    'direccion_grupos.condicion_envio_code',
                    'direccion_grupos.estado',
                    'direccion_grupos.motorizado_status',
                    'direccion_grupos.motorizado_sustento_text',
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
                case 'no_contesto':
                    $query
                        ->where('direccion_grupos.estado', '1')
                        ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
                        ->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_NO_CONTESTO);
                    break;
                case 'observado':
                    //$query->where('direccion_grupos.estado', '1');
                    $query->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT);
                    $query->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_OBSERVADO);
                    /*$query->where(function ($query) {
                        //$query->where('direccion_grupos.estado', '0');
                        //$query->where('direccion_grupos.condicion_envio', Pedido::ANULADO);
                        $query->orWhere(function ($query) {
                            $query->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT);
                            $query->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_OBSERVADO);
                        });
                    });*/
                    break;
                default:
                    //$query->where('direccion_grupos.condicion_envio_code', Pedido::RECEPCION_MOTORIZADO_INT)
                    $query
                        ->where('direccion_grupos.estado', '1')
                        ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
                        ->whereNotIn('direccion_grupos.motorizado_status', [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO]);
            }
            //add_query_filtros_por_roles($query, 'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->editColumn('condicion_envio', function ($pedido) {
                    $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);

                    return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success w-100" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                })
                ->addColumn('action', function ($pedido) use ($tab) {

                    $btn = '<ul class="list-unstyled mt-sm-20">';
                    switch ($tab) {
                        case 'entregado':
                        case 'no_contesto':
                        case 'observado':
                            if ($pedido->estado = 1 && ($pedido->condicion_envio_code==Pedido::MOTORIZADO_INT||$pedido->condicion_envio_code==Pedido::CONFIRM_MOTORIZADO_INT)) {
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
                            break;
                        default:

                    }
                    switch ($tab) {
                        case 'entregado':

                            break;
                        case 'no_contesto':
                            $btn .= '<li class="pt-8">
                                    <button class="btn btn-sm text-white bg-success" data-motorizado-history="no_contesto"
                                    data-jqconfirm-action="' . route('direcciongrupo.no-contesto.get-sustentos-adjuntos', $pedido->id) . '">
                                        <i class="fa fa-motorcycle text-white" aria-hidden="true"></i>
                                        Ver adjuntos
                                    </button>
                                </li>';
                            break;
                        case 'observado':
                            $btn .= '<hr class="my-2"><p class="text-wrap text-break"><i>' . $pedido->motorizado_sustento_text . '</i></p>';
                            break;
                        default:
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

                    }
                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio'])
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
                ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_MOTORIZADO_INT)
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
                ->editColumn('condicion_envio', function ($pedido) {
                    $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                    return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success w-100" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                })
                ->addColumn('action', function ($pedido) {
                    $btn = '<ul class="list-unstyled pl-0">';
                    $btn .= '<li>
                                    <button href="" class="btn btn-sm text-secondary" data-target="#modal-motorizado-entregar-confirm" data-toggle="modal" data-entregar-confirm="' . $pedido->id . '" data-destino="' . $pedido->destino . '" data-fechaenvio="' . $pedido->fecha . '" data-codigos="' . $pedido->codigos . '"
                                        data-imagen1="' . \Storage::disk('pstorage')->url($pedido->foto1) . '" data-imagen2="' . \Storage::disk('pstorage')->url($pedido->foto2) . '" data-imagen3="' . \Storage::disk('pstorage')->url($pedido->foto3) . '"
                                    >
                                        <i class="fas fa-envelope text-success"></i> A cliente
                                    </button>
                                </li>';
                    $btn .= '<li>
                                    <button class="btn btn-sm text-danger" data-jqconfirm="' . $pedido->id . '" data-jqconfirm-type="revertir">
                                        <i class="fas fa-arrow-left text-danger"></i> Revertir
                                    </button>
                                </li>';
                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio'])
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
            'motorizado_status' => 0,
            'motorizado_sustento_text' => '',
            'motorizado_sustento_foto' => '',
        ]);
        $grupo->pedidos()->activo()->update([
            'condicion_envio' => Pedido::MOTORIZADO,
            'condicion_envio_code' => Pedido::MOTORIZADO_INT,
        ]);
        return response()->json([
            'success' => true
        ]);
    }
}
