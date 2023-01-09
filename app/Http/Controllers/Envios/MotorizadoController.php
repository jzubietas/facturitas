<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotorizadoController extends Controller
{

    //estado motorizado
    public function index(Request $request)
    {
        if ($request->has('datatable')) {
            $query = DireccionGrupo::/*join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')*/
            join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                //->where('direccion_grupos.condicion_envio_code', Pedido::RECEPCION_MOTORIZADO_INT)
                ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
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
            if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
                $query = $query->where('direccion_grupos.motorizado_id', '=', auth()->id());
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
                ->addColumn('action', function ($pedido) {
                    $btn = '<ul class="list-unstyled pl-0 d-flex mt-sm-20">';
                    $btn .= '<li class="p-8">
                                    <button class="btn btn-sm text-white bg-primary" data-jqconfirm="' . $pedido->id . '">
                                        <i class="fa fa-motorcycle text-white" aria-hidden="true"></i>
                                        A confirmacion
                                    </button>
                                </li>';
                    $btn .= '<li class="p-8">
                                <button class="btn btn-sm text-white btn-danger" data-jqconfirmcancel="' . $pedido->id . '" data-jqconfirm-type="revertir">
                                    <i class="fas fa-arrow-left text-white"></i>
                                    Revertir a reparto
                                </button>
                            </li>';
                    $btn .= '</ul>';

                    return $btn;
                })
                ->rawColumns(['action', 'condicion_envio'])
                ->toJson();
        }
        return view('envios.motorizado.index');
    }

    //estado motorizado confirmar
    public function confirmar(Request $request)
    {
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
                    DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
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
        return view('envios.motorizado.confirmar');
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
}
