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
use App\Models\ImagenAtencion;
use App\Models\ImagenPedido;
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

    }

    public function Sobresporenviar()
    {
        $ver_botones_accion = 1;

        if(Auth::user()->rol == "Asesor")
        {
            $ver_botones_accion = 0;
        }else if(Auth::user()->rol == "Super asesor"){
            $ver_botones_accion = 0;
        }else if(Auth::user()->rol == "Encargado"){
            $ver_botones_accion = 1;
        }else{
            $ver_botones_accion = 1;
        }
        
        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
                            ->where('estado', '1')
                            ->WhereNotIn('distrito' ,['CHACLACAYO','CIENEGUILLA','LURIN','PACHACAMAC','PUCUSANA','PUNTA HERMOSA','PUNTA NEGRA','SAN BARTOLO','SANTA MARIA DEL MAR'])
                            ->select([
                                'distrito',                                
                                DB::raw("concat(distrito,' - ',zona) as distritonam"),
                                'zona'
                            ]);
                            //->pluck('distritonam', 'distrito');

        $departamento = Departamento::where('estado', "1")
                ->pluck('departamento', 'departamento');

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('sobres.porEnviar', compact('superasesor','ver_botones_accion','distritos','departamento'));
    }

    public function Sobresporenviartabla(Request $request)
    {
        $pedidos=null;

        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
                ->join('users as u', 'pedidos.user_id', 'u.id')
                ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select(
                    'pedidos.id',
                    'pedidos.cliente_id',
                    // 'c.nombre as nombres',
                    // 'c.celular as celulares',
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
                )
                ->where('pedidos.estado', '1')
                //->whereIn('pedidos.envio', [Pedido::ENVIO_CONFIRMAR_RECEPCION,Pedido::ENVIO_RECIBIDO]) // ENVIADO CONFIRMAR RECEPCION Y ENVIADO RECIBIDO
                ->whereIn('pedidos.condicion_envio_code', [Pedido::ENVIADO_OPE_INT,Pedido::RECEPCION_COURIER_INT,Pedido::ENVIO_COURIER_JEFE_OPE_INT]) // ENVIADO CONFIRMAR RECEPCION Y ENVIADO RECIBIDO
                ->where('dp.estado', '1');
                /*->groupBy(
                    'pedidos.id',
                    'pedidos.cliente_id',
                    'c.nombre',
                    'c.celular',
                    'u.identificador',
                    'dp.codigo',
                    'dp.nombre_empresa',
                    'dp.total',
                    'pedidos.condicion',
                    'pedidos.created_at',
                    'pedidos.condicion_envio',
                    'pedidos.envio',
                    'pedidos.destino',
                    'pedidos.direccion',
                    'dp.envio_doc',
                    'dp.fecha_envio_doc',
                    'dp.cant_compro',
                    'dp.fecha_envio_doc_fis',
                    'dp.foto1',
                    'dp.foto2',
                    'dp.fecha_recepcion'
                );*/
        if(Auth::user()->rol == "Operario"){
            $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> Where('users.operario',Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos=$pedidos->WhereIn('u.identificador',$asesores);

        }else if(Auth::user()->rol == "Jefe de operaciones"){
            $operarios = User::where('users.rol', 'Operario')
                -> where('users.estado', '1')
                -> where('users.jefe', Auth::user()->id)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $asesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                ->WhereIn('users.operario',$operarios)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos=$pedidos->WhereIn('u.identificador',$asesores);

        }else if(Auth::user()->rol == "Asesor"){
            $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);

        }
        else if(Auth::user()->rol == "Super asesor"){
            $pedidos=$pedidos->Where('u.identificador',Auth::user()->identificador);

        }
        else if(Auth::user()->rol == "Encargado"){

            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $pedidos=$pedidos->whereIn('u.identificador',$usersasesores);
        }
        return Datatables::of(DB::table($pedidos))
                    ->addIndexColumn()
                    ->addColumn('action', function($pedido){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

    }

    public function sobreenvioshistorial(Request $request)
    {
        if(!$request->provincialima)
        {
            $historicos=DireccionEnvio::where("id",0)->get();
            return Datatables::of($historicos)
                     ->addIndexColumn()
                     ->addColumn('action', function($historico){
                         $btn='';
                         return $btn;
                     })
                     ->rawColumns(['action'])
                     ->make(true);

        }else{
            $mirol=Auth::user()->rol;
            $query = null;
            if($request->provincialima=="PROVINCIA")
            {
                $query=GastoEnvio::select(
                    'gasto_envios.id',
                    'gasto_envios.tracking',
                    'gasto_envios.registro',
                    )
                ->where('gasto_envios.estado', '1')
                ->where("gasto_envios.salvado",'1')
                ->where('gasto_envios.cliente_id', $request->cliente_id);

                return Datatables::of($historicos)
                     ->addIndexColumn()
                     ->addColumn('action', function($historico){
                         $btn='';
                         return $btn;
                     })
                     ->rawColumns(['action'])
                     ->make(true);

            }else if($request->provincialima=="LIMA")
            {
                $query=DireccionEnvio::select(
                        'direccion_envios.id',
                        'direccion_envios.distrito',
                        'direccion_envios.direccion',
                        'direccion_envios.referencia',
                        'direccion_envios.nombre',
                        'direccion_envios.celular',
                        )
                    ->where('direccion_envios.estado', '1')
                    //->where("direccion_envios.salvado",'1')
                    ->where('direccion_envios.cliente_id', $request->cliente_id);

                $historicos=$query->get();
                return Datatables::of($historicos)
                     ->addIndexColumn()
                     ->addColumn('action', function($historico){
                         $btn='';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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

                if (isset($files)){
                    foreach ($files as $file){
                        $file_name = Carbon::now()->second.$file->getClientOriginalName(); //Get file original name
                        $file->move($destinationPath , $file_name);

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
                        'ft' => ($cantidad[$contP]*$porcentaje[$contP])/100,
                        'courier' => $courier[$contP],
                        'total' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
                        'saldo' => (($cantidad[$contP]*$porcentaje[$contP])/100)+$courier[$contP],
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
                    'modificador' => 'USER'.Auth::user()->id
                ]);

            DB::commit();
            }
        catch (\Throwable $th) {
            throw $th;
            /*DB::rollback();
            dd($th);*/
        }

        if(Auth::user()->rol == "Asesor"){
            return redirect()->route('pedidos.mispedidos')->with('info', 'actualizado');
        }
        else
            return redirect()->route('pedidos.index')->with('info', 'actualizado');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pedido $pedido)
    {
        $detalle_pedidos = DetallePedido::find($pedido->id);
        $pedido->update([
            'motivo' => $request->motivo,
            'responsable' => $request->responsable,
            'condicion' => 'ANULADO',
            'modificador' => 'USER'.Auth::user()->id,
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
        if($pedido_deuda == 0){//SINO DEBE NINGUN PEDIDO EL ESTADO DEL CLIENTE PASA A NO DEUDA(CERO)
            $cliente->update([
                'deuda' => '0'
            ]);
        }

        return redirect()->route('pedidos.index')->with('info', 'eliminado');
    }

    public function destroyid(Request $request)
    {
        if (!$request->hiddenID) {
            $html='';
        } else {
            Pedido::find($request->hiddenID)->update([
                'motivo' => $request->motivo,
                'responsable' => $request->responsable,
                'condicion' => 'ANULADO',
                'modificador' => 'USER'.Auth::user()->id,
                'estado' => '0'
            ]);

            //$detalle_pedidos = DetallePedido::find($request->hiddenID);
            $detalle_pedidos = DetallePedido::where('pedido_id',$request->hiddenID)->first() ;

            $detalle_pedidos->update([
                'estado' => '0'
            ]);

            $html=$detalle_pedidos;
        }
        return response()->json(['html' => $html]);
    }
    public function pedidosgrupotabla(Request $request)
    {
        $pedidos=null;
        if (!$request->direcciongrupo) {
        } else {
            //$idrequest=explode("_",$request->direcciongrupo);

            $direcciongrupo = DireccionGrupo::find($request->direcciongrupo);
            $destino = $direcciongrupo->destino;

            if($destino=='LIMA'){
                $pedidos =  DireccionPedido::where("direcciongrupo",$request->direcciongrupo)
                 ->where("estado",'1');
            }else if($destino=='PROVINCIA'){
                $pedidos =  GastoPedido::where("direcciongrupo",$request->direcciongrupo)
                ->where("estado",'1');
            }

            $pedidos=$pedidos->get();

            return Datatables::of($pedidos)
                    ->addIndexColumn()
                    ->make(true);
        }
    }

    public function EnvioDesvincular(Request $request)
    {
        //return $request->all();
        $pedidos=$request->pedidos;
        $direcciongrupo=$request->direcciongrupo;
        $observaciongrupo=$request->observaciongrupo;
        if(!$request->pedidos)
        {
            return '0';
        }
        else{
            $array_pedidos=explode(",",$pedidos);

            //return var_dump($array_pedidos);

            $direcciongrupolist = DireccionGrupo::find($request->direcciongrupo);
            //return $direcciongrupo;
            $destino = $direcciongrupolist->destino;

            if($destino == "LIMA"){
                foreach($array_pedidos as $pedido_id)
                {
                    $data = DireccionPedido::where("pedido_id",$pedido_id)
                    ->where("direcciongrupo",$direcciongrupo)->first();
                    $data->update([
                        "estado"=>'0'
                    ]);
                    $pedido = Pedido::where("id",$pedido_id)->first();
                    $pedido->update([
                        "condicion_envio"=>pedido::RECEPCION_COURIER,
                        "condicion_envio_code"=>pedido::RECEPCION_COURIER_INT,
                        "observacion_devuelto"=>$observaciongrupo
                    ]);
                }
                $direccionpedido = DireccionPedido::where("direcciongrupo",$direcciongrupo)->where("estado",'1')->get()->count();
                if($direccionpedido==0){
                    $direcciongrupo = DireccionGrupo::where("id",$direcciongrupo)->first();
                    $direcciongrupo->update([
                        "estado"=>'0'
                    ]);
                }
            }else if($destino = "PROVINCIA"){
                foreach($array_pedidos as $pedido_id)
                {
                    $data = GastoPedido::where("pedido_id",$pedido_id)->where("direcciongrupo",$direcciongrupo)->first();
                    $data->update([
                        "estado"=>'0'
                    ]);
                    //return $pedido_id;
                    $pedido = Pedido::where("id",$pedido_id)->first();
                    $pedido->update([
                        "condicion_envio"=>pedido::RECEPCION_COURIER,
                        "condicion_envio_code"=>pedido::RECEPCION_COURIER_INT,
                        "observacion_devuelto"=>$observaciongrupo
                    ]);
                }
                $direccionpedido = GastoPedido::where("direcciongrupo",$direcciongrupo)->where("estado",'1')->get()->count();
                if($direccionpedido==0){
                    $direcciongrupo = DireccionGrupo::where("id",$direcciongrupo)->first();
                    $direcciongrupo->update([
                        "estado"=>'0'
                    ]);
                }
            }






            return response()->json(['html' => $array_pedidos]);


        }


        //$pedido=Pedido::where("id",$request->)




        //return redirect()->route('envios.index')->with('info','actualizado');
    }


}
