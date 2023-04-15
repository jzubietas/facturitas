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
use App\Models\SituacionClientes;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        $motorizados = [];
        if (auth()->user()->rol != User::ROL_ENCARGADO) {
            $motorizados = User::query()->where('rol', '=', 'MOTORIZADO')->whereNotNull('zona')->get();
        }


        return view('envios.distribuirsobres', compact('superasesor', 'motorizados', 'ver_botones_accion', 'distritos', 'departamento'));
    }

    public function datatable(Request $request)
    {
        $query = GrupoPedido::query()->with([
            'pedidos' => function (BelongsToMany $belongsToMany) {
                if (auth()->user()->rol == User::ROL_ENCARGADO) {
                    $usersasesores = User::whereIn('rol', [User::ROL_ASESOR, User::ROL_ASESOR_ADMINISTRATIVO])
                        ->where('estado', '1')
                        ->where('supervisor', Auth::user()->id)
                        ->pluck('id');
                    $belongsToMany->whereIn('pedidos.user_id', $usersasesores);
                    $belongsToMany->where('pedidos.env_celular_cliente_recibe', '<>', '999999999');
                }
                $belongsToMany->where('pedidos.estado', '1');
            },
            'motorizadoHistories'
        ])
            //->join('grupo_pedido_items', 'grupo_pedido_items.grupo_pedido_id', '=', 'grupo_pedidos.id')
            ->select([
                'grupo_pedidos.id',
                'grupo_pedidos.urgente',
                //DB::raw('GROUP_CONCAT(grupo_pedido_items.codigo) as codigos'),
                //DB::raw('GROUP_CONCAT(grupo_pedido_items.razon_social) as productos'),
                'grupo_pedidos.zona',
                'grupo_pedidos.provincia',
                'grupo_pedidos.distrito',
                'grupo_pedidos.direccion',
                'grupo_pedidos.referencia',
                'grupo_pedidos.cliente_recibe',
                'grupo_pedidos.telefono',
                'grupo_pedidos.created_at',
              DB::raw('(select p.condicion_envio_code  from grupo_pedido_items gpi inner join pedidos p on p.id =gpi.pedido_id where  gpi.grupo_pedido_id =grupo_pedidos.id limit 1) as condicion_envio_code'),
                // 'productos' => DB::table('grupo_pedido_items')->selectRaw('GROUP_CONCAT(grupo_pedido_items.razon_social)')->whereRaw('grupo_pedido_items.grupo_pedido_id=grupo_pedidos.id'),
            ])

            //->whereNull('grupo_pedidos.deleted_at')
            /*->groupBy([
                'grupo_pedidos.id',
                'grupo_pedidos.zona',
                'grupo_pedidos.provincia',
                'grupo_pedidos.distrito',
                'grupo_pedidos.direccion',
                'grupo_pedidos.referencia',
                'grupo_pedidos.cliente_recibe',
                'grupo_pedidos.telefono',
                'grupo_pedidos.created_at',
            ])*/
        ;

        $motorizados = User::query()->where('rol', '=', 'MOTORIZADO')->whereNotNull('zona')->get();
        $color_zones = [];
      $color_zones['NORTE'] = 'warning';
      $color_zones['CENTRO'] = 'info';
      $color_zones['SUR'] = 'dark';
        if (is_array($request->exclude_ids) && count($request->exclude_ids) > 0) {
            $query->whereNotIn('grupo_pedidos.id', $request->exclude_ids);
        }

        $search_value = trim(data_get($request->search, 'value', '') ?? '');
        if (!empty($search_value)) {
            $query->where(function ($query) use ($search_value) {
                $cols = ['grupo_pedidos.zona',
                    'grupo_pedidos.provincia',
                    'grupo_pedidos.distrito',
                    'grupo_pedidos.direccion',
                    'grupo_pedidos.referencia',
                    'grupo_pedidos.cliente_recibe',
                    'grupo_pedidos.telefono',];
                foreach ($cols as $col) {
                    $query->orWhere($col, 'like', '%' . $search_value . '%');
                }

                $query->orWhereIn('grupo_pedidos.id',
                    DB::table('grupo_pedido_items')
                        ->where('grupo_pedido_items.codigo', 'like', '%' . $search_value . '%')
                        ->orWhere('grupo_pedido_items.razon_social', 'like', '%' . $search_value . '%')
                        ->select('grupo_pedido_items.grupo_pedido_id')
                );

                /*if(auth()->user()->rol==User::ROL_ENCARGADO)
                {
                    $usersasesores = User::whereIn('users.rol', [User::ROL_ASESOR,User::ROL_ASESOR_ADMINISTRATIVO])
                        ->where('users.estado', '1')
                        ->where('users.supervisor', Auth::user()->id)
                        ->select(
                            DB::raw("users.id as id")
                        )
                        ->pluck('users.id');

                    $query->where('grupo_pedidos.id',
                      DB::table('grupo_pedidos_items')
                        ->where('grupo_pedidos_items.cliente_id');
                    );
                }*/
            });
        }
        /*if(auth()->user()->rol==User::ROL_ENCARGADO)
        {

        }*/

        $items = $query->get()->filter(fn(GrupoPedido $grupo) => $grupo->pedidos->count() > 0);

        return \DataTables::of($items)
            ->addColumn('codigos', function (GrupoPedido $grupo) {
                return $grupo->pedidos->pluck('codigo')->sort()
                    ->values()->map(fn($codigo, $index) => ($index + 1) . ") <b>" . $codigo . "</b>")->join('<hr class="my-1">');
            })
            ->addColumn('productos', function (GrupoPedido $grupo) {
                return $grupo->pedidos->sortBy(fn($pedido) => $pedido->codigo)->pluck('pivot.razon_social')->map(fn($codigo, $index) => ($index + 1) . ") <b>" . $codigo . "</b>")->join('<hr class="my-1">');
            })
            ->addColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                $color = Pedido::getColorByCondicionEnvio(Pedido::RECEPCION_COURIER);
                $textoEstado=Pedido::RECEPCION_COURIER;
                if ($pedido->condicion_envio_code == Pedido::RECOJO_COURIER_INT) {
                  $textoEstado=Pedido::RECOJO_COURIER;
                  $color = Pedido::getColorByCondicionEnvio(Pedido::RECOJO_COURIER);
                }
                $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span>
<span class="badge badge-success py-2" style="background-color: ' . $color . '!important;">' . $textoEstado. '</span>';


                return $badge_estado;
            })
            ->editColumn('zona', function ($pedido) {
                return "<b>" . $pedido->zona . "</b>";
            })
            ->addColumn('fecha_producto', function (GrupoPedido $grupo) {
                return $grupo->pedidos->sortBy(fn($pedido) => $pedido->codigo)->pluck('created_at')
                    ->map(fn($codigo, $index) => ($index + 1) . ") <b>" . $codigo->format('d-m-Y') . "</b>")->join('<hr class="my-1">');
            })
            ->addColumn('action', function ($pedido) use ($motorizados, $color_zones) {
                $btn = [];

                if (auth()->user()->rol != User::ROL_ENCARGADO) {
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
                }

                return "<ul class='d-flex'>" . join('', $btn) . "</ul>";
            })
            ->rawColumns(['action', 'condicion_envio', 'productos', 'codigos', 'zona', 'fecha_producto'])
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

    private function createDireccionGrupo($grupo, $groupData, $pedidosIds)
    {
        unset($groupData['pedido_id']);
        unset($groupData['pedido_codigo']);
        unset($groupData['pedido_nombre_empresa']);
        $direcciongrupo = DireccionGrupo::create($groupData);
        Pedido::whereIn('id', $pedidosIds)->update([
            'env_zona_asignada' => null,
            'estado_ruta' => '1',
            'condicion_envio' => (($groupData['cod_recojo']==1)? Pedido::REPARTO_RECOJO_COURIER : Pedido::REPARTO_COURIER),//reparto recojo courier
            'condicion_envio_code' => (($groupData['cod_recojo']==1)? Pedido::REPARTO_RECOJO_COURIER_INT : Pedido::REPARTO_COURIER_INT),
            'condicion_envio_at' => now(),
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
        DireccionGrupo::restructurarCodigos($direcciongrupo);
        return $direcciongrupo;
    }

    public function agrupar(Request $request)
    {
        $this->validate($request, [
            'zona' => ['required', Rule::in(['NORTE', 'SUR', 'CENTRO'])],
            'motorizado_id' => 'required',
            'groups' => 'required|array',
        ]);
        $groups = GrupoPedido::query()->whereIn('id', $request->groups)->get();

        $zona = $request->get('zona');

        $grupos = [];
        foreach ($groups as $grupo) {
            $pedidos = $grupo->pedidos()
                ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                ->where('detalle_pedidos.estado', '1')
                ->select([
                    'pedidos.*',
                    'detalle_pedidos.nombre_empresa'
                ])
                ->activo()
                ->get();
            $vcod_recojo=intval($grupo->cod_recojo);
            if ($grupo->zona != 'OLVA')
            {
                $firstProduct = collect($pedidos)->first();
                $cliente = $firstProduct->cliente;
                $lista_codigos = $pedidos->pluck('codigo')->join(',');
                $lista_productos = $pedidos->pluck('nombre_empresa')->join(',');;

                if (  $vcod_recojo ==0)
                {
                  $groupData = [
                    'condicion_envio_code' => Pedido::REPARTO_COURIER_INT,//RECEPCION CURRIER
                    'condicion_envio_at' => now(),
                    'condicion_envio' => Pedido::REPARTO_COURIER,//RECEPCION CURRIER
                    'distribucion' => $grupo->zona,
                    'destino' => $firstProduct->env_destino,
                    'direccion' => $firstProduct->env_direccion,//nro treking
                    'estado' => '1',
                    'codigos' => $lista_codigos,
                    'producto' => $lista_productos,
                    'cliente_id' => $cliente->id,
                    'user_id' => $firstProduct->user_id,
                    'nombre' => $firstProduct->env_nombre_cliente_recibe,
                    'celular' => $firstProduct->env_celular_cliente_recibe,
                    'gmlink' => $firstProduct->env_gmlink,
                    'nombre_cliente' => $cliente->nombre,
                    'celular_cliente' => $cliente->celular,
                    'icelular_cliente' => $cliente->icelular,
                    'distrito' => $firstProduct->env_distrito,
                    'referencia' => $firstProduct->env_referencia,//nro registro
                    'observacion' => $firstProduct->env_observacion,//rotulo
                    'motorizado_id' => $request->motorizado_id,
                    'identificador' => $cliente->user->identificador,
                    'cod_recojo' => $grupo->cod_recojo,
                    'env_sustento_recojo' => $grupo->env_sustento_recojo,
                  ];
                }
                else if($vcod_recojo == 1)
                {
                  $groupData = [
                    'condicion_envio_code' => Pedido::REPARTO_RECOJO_COURIER_INT,//ENTREGADO JEFE CURRIER
                    'condicion_envio_at' => now(),
                    'condicion_envio' => Pedido::REPARTO_RECOJO_COURIER,//ENTREGADO JEFE CURRIER
                    'distribucion' => $grupo->zona,
                    'destino' => $firstProduct->env_destino,
                    'direccion' => $firstProduct->env_direccion,//nro treking
                    'estado' => '1',
                    'codigos' => $lista_codigos,
                    'producto' => $lista_productos,
                    'cliente_id' => $cliente->id,
                    'user_id' => $firstProduct->user_id,
                    'nombre' => $firstProduct->env_nombre_cliente_recibe,
                    'celular' => $firstProduct->env_celular_cliente_recibe,
                    'gmlink' => $firstProduct->env_gmlink,
                    'nombre_cliente' => $cliente->nombre,
                    'celular_cliente' => $cliente->celular,
                    'icelular_cliente' => $cliente->icelular,
                    'distrito' => $firstProduct->env_distrito,
                    'referencia' => $firstProduct->env_referencia,//nro registro
                    'observacion' => $firstProduct->env_observacion,//rotulo
                    'motorizado_id' => $request->motorizado_id,
                    'identificador' => $cliente->user->identificador,
                    'cod_recojo' => $grupo->cod_recojo,
                    'env_sustento_recojo' => $grupo->env_sustento_recojo,
                  ];
                }

                if ($request->get("visualizar") == '1')
                {
                    $grupos[] = $groupData;
                }
                else {
                    $grupos[] = $this->createDireccionGrupo($grupo, $groupData, collect($pedidos)->pluck('id'))->refresh();
                    if($vcod_recojo  == 1)
                    {
                      $pedidosGruposPedidos = DB::table('grupo_pedido_items')->where('grupo_pedido_id', $grupo->id )->get();
                      foreach ($pedidos as $pedidosFila){
                        $pedidoUpdate = Pedido::where('id', $pedidosFila->id)->first();
                        $pedidoUpdate->update([
                          'condicion_envio' => Pedido::REPARTO_RECOJO_COURIER,
                          'condicion_envio_code' => Pedido::REPARTO_RECOJO_COURIER_INT,
                        ]);
                      }
                    }
                    $delete=GrupoPedido::where('id',$grupo->id)->delete();
                }
            }
            else {
              //OLVA
                $dividir = $pedidos->map(function (Pedido $pedido) use ($grupo, $request, $zona) {
                  $valcod_recojo=intval($grupo->cod_recojo);
                    $cliente = $pedido->cliente;
                    if($valcod_recojo  == 0){
                      return [
                      'condicion_envio_code' => Pedido::REPARTO_COURIER_INT,//RECEPCION CURRIER
                      'condicion_envio_at' => now(),
                      'condicion_envio' => Pedido::REPARTO_COURIER,//RECEPCION CURRIER
                      'distribucion' => $grupo->zona,
                      'destino' => $pedido->env_destino,
                      'direccion' => $pedido->env_tracking,//nro treking
                      //'fecha_recepcion' => now(),
                      'estado' => '1',
                      'cliente_id' => $cliente->id,
                      'user_id' => $pedido->user_id,
                      'pedido_id' => $pedido->id,
                      'pedido_codigo' => $pedido->codigo,
                      'pedido_nombre_empresa' => $pedido->nombre_empresa,
                      'nombre' => $pedido->env_nombre_cliente_recibe,
                      'celular' => $pedido->env_celular_cliente_recibe,
                      'nombre_cliente' => $cliente->nombre,
                      'celular_cliente' => $cliente->celular,
                      'icelular_cliente' => $cliente->icelular,
                      'distrito' => $pedido->env_distrito,
                      'referencia' => $pedido->env_numregistro,//nro registro
                      'observacion' => $pedido->env_rotulo,//rotulo
                      'motorizado_id' => $request->motorizado_id,
                      'identificador' => $cliente->user->identificador,
                        'cod_recojo' => $grupo->cod_recojo,
                        'env_sustento_recojo' => $grupo->env_sustento_recojo,
                    ];
                  }else{
                      return [
                        'condicion_envio_code' => Pedido::REPARTO_RECOJO_COURIER_INT,//ENTREGADO JEFE CURRIER
                        'condicion_envio_at' => now(),
                        'condicion_envio' => Pedido::REPARTO_RECOJO_COURIER,//ENTREGADO JEFE CURRIER
                        'distribucion' => $grupo->zona,
                        'destino' => $pedido->env_destino,
                        'direccion' => $pedido->env_tracking,//nro treking
                        //'fecha_recepcion' => now(),
                        'estado' => '1',
                        'cliente_id' => $cliente->id,
                        'user_id' => $pedido->user_id,
                        'pedido_id' => $pedido->id,
                        'pedido_codigo' => $pedido->codigo,
                        'pedido_nombre_empresa' => $pedido->nombre_empresa,
                        'nombre' => $pedido->env_nombre_cliente_recibe,
                        'celular' => $pedido->env_celular_cliente_recibe,
                        'nombre_cliente' => $cliente->nombre,
                        'celular_cliente' => $cliente->celular,
                        'icelular_cliente' => $cliente->icelular,
                        'distrito' => $pedido->env_distrito,
                        'referencia' => $pedido->env_numregistro,//nro registro
                        'observacion' => $pedido->env_rotulo,//rotulo
                        'motorizado_id' => $request->motorizado_id,
                        'identificador' => $cliente->user->identificador,
                        'cod_recojo' => $grupo->cod_recojo,
                        'env_sustento_recojo' => $grupo->env_sustento_recojo,
                      ];
                    }
                })
                    ->groupBy(fn($data) => join('_', [$data['distribucion'], $data['direccion']]))
                    ->values();
                foreach ($dividir as $items) {
                    $citems = collect($items);
                    $pedidos = $citems->pluck('pedido_id');
                    $groupData = $citems->first();
                    if ($request->get("visualizar") == '1') {
                        $groupData['codigos'] = $citems->pluck('pedido_codigo')->join(', ');
                        $groupData['producto'] = $citems->pluck('pedido_nombre_empresa')->join(', ');
                        $grupos[] = $groupData;
                    } else {
                        $grupos[] = $this->createDireccionGrupo($grupo, $groupData, $pedidos)->refresh();
                      if($grupo->cod_recojo == 1)
                      {
                        $pedidos = $grupo->pedidos()
                          ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
                          ->where('detalle_pedidos.estado', '1')
                          ->select([
                            'pedidos.*',
                            'detalle_pedidos.nombre_empresa'
                          ])
                          ->activo()
                          ->get();
                        //$pedidosGruposPedidos = DB::table('grupo_pedido_items')->where('grupo_pedido_id', $grupo->id )->get();
                        foreach ($pedidos as $pedidosFila){
                          $pedidoUpdate = Pedido::where('id', $pedidosFila->id)->first();
                          $pedidoUpdate->update([
                            'condicion_envio' => Pedido::REPARTO_RECOJO_COURIER,
                            'condicion_envio_code' => Pedido::REPARTO_RECOJO_COURIER_INT,
                          ]);
                        }
                      }
                      $delete=GrupoPedido::where('id',$grupo->id)->delete();
                    }
                }
            }
        }
        return $grupos;
    }

    public function desagrupar(Request $request)
    {
        $grupo = GrupoPedido::query()->findOrFail($request->grupo_id);
        $pedido = Pedido::activo()->findOrFail($request->pedido_id);

        DB::beginTransaction();
        $detach = $grupo->pedidos()->detach([$pedido->id]);
        $grupoPedido=null;
        if ($pedido->estado != 0) {

            if ($grupo->pedidos()->count() == 0) {
                $grupo->delete();
                return response()->json([
                    'data' => null,
                    'pedido' => $pedido,
                    'detach' => $detach,
                    'success' => true
                ]);
            }

            $grupoPedido = GrupoPedido::createGroupByPedido($pedido, true, true);
        }
        DB::commit();
        $grupo = $grupo->refresh()->load(['pedidos']);
        $grupo->codigos = $grupo->pedidos
            ->pluck('codigo')
            ->sort()
            ->values()
            ->map(fn($codigo, $index) => ($index + 1) . ") <b>" . $codigo . "</b>")
            ->join('<hr class="my-1">');
        $grupo->productos = $grupo->pedidos
            ->sortBy(fn($pedido) => $pedido->codigo)
            ->pluck('pivot.razon_social')
            ->map(fn($codigo, $index) => ($index + 1) . ") <b>" . $codigo . "</b>")
            ->join('<hr class="my-1">');

        return response()->json([
            'data' => $grupo,
            'grupo2' => $grupoPedido,
            'pedido' => $pedido,
            'detach' => $detach,
            'success' => true
        ]);
    }

}
