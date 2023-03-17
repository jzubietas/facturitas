<?php

namespace App\Http\Controllers;

use App\Events\PedidoAtendidoEvent;
use App\Events\PedidoEntregadoEvent;
use App\Events\PedidoEvent;
use App\Models\Cliente;
use App\Models\Departamento;
use App\Models\DetallePago;
use App\Models\DetallePedido;
use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\DireccionPedido;
use App\Models\Distrito;
use App\Models\GastoEnvio;
use App\Models\GastoPedido;
use App\Models\GrupoPedido;
use App\Models\ImagenAtencion;
use App\Models\ImagenPedido;
use App\Models\PedidoMovimientoEstado;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Porcentaje;
use App\Models\Provincia;
use App\Models\Ruc;
use App\Notifications\PedidoNotification;
use Carbon\Carbon;
use Exception;
use Facade\FlareClient\Http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use DataTables;

class SobreController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return '';
    }

    public function cargadistritos()
    {
        $dis = Distrito::cargaDistrito();
        return $dis;
    }

    public function Sobresporenviar()
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
            ->select([
                'distrito',
                DB::raw("concat(distrito,' - ',zona) as distritonam"),
                'zona'
            ])->orderBy('distrito')->get();

        $distritos_recojo = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->WhereNotIn('distrito', ['CHACLACAYO', 'CIENEGUILLA', 'LURIN', 'PACHACAMAC', 'PUCUSANA', 'PUNTA HERMOSA', 'PUNTA NEGRA', 'SAN BARTOLO', 'SANTA MARIA DEL MAR'])
            ->select([
                'distrito',
                DB::raw("concat(distrito,' - ',zona) as distritonam"),
                'zona'
            ])->orderBy('distrito')->get();

        $departamento = Departamento::where('estado', "1")
            ->pluck('departamento', 'departamento');

        $superasesor = User::where('rol', 'Super asesor')->count();

        $user_id = User::where('estado', '1')->whereIn("rol", [User::ROL_ASESOR, User::ROL_ASESOR_ADMINISTRATIVO]);
        if (auth()->user()->rol == 'Llamadas') {
            $user_id = $user_id->where('llamada', Auth::user()->id);
        } else if (auth()->user()->rol == 'Asesor') {
            $user_id = $user_id->where('identificador', Auth::user()->identificador);
        }
        $user_id = $user_id->select([
            'id', 'identificador', 'letra', 'exidentificador'
        ])->orderBy('exidentificador')->get();

        //->pluck('identificador', 'id');


        return view('sobres.porEnviar', compact('superasesor', 'ver_botones_accion', 'distritos', 'distritos_recojo', 'departamento', 'user_id'));
    }

    public function Sobresporenviartabla(Request $request)
    {
        $pedidos = null;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->join('users as u', 'pedidos.user_id', 'u.id')
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select([
                'pedidos.id',
                'pedidos.cliente_id',
                'c.nombre as nombres',
                'c.celular as celulares',
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
                'pedidos.destino',
                'pedidos.direccion',
                'pedidos.da_confirmar_descarga',
                'dp.envio_doc',
                'dp.fecha_envio_doc',
                'dp.cant_compro',
                'dp.fecha_envio_doc_fis',
                'dp.foto1',
                'dp.foto2',
                'dp.fecha_recepcion',
                'pedidos.devuelto',
                'pedidos.cant_devuelto',
                'pedidos.returned_at',
                'pedidos.observacion_devuelto',
                'pedidos.estado_sobre',
                'pedidos.estado_ruta',
                'pedidos.pendiente_anulacion',
                'pedidos.estado',
            ])
            ->where('pedidos.estado', '1')
            ->where('pedidos.pendiente_anulacion', '0')
            ->whereIn('pedidos.condicion_envio_code', [
                //Pedido::EN_ATENCION_OPE_INT,
                //Pedido::POR_ATENDER_OPE_INT,
                //Pedido::ATENDIDO_OPE_INT,
                //Pedido::ENVIO_COURIER_JEFE_OPE_INT,
                Pedido::RECIBIDO_JEFE_OPE_INT,
                Pedido::RECEPCION_COURIER_INT,
            ])
            ->sinDireccionEnvio();

        if (Auth::user()->rol == "Operario") {
            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->Where('users.operario', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == "Jefe de operaciones") {
            $operarios = User::where('users.rol', 'Operario')
                ->where('users.estado', '1')
                ->where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->WhereIn('users.operario', $operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->WhereIn('u.identificador', $asesores);

        } else if (Auth::user()->rol == "Asesor") {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);

        } else if (Auth::user()->rol == "Super asesor") {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);

        } else if (Auth::user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
            $pedidos = $pedidos->Where('u.identificador', Auth::user()->identificador);
        } else if (Auth::user()->rol == "Encargado") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos = $pedidos->whereIn('u.identificador', $usersasesores);
        }
        return Datatables::of(DB::table($pedidos))
            ->addIndexColumn()
            ->addColumn('condicion_envio_color', function ($pedido) {
                return Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
            })
            ->editColumn('condicion_envio', function ($pedido) {
                $badge_estado = '';
                if ($pedido->pendiente_anulacion == '1') {
                    $badge_estado .= '<span class="badge badge-danger">' . Pedido::PENDIENTE_ANULACION . '</span>';
                    return $badge_estado;
                }
                if ($pedido->estado == '0') {
                    $badge_estado .= '<span class="badge badge-danger">' . Pedido::ANULADO . '</span>';
                    return $badge_estado;
                }
                if ($pedido->estado_sobre == '1') {
                    $badge_estado .= '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important;">Direccion agregada</span>';
                }
                if ($pedido->estado_ruta == '1') {
                    $badge_estado .= '<span class="badge badge-success " style="background-color: #00bc8c !important;
                            padding: 4px 8px !important;
                            font-size: 8px;
                            margin-bottom: -4px;
                            color: black !important;">Con ruta</span>';
                }
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $badge_estado .= '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                return $badge_estado;
            })
            ->addColumn('action', function ($pedido) {
                $btn = '';
                return $btn;
            })
            ->rawColumns(['action', 'condicion_envio'])
            ->make(true);

    }

    public function sobreenvioshistorial(Request $request)
    {
        if (!$request->provincialima) {
            $historicos = DireccionEnvio::where("id", 0)->get();
            return Datatables::of($historicos)
                ->addIndexColumn()
                ->addColumn('action', function ($historico) {
                    $btn = '';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

        } else {
            $mirol = Auth::user()->rol;
            $query = null;
            if ($request->provincialima == "PROVINCIA") {
                $query = GastoEnvio::select(
                    'gasto_envios.*',
                    'clientes.nombre',
                    'clientes.celular as recibe',
                )
                    ->join('clientes', 'clientes.id', '=', 'gasto_envios.cliente_id')
                    ->where('gasto_envios.estado', '1')
                    ->where("gasto_envios.salvado", '1')
                    ->where('gasto_envios.cliente_id', $request->cliente_id);

                return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('action', function ($historico) {
                        $btn = '';
                        return $btn;
                    })
                    ->editColumn('foto', function ($pedido) {
                        $html = collect(explode(',', $pedido->tracking))->trim()->map(fn($f) => '<b>( ' . Carbon::parse($pedido->created_at)->format('d-m-Y') . ' )' . $f . '</b>')->join('<br>') . '<br>';


                        $html .= collect(explode(',', $pedido->foto))->trim()->map(fn($f) => '<a target="_blank" href="' .
                            Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rótulo</a>')->join('<br>');

                        $html .= '<p>';
                        return $html;
                    })
                    ->rawColumns(['action', 'foto'])
                    ->make(true);

            } else if ($request->provincialima == "LIMA") {
                $query = DireccionEnvio::query()->select(
                    'direccion_envios.*',
                    'clientes.nombre',
                    'clientes.celular as recibe',
                )
                    ->where('direccion_envios.estado', '1')
                    ->join('clientes', 'clientes.id', '=', 'direccion_envios.cliente_id')
                    ->where("direccion_envios.salvado", '1')
                    ->where('direccion_envios.cliente_id', '=', $request->cliente_id);
                //$historicos=$query->get();
                return Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('action', function ($historico) {
                        $btn = '';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

            }

        }
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {/*return $request->all();*/
        $detallepedido = DetallePedido::where('pedido_id', $pedido->id)->first();
        try {
            DB::beginTransaction();

            // ALMACENANDO DETALLES
            $codigo = $request->codigo;
            $nombre_empresa = $request->nombre_empresa;
            $mes = $request->mes;
            $anio = $request->anio;
            $ruc = $request->ruc;
            $cantidad = $request->cantidad;
            $tipo_banca = $request->tipo_banca;
            $porcentaje = $request->porcentaje;
            $courier = $request->courier;
            $descripcion = $request->descripcion;
            $nota = $request->nota;
            $contP = 0;

            $files = $request->file('adjunto');
            $destinationPath = base_path('public/storage/adjuntos/');
            $cont = 0;

            if (isset($files)) {
                foreach ($files as $file) {
                    $file_name = Carbon::now()->second . $file->getClientOriginalName(); //Get file original name
                    $file->move($destinationPath, $file_name);

                    ImagenPedido::create([
                        'pedido_id' => $pedido->id,
                        'adjunto' => $file_name,
                        'estado' => '1'
                    ]);

                    $cont++;
                }
            }

            while ($contP < count((array)$codigo)) {
                $detallepedido->update([
                    'codigo' => $codigo[$contP],
                    'nombre_empresa' => $nombre_empresa[$contP],
                    'mes' => $mes[$contP],
                    'anio' => $anio[$contP],
                    'ruc' => $ruc[$contP],
                    'cantidad' => $cantidad[$contP],
                    'tipo_banca' => $tipo_banca[$contP],
                    'porcentaje' => $porcentaje[$contP],
                    'ft' => ($cantidad[$contP] * $porcentaje[$contP]) / 100,
                    'courier' => $courier[$contP],
                    'total' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'saldo' => (($cantidad[$contP] * $porcentaje[$contP]) / 100) + $courier[$contP],
                    'descripcion' => $descripcion[$contP],
                    'nota' => $nota[$contP]
                ]);

                $contP++;
            }

            //ACTUALIZAR PORCENTAJE EN CLIENTE
            $porcentaje = Porcentaje::where('cliente_id', $pedido->cliente_id)
                ->where('nombre', $detallepedido->tipo_banca);
            $porcentaje->update([
                'porcentaje' => $detallepedido->porcentaje
            ]);

            //ACTUALIZAR MODIFICACION AL PEDIDO
            $pedido->update([
                'modificador' => 'USER' . Auth::user()->id
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            /*DB::rollback();
            dd($th);*/
        }

        if (Auth::user()->rol == "Asesor") {
            return redirect()->route('pedidos.mispedidos')->with('info', 'actualizado');
        } else
            return redirect()->route('pedidos.index')->with('info', 'actualizado');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::find($pedido->id);
        $pedido->update([
            'motivo' => $request->motivo,
            'responsable' => $request->responsable,
            'condicion' => 'ANULADO',
            'modificador' => 'USER' . Auth::user()->id,
            'estado' => '0'
        ]);

        $detalle_pedidos->update([
            'estado' => '0'
        ]);

        //ACTUALIZAR QUE CLIENTE NO DEBE
        $cliente = Cliente::find($pedido->cliente_id);

        $pedido_deuda = Pedido::where('cliente_id', $pedido->cliente_id)//CONTAR LA CANTIDAD DE PEDIDOS QUE DEBE
        ->where('pagado', '0')
            ->count();
        if ($pedido_deuda == 0) {//SINO DEBE NINGUN PEDIDO EL ESTADO DEL CLIENTE PASA A NO DEUDA(CERO)
            $cliente->update([
                'deuda' => '0'
            ]);
        }

        return redirect()->route('pedidos.index')->with('info', 'eliminado');
    }

    public function destroyid(Request $request)
    {
        if (!$request->hiddenID) {
            $html = '';
        } else {
            Pedido::find($request->hiddenID)->update([
                'motivo' => $request->motivo,
                'responsable' => $request->responsable,
                'condicion' => 'ANULADO',
                'modificador' => 'USER' . Auth::user()->id,
                'estado' => '0'
            ]);

            //$detalle_pedidos = DetallePedido::find($request->hiddenID);
            $detalle_pedidos = DetallePedido::where('pedido_id', $request->hiddenID)->first();

            $detalle_pedidos->update([
                'estado' => '0'
            ]);

            $html = $detalle_pedidos;
        }
        return response()->json(['html' => $html]);
    }

    public function pedidosgrupotabla(Request $request)
    {
        $pedidos = null;
        if (!$request->direcciongrupo) {
        } else {
            //$idrequest=explode("_",$request->direcciongrupo);

            //return $request->direcciongrupo;
            $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select([
                    'dp.pedido_id', 'dp.codigo', 'dp.nombre_empresa'
                ])
                ->where("direccion_grupo", $request->direcciongrupo);

            $pedidos = $pedidos->get();

            return Datatables::of($pedidos)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function EnvioDesvincular(Request $request)
    {
        //return $request->all();
        $pedidos = $request->pedidos;/*7326,7327*/
        $direcciongrupoId = $request->direcciongrupo;/*9206*/
        //$observaciongrupo = $request->observaciongrupo;
        if (!$request->pedidos) {
            return '0';
        } else {
            $removePedidosIds = collect(explode(",", $pedidos))->map(fn($cod) => trim($cod))->filter()->values();

            $direcciongrupo = DireccionGrupo::findOrFail($direcciongrupoId);

            $pedidos = Pedido::whereIn("pedidos.id", $removePedidosIds)->where('direccion_grupo', $direcciongrupo->id)->get();

            DB::beginTransaction();
            if (count($pedidos) > 0) {
                foreach ($pedidos as $pedido) {
                    GrupoPedido::createGroupByPedido($pedido, false, true);

                    if ($request->tipoajax == 2) {
                        $pedido->update([
                            "condicion_envio" => pedido::ENVIO_COURIER_JEFE_OPE,
                            "condicion_envio_code" => pedido::ENVIO_COURIER_JEFE_OPE_INT,
                            "direccion_grupo" => null
                        ]);
                    } else {
                        $pedido->update([
                            "condicion_envio" => pedido::RECEPCION_COURIER,
                            "condicion_envio_code" => pedido::RECEPCION_COURIER_INT,
                            //"observacion_devuelto" => $observaciongrupo,
                            "direccion_grupo" => null
                        ]);
                    }

                }
            }
            DireccionGrupo::restructurarCodigos($direcciongrupo);
            DB::commit();
            return response()->json(['html' => $removePedidosIds]);
        }
    }




  public function RetornoRecojo(Request $request)
  {
    $direccion_grupo = DireccionGrupo::where('id', $request->direccion_grupo)->first();
    $pedidos = Pedido::where('direccion_grupo', $request->direccion_grupo)->activo()->get();
    $sustento_recojo = $request->sustento_recojo;
    $pedido_concatenado = explode(",", $request->pedido_concatenado);

    $contar=0;
    $dirgrupo=0;
    foreach ($pedidos as $pedidoid) {
      $pedido = Pedido::where("id", $pedidoid->id)->first();
      if ($pedido) {
        $contar++;
        $dirgrupo = $pedido->direccion_grupo;
        if ($dirgrupo) {
          $contar++;

            $direccionJefeOperaciones = User::join('directions as d',' direction.user_id',' user.id')->where('user.id',  $pedido->user_id)->select([
              'd.user_id',
              'd.rol',
              'd.direccion_recojo',
              'd.numero_recojo',
              'user.name',
              'user.referencia',
              'user.direccion',
            ])->first();


          PedidoMovimientoEstado::create([
            'condicion_envio_code' => Pedido::RECOJO_COURIER_INT,
            'fecha' => now(),
            'pedido' => $pedido->id,
            'json_envio' => json_encode(array(
              "recojo" => true,
              'direccion_grupo' => null,
              'destino' => 'LIMA',
              'env_destino' => 'LIMA',
              'env_zona_asignada' => null,
              'env_cantidad' => 0,
              'env_tracking' => '',
              'env_numregistro' => '',
              'env_rotulo' => '',
              'env_importe' => 0.00,
              'estado_ruta' => 0,
              'fecha_salida' => null,
              "env_sustento" => $sustento_recojo,
              'condicion_envio' => Pedido::RECOJO_COURIER,
              'condicion_envio_code' => Pedido::RECOJO_MOTORIZADO_INT
            ))
          ]);
          $pedido->update([
            'direccion_grupo' => null,
            'destino' => 'LIMA',
            'env_destino' => 'LIMA',
            'env_zona_asignada' => null,
            'env_cantidad' => 0,
            'env_tracking' => '',
            'env_numregistro' => '',
            'env_rotulo' => '',
            'env_importe' => 0.00,
            'estado_ruta' => 0,
            'fecha_salida' => null,
            "env_nombre_cliente_recibe" => $direccionJefeOperaciones->name,
            "env_celular_cliente_recibe" => $direccionJefeOperaciones->celular,
            "env_direccion" => $direccionJefeOperaciones->direccion_recojo->referencia,
            "env_referencia" => $direccionJefeOperaciones->referencia,
            "env_observacion" => $direccionJefeOperaciones->direccion,
            /*"env_sustento" => $pedido->env_sustento,*/
            'condicion_envio' => Pedido::RECOJO_COURIER,
            'condicion_envio_code' => Pedido::RECOJO_COURIER_INT
          ]);

          //GrupoPedido::createGroupByPedido($pedido, true, true);
          DireccionGrupo::createByPedido($pedido);


        }
      }
    }
    return response()->json(['html' => 1,'direccion_grupo' => $dirgrupo,'contador'=>$contar]);
  }

}
