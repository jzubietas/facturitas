<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\DetallePedido;
use App\Models\DireccionGrupo;
use App\Models\Directions;
use App\Models\Distrito;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecojoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
  public function index(Request $request)
  {

    if ($request->has('datatable')) {
      $query = DireccionGrupo::
      join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
        ->join('users as u', 'u.id', 'c.user_id')
        /*->when($fecha_consulta != null, function ($query) use ($fecha_consulta) {
          $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta);
        })*/
        ->select([
          'direccion_grupos.*',
        ]);

      $query
        ->where('direccion_grupos.estado', '1')
        ->where('direccion_grupos.condicion_envio_code', Pedido::ENTREGADO_RECOJO_JEFE_OPE_INT);

      /*if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
        $query = $query->where('direccion_grupos.motorizado_id', '=', auth()->id());
      }*/


      //add_query_filtros_por_roles($query, 'u');
      return datatables()->query(DB::table($query))
        ->addIndexColumn()
        /*->editColumn('fecha_recepcion', function ($pedido) {
          if ($pedido->fecha_recepcion != null) {
            return Carbon::parse($pedido->fecha_recepcion)->format('d-m-Y h:i A');
          } else {
            return '';
          }
        })*/
        ->editColumn('condicion_envio', function ($pedido) {
          $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);

          return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
        })
        ->addColumn('action', function ($pedido)  {
          $btn = [];
          $btn []='<button type="button" class="btn btn-warning btn-sm" data-target="#modal-envio-recojo" data-toggle="modal" data-grupopedido="' . $pedido->id . '" data-codigos="' . $pedido->codigos . '"><i class="fas fa-check-circle"></i> Recibir</a>';
          return join('', $btn);
        })
        ->rawColumns(['action', 'condicion_envio'])
        ->toJson();
    }
    return view('operaciones.recojo.index');
  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

  public function courierConfirmRecojo(Request $request)
  {
    $envio = DireccionGrupo::where("id", $request->input_confirmrecojomotorizado)->first();


    DireccionGrupo::cambiarCondicionEnvio($envio, Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT);

      $pedidos = $envio->pedidos()
          ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
          ->where('detalle_pedidos.estado', '1')
          ->select([
              'pedidos.*',
              'detalle_pedidos.nombre_empresa'
          ])
          ->activo()
          ->get();

      $firstProduct = collect($pedidos)->first();
      //return $firstProduct;
      $asesor=Pedido::where('id',$firstProduct->id)->first()->user_id;//3
      $operario=User::where('rol',User::ROL_ASESOR)->where('id',$asesor)->first()->operario;
      $jefeope=User::where('rol',User::ROL_OPERARIO)->where('id',$operario)->first()->jefe;

      $direccion_nueva=Directions::whereIn('rol',[User::ROL_JEFE_OPERARIO,User::ROL_JEFE_COURIER])->where('user_id',$jefeope)->first();
      $envio_update ="sin pago";
          $envio_update = DireccionGrupo::where("id", $request->input_confirmrecojomotorizado)->update([
            'nombre' => $direccion_nueva->cliente,
            'celular'=>$direccion_nueva->numero_recojo,
            'direccion'=>$direccion_nueva->direccion_recojo,
            'referencia'=>$direccion_nueva->referencia,
            'distrito' => $direccion_nueva->distrito ,
            'destino'=>$direccion_nueva->destino,
          ]);
      //Arma logica de NOTIFICACION
      if ($jefeope){
          $alerta = Alerta::create([
              'user_id' => $jefeope,
              'metadata' => $firstProduct->id,
              'tipo'=>'warning',
              'subject' => 'RECOJO',
              'message' => 'Vas a recibir un sobre con codigo: '.$firstProduct->codigo.' - '.$firstProduct->nombre_empresa.'. Por favor, atento a la llamada del motorizado.',
              'date_at' => now(),
          ]);
      }
    return $envio_update;

      foreach ($pedidos as $pedidosFila){
          $pedidoUpdate = Pedido::where('id', $pedidosFila->id)->first();
          $pedidoUpdate->update([
              'env_direccion'=>$direccion_nueva->direccion_recojo,
              'direccion'=>$direccion_nueva->direccion_recojo,
              'env_celular_cliente_recibe'=>$direccion_nueva->numero_recojo,
              'env_nombre_cliente_recibe'=>$direccion_nueva->cliente,
              'env_distrito'=>$direccion_nueva->distrito,
              'env_destino'=>$direccion_nueva->destino,
              'env_referencia'=>$direccion_nueva->referencia,
              'condicion_envio' => Pedido::CONFIRMAR_RECOJO_MOTORIZADO,
              'condicion_envio_code' => Pedido::CONFIRMAR_RECOJO_MOTORIZADO_INT,
          ]);
      }

    return response()->json(['html' => $envio->id]);
  }

  public function courierRecojoenviarope(Request $request)
  {
    $envio = DireccionGrupo::where("id", $request->input_recojoenviarope)->first();
    DireccionGrupo::cambiarCondicionEnvio($envio, Pedido::ENTREGADO_RECOJO_JEFE_OPE_INT);
    PedidoMovimientoEstado::create([
      'pedido' => $request->input_recojoenviarope,
      'condicion_envio_code' => Pedido::ENTREGADO_RECOJO_JEFE_OPE_INT,
      'fecha_salida'=>now(),
      'notificado' => 0
    ]);

    return response()->json(['html' => $envio->id]);
    /*return response()->json(['html' => $request->all()]);*/
  }

  public function RegistrarRecojo(Request $request)
  {
    $attach_pedidos_data = [];
    $Nombre_recibe = $request->Nombre_recibe;
    $celular_id = $request->celular_id;
    $direccion_recojo = $request->direccion_recojo;
    $referencia_recojo = $request->referencia_recojo;
    $observacion_recojo = $request->observacion_recojo;
    $gm_link = $request->gm_link;
    $direccion_entrega = $request->direccion_entrega;
    $sustento_recojo = $request->sustento_recojo;
    $pedido_concatenado = explode(",", $request->pedido_concatenado);
    $distrito_recojo=$request->distrito_recojo;

    //$array_pedidos = collect(explode(",", $pedido_concatenado))->filter()->map(fn($id) => intval($id))->all();
    //$count_pedidos = count((array)$array_pedidos);

    $contar=0;
    $dirgrupo=0;
    $zona_distrito = Distrito::where('distrito', $distrito_recojo)->first();
    foreach ($pedido_concatenado as $pedidoid) {
      //$pedido = Pedido::find($pedidoid);

      $pedido = Pedido::where("id", $pedidoid)->first();
      $dirgrupo = $pedido->direccion_grupo;
      Pedido::where("id", $pedidoid)->update([
        'direccion_grupo' => null,
        'destino' => 'LIMA',
        'env_destino' => 'LIMA',
        'env_zona_asignada' => null,
        'env_zona'=>$zona_distrito->zona,
        'env_cantidad' => 0,
        'env_tracking' => '',
        'env_numregistro' => '',
        'env_rotulo' => '',
        'env_importe' => 0.00,
        'estado_ruta' => 0,
        'fecha_salida' => null,
        "env_nombre_cliente_recibe" => $Nombre_recibe,
        "env_celular_cliente_recibe" => $celular_id,
        'env_distrito'=>$zona_distrito->distrito,
        "env_direccion" => $direccion_recojo,
        "env_referencia" => $referencia_recojo,
        "env_observacion" => $observacion_recojo,
        "env_gmlink" => $gm_link,
        "env_sustento" => $sustento_recojo,
        "condicion_envio" => Pedido::RECOJO_COURIER,
        "condicion_envio_code" => Pedido::RECOJO_COURIER_INT,
        "estado_sobre"=>1
      ]);

      $pedido = Pedido::where("id", $pedidoid)->first();

      $dp_empresa = DetallePedido::activo()->where("pedido_id", $pedidoid)->first();
      if ( in_array($pedido->condicion_envio_code ,[Pedido::RECEPCION_COURIER_INT,Pedido::RECOJO_COURIER_INT] )) {
        $attach_pedidos_data[$pedidoid] = [
          'razon_social' => $dp_empresa->nombre_empresa,
          'codigo' => $dp_empresa->codigo,
        ];
      }

      //$grupoCreatePedido = GrupoPedido::createGroupByPedido($pedido, true, true);

    }

    if (count($attach_pedidos_data) > 0)
    {
      $grupoPedido = GrupoPedido::createGroupByArray([
        "zona" => $zona_distrito->zona,
        "provincia" => $zona_distrito->provincia,
        'distrito' => $zona_distrito->distrito,
        'direccion' => $direccion_recojo,
        'referencia' => $referencia_recojo,
        'cliente_recibe' => $Nombre_recibe,
        'telefono' => $celular_id,
         'cod_recojo'=>'1',
         'env_sustento_recojo'=>$sustento_recojo
      ]);
      $grupoPedido->pedidos()->syncWithoutDetaching($attach_pedidos_data);
    }

    return response()->json(['html' => 1,'direccion_grupo' => $dirgrupo,'contador'=>$contar]);

  }
}
