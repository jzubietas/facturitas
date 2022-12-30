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
            $query = DireccionGrupo::join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')
                ->join('clientes as c', 'c.id', 'de.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
                ->where('direccion_grupos.estado', '1')
                ->select([
                    'direccion_grupos.id',
                    'u.identificador as identificador',
                    DB::raw(" (select 'LIMA') as destino "),
                    'de.celular',
                    'de.nombre',
                    'de.cantidad',
                    'direccion_grupos.codigos',
                    'direccion_grupos.producto',
                    'de.direccion',
                    'de.referencia',
                    'de.observacion',
                    'de.distrito',
                    DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                    'direccion_grupos.destino as destino2',
                    'direccion_grupos.distribucion',
                    'direccion_grupos.condicion_envio',
                    'direccion_grupos.subcondicion_envio',
                    'direccion_grupos.condicion_sobre',
                    'direccion_grupos.correlativo as correlativo'
                ]);
            add_query_filtros_por_roles($query,'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) {
                    $btn = '';

                    $btn.='<ul class="list-unstyled pl-0">';
                        /*$btn.='<li>
                                    <a href="" class="btn-sm text-secondary" data-target="#modal-motorizado-entregar" data-toggle="modal" data-entregar="'.$pedido->id.'" data-destino="'.$pedido->destino.'" data-fechaenvio="'.$pedido->fecha.'" data-codigos="'.$pedido->codigos.'">
                                        <i class="fas fa-envelope text-success"></i>A confirmar</a></li>
                                    </a>
                                </li>';*/
                        $btn.='<li>
                                    <a href="" class="btn-sm text-secondary" data-target="#modal-motorizado-entregar"  data-codigos="'.$pedido->codigos.'" data-toggle="modal" data-ide="'.$pedido->id.'" data-destino="'.$pedido->destino.'" data-fechaenvio="'.$pedido->fecha.'">
                                        <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i> A confirmacion
                                    </a>
                                </li>';
                        /*$btn.='<li>
                                    <a href="" class="btn-sm text-secondary" data-target="#modal-recibir" data-toggle="modal" data-recibir="'.$pedido->id.'">
                                        <i class="fa fa-motorcycle text-primary" aria-hidden="true"></i>Recibido
                                    </a>
                                </li>';*/
                    $btn.='</ul>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('envios.motorizado.index');
    }

    //estado motorizado confirmar
    public function confirmar(Request $request)
    {
        if ($request->has('datatable')) {
            $query = DireccionGrupo::join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')
                ->join('clientes as c', 'c.id', 'de.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_MOTORIZADO_INT)
                ->where('direccion_grupos.estado', '1')
                ->select([
                    'direccion_grupos.id',
                    'u.identificador as identificador',
                    DB::raw(" (select 'LIMA') as destino "),
                    'de.celular',
                    'de.nombre',
                    'de.cantidad',
                    'direccion_grupos.codigos',
                    'direccion_grupos.producto',
                    'de.direccion',
                    'de.referencia',
                    'de.observacion',
                    'de.distrito',
                    DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                    'direccion_grupos.destino as destino2',
                    'direccion_grupos.distribucion',
                    'direccion_grupos.condicion_envio',
                    'direccion_grupos.subcondicion_envio',
                    'direccion_grupos.condicion_sobre',
                    'direccion_grupos.correlativo as correlativo'
                ]);
            add_query_filtros_por_roles($query,'u');
            return datatables()->query(DB::table($query))
                ->addIndexColumn()
                ->addColumn('action', function ($pedido) {
                    $btn = '';
                    $btn.='<ul class="list-unstyled pl-0">';
                        $btn.='<li>
                                    <a href="" class="btn-sm text-secondary" data-target="#modal-motorizado-entregar-confirm" data-toggle="modal" data-entregar-confirm="'.$pedido->id.'" data-destino="'.$pedido->destino.'" data-fechaenvio="'.$pedido->fecha.'" data-codigos="'.$pedido->codigos.'">
                                        <i class="fas fa-envelope text-success"></i> A cliente</a></li>
                                    </a>
                                </li>';
                    $btn.='</ul>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('envios.motorizado.confirmar');
    }

    //estado confirmar cliente
    public function confirmar_cliente(Request $request)
    {
        if ($request->has('datatable')) {
            $query = DireccionGrupo::join('direccion_envios as de', 'direccion_grupos.id', 'de.direcciongrupo')
                ->join('clientes as c', 'c.id', 'de.cliente_id')
                ->join('users as u', 'u.id', 'c.user_id')
                ->where('direccion_grupos.condicion_envio_code', Pedido::CONFIRM_VALIDADA_CLIENTE_INT)
                ->where('direccion_grupos.estado', '1')
                ->select([
                    'direccion_grupos.id',
                    'u.identificador as identificador',
                    DB::raw(" (select 'LIMA') as destino "),
                    'de.celular',
                    'de.nombre',
                    'de.cantidad',
                    'direccion_grupos.codigos',
                    'direccion_grupos.producto',
                    'de.direccion',
                    'de.referencia',
                    'de.observacion',
                    'de.distrito',
                    DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
                    'direccion_grupos.destino as destino2',
                    'direccion_grupos.distribucion',
                    'direccion_grupos.condicion_envio',
                    'direccion_grupos.subcondicion_envio',
                    'direccion_grupos.condicion_sobre',
                    'direccion_grupos.correlativo as correlativo'
                ]);
            add_query_filtros_por_roles($query,'u');
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
