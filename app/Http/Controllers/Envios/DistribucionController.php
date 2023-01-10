<?php

namespace App\Http\Controllers\Envios;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\DetallePedido;
use App\Models\DireccionGrupo;
use App\Models\Distrito;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use App\Models\PedidoMotorizadoHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DistribucionController extends Controller
{
    public function index()
    {
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

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
            ->pluck('distrito', 'distrito');

        $departamento = Departamento::where('estado', "1")
            ->pluck('departamento', 'departamento');

        $superasesor = User::where('rol', 'Super asesor')->count();

        $motorizados = User::query()->where('rol', '=', 'MOTORIZADO')->whereNotNull('zona')->get();

        return view('envios.distribuirsobres', compact('superasesor', 'motorizados', 'ver_botones_accion', 'distritos', 'departamento'));
    }

    public function datatable(Request $request)
    {
        $query = GrupoPedido::query()->with(['pedidos', 'motorizadoHistories'])
            ->join('grupo_pedido_items', 'grupo_pedido_items.grupo_pedido_id', '=', 'grupo_pedidos.id')
            ->select([
                'grupo_pedidos.id',
                DB::raw('GROUP_CONCAT(grupo_pedido_items.codigo) as codigos'),
                DB::raw('GROUP_CONCAT(grupo_pedido_items.razon_social) as productos'),
                'grupo_pedidos.zona',
                'grupo_pedidos.provincia',
                'grupo_pedidos.distrito',
                'grupo_pedidos.direccion',
                'grupo_pedidos.referencia',
                'grupo_pedidos.cliente_recibe',
                'grupo_pedidos.telefono',
                'grupo_pedidos.created_at',
                //'codigos' => DB::table('grupo_pedido_items')->selectRaw('GROUP_CONCAT(grupo_pedido_items.codigo)')->whereRaw('grupo_pedido_items.grupo_pedido_id=grupo_pedidos.id'),
                // 'productos' => DB::table('grupo_pedido_items')->selectRaw('GROUP_CONCAT(grupo_pedido_items.razon_social)')->whereRaw('grupo_pedido_items.grupo_pedido_id=grupo_pedidos.id'),
            ])
            ->whereNull('grupo_pedidos.deleted_at')
            ->groupBy([
                'grupo_pedidos.id',
                'grupo_pedidos.zona',
                'grupo_pedidos.provincia',
                'grupo_pedidos.distrito',
                'grupo_pedidos.direccion',
                'grupo_pedidos.referencia',
                'grupo_pedidos.cliente_recibe',
                'grupo_pedidos.telefono',
                'grupo_pedidos.created_at',
            ]);

        $motorizados = User::query()->where('rol', '=', 'MOTORIZADO')->whereNotNull('zona')->get();
        $color_zones = [];
        $color_zones['NORTE'] = 'warning';
        $color_zones['CENTRO'] = 'info';
        $color_zones['SUR'] = 'dark';
        if (is_array($request->exclude_ids) && count($request->exclude_ids) > 0) {
            $query->whereNotIn('grupo_pedidos.id', $request->exclude_ids);
        }

        /*
                $search_value = data_get($request->search, 'value');
              /*if($search_value && !empty($search_value)){
                    $query->orWhere('codigos','like','%'.$search_value.'%');
                }
        */

        return \DataTables::of($query->get())
            ->addColumn('codigos', function ($pedido) {
                return collect(explode(',', $pedido->codigos))->map(function ($codigo, $index) {
                    return ($index + 1) . ") <b>" . $codigo . "</b>";
                })->join('<hr class="my-1">');
            })
            ->addColumn('codigos_search', function ($pedido) {
                return collect(explode(',', $pedido->codigos))->map(function ($codigo, $index) {
                    return $codigo;
                })->join(',');
            })
            ->addColumn('productos', function ($pedido) {
                return collect(explode(',', $pedido->productos))->map(function ($codigo, $index) {
                    return ($index + 1) . ")" . $codigo;
                })->join('<hr class="my-1">');
            })
            ->addColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                $color = Pedido::getColorByCondicionEnvio(Pedido::RECEPCION_COURIER);
                $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span>
<span class="badge badge-success py-2" style="background-color: ' . $color . '!important;">' . Pedido::RECEPCION_COURIER . '</span>';
                return $badge_estado;
            })
            ->editColumn('zona', function ($pedido) {
                return "<b>" . $pedido->zona . "</b>";
            })
            ->addColumn('action', function ($pedido) use ($motorizados, $color_zones) {
                $btn = [];
                if ($pedido->motorizadoHistories->count() > 0) {
                    $btn [] = '<button data-motorizado-history="' . $pedido->motorizadoHistories->count() . '" class="btn btn-light rounded-circle"><i class="fa fa-motorcycle"></i></button>';
                }
                foreach ($motorizados as $motorizado) {
                    if (Str::contains($pedido->zona, $motorizado->zona)) {
                        $addClass = 'border border-danger';
                        $styleClass = 'box-shadow: rgb(84 84 84 / 40%) -5px 5px, rgb(157 157 157 / 10%) 0px 0px, rgb(229 229 229 / 5%) -25px 25px;';
                    } else {
                        $addClass = '';
                        $styleClass = '';
                    }

                    $btn[] = "<div class='text-center p-1'><button data-zona='$motorizado->zona' data-elTable='#tablaPrincipal" . Str::upper($motorizado->zona) . "' data-ajax-post='" . route('envios.distribuirsobres.asignarzona', ['grupo_pedido_id' => $pedido->id, 'motorizado_id' => $motorizado->id, 'zona' => Str::upper($motorizado->zona)]) . "'
 class='add-row-datatable $addClass btn btn-" . ($color_zones[Str::upper($motorizado->zona)] ?? 'info') . " btn-sm btn-block my-0' type='button' style='$styleClass'>
<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true' style='display: none'></span>
  <span class='sr-only' style='display: none'>" . (Str::upper($motorizado->zona)) . "</span>" . (Str::upper($motorizado->zona)) . "</button></div>";
                }
                if (count($motorizados) == 0) {
                    $btn[] = '<li class="list-group-item alert alert-warning p-8 text-center mb-0">No hay motorizados registrados</li>';
                }
                return "<ul class='d-flex'>" . join('', $btn) . "</ul>";
            })
            ->rawColumns(['action', 'condicion_envio', 'productos', 'codigos', 'zona'])
            ->make(true);

    }

    public function asignarZona(Request $request)
    {
        $this->validate($request, [
            'grupo_pedido_id' => 'required',
            'motorizado_id' => 'required',
            'zona' => 'required',
        ]);
        $pedidoGrupo = GrupoPedido::query()->findOrFail($request->grupo_pedido_id);

        return response()->json($pedidoGrupo);
    }

    public function agrupar(Request $request)
    {
        $this->validate($request, [
            'zona' => ['required', Rule::in(['NORTE', 'SUR', 'CENTRO'])],
            'motorizado_id' => 'required',
            'groups' => 'required|array',
        ]);
        $groups = GrupoPedido::query()->with(['pedidos'])->whereIn('id', $request->groups)->get();

        $zona = $request->get('zona');

        foreach ($groups as $grupo) {
            $pedidos = $grupo->pedidos;
            $firstProduct = collect($pedidos)->first();
            $cliente = $firstProduct->cliente;
            $lista_codigos = collect($pedidos)->pluck('codigo')->join(',');
            $lista_productos = DetallePedido::wherein("pedido_id", collect($pedidos)->pluck('id'))->pluck('nombre_empresa')->join(',');

            $groupData = [
                'condicion_envio_code' => Pedido::REPARTO_COURIER_INT,//RECEPCION CURRIER
                'condicion_envio' => Pedido::REPARTO_COURIER,//RECEPCION CURRIER
                'producto' => $lista_productos,
                'distribucion' => $zona,
                'destino' => $firstProduct->env_destino,
                'direccion' => $firstProduct->env_direccion,
                'fecha_recepcion' => now(),
                'codigos' => $lista_codigos,

                'estado' => '1',

                'cliente_id' => $cliente->id,
                'user_id' => $firstProduct->user_id,

                'nombre' => $firstProduct->env_nombre_cliente_recibe,
                'celular' => $firstProduct->env_celular_cliente_recibe,

                'nombre_cliente' => $cliente->nombre,
                'celular_cliente' => $cliente->celular,
                'icelular_cliente' => $cliente->icelular,

                'distrito' => $firstProduct->env_distrito,
                'referencia' => $firstProduct->env_referencia,
                'observacion' => $firstProduct->env_observacion,
                'cantidad' => count($pedidos),
                'motorizado_id' => $request->motorizado_id,
            ];

            if ($request->get("visualizar") == '1') {
                $grupos[] = $groupData;
            } else {
                $direcciongrupo = DireccionGrupo::create($groupData);
                $grupos[] = $direcciongrupo->refresh();
                Pedido::whereIn('id', collect($pedidos)->pluck('id'))->update([
                    'env_zona_asignada' => null,
                    'estado_ruta' => '1',
                    'condicion_envio_code' => Pedido::REPARTO_COURIER_INT,
                    'condicion_envio' => Pedido::REPARTO_COURIER,
                    'direccion_grupo' => $direcciongrupo->id,
                ]);
                PedidoMotorizadoHistory::query()
                    ->where([
                        'pedido_grupo_id' => $grupo->id,
                    ])
                    ->update([
                        'direccion_grupo_id' => $direcciongrupo->id,
                    ]);
                $grupo->delete();
            }
        }
        return $grupos;
    }

    public function desagrupar(Request $request)
    {
        $grupo = GrupoPedido::query()->findOrFail($request->grupo_id);
        $pedido = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->select([
                'pedidos.*',
                'detalle_pedidos.nombre_empresa'
            ])
            ->join('detalle_pedidos', 'pedidos.id', 'detalle_pedidos.pedido_id')
            ->activo()
            ->where('detalle_pedidos.estado', '1')
            ->findOrFail($request->pedido_id);

        DB::beginTransaction();
        $detach = $grupo->pedidos()->detach([$pedido->id]);

        if ($grupo->pedidos()->count() == 0) {
            $grupo->delete();
            return response()->json([
                'data' => null,
                'pedido' => $pedido,
                'detach' => $detach,
                'success' => true
            ]);
        }

        $grupoPedido = GrupoPedido::createGroupByPedido($pedido, true);
        $grupoPedido->pedidos()->attach($pedido->id, [
            'razon_social' => $pedido->nombre_empresa,
            'codigo' => $pedido->codigo,
        ]);

        DB::commit();
        return response()->json([
            'data' => $grupo->refresh()->load(['pedidos']),
            'grupo2' => $grupoPedido,
            'pedido' => $pedido,
            'detach' => $detach,
            'success' => true
        ]);
    }

}
