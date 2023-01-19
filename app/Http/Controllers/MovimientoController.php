<?php

namespace App\Http\Controllers;

use App\Models\MovimientoBancario;
use App\Models\Pago;
use App\Models\PagoPedido;
use App\Models\DetallePago;
use App\Models\User;
use App\Models\TipoMovimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagosobservados_cantidad = Pago::where('user_id', Auth::user()->id)//PAGOS OBSERVADOS
                ->where('estado', '1')
                ->where('condicion', Pago::OBSERVADO)
                ->count();

        $superasesor = User::where('rol', 'Super asesor')->count();

        $bancos = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "INTERBANK" => 'INTERBANK',
            /*"SCOTIABANK" => 'SCOTIABANK',
            "PICHINCHA" => 'PICHINCHA',*/
        ];

        $tipotransferencia = [
            "INTERBANCARIO" => 'INTERBANCARIO',
            "DEPOSITO" => 'DEPOSITO',
            "GIRO" => 'GIRO',
            "TRANSFERENCIA" => 'TRANSFERENCIA',
            "YAPE" => 'YAPE',
            "PLIN" => 'PLIN',
            "TUNKI" => 'TUNKI',
        ];

        $titulares = [
            "EPIFANIO SOLANO HUAMAN" => 'EPIFANIO SOLANO HUAMAN',
            "ALFREDO ALEJANDRO GABRIEL MONTALVO" => 'ALFREDO ALEJANDRO GABRIEL MONTALVO',
            "SABINA LACHOS" => 'SABINA LACHOS',
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS',
        ];

        $movimientosSinConciliar= MovimientoBancario::activo()->sinConciliar()->count();
        return view('movimientos.index', compact('pagosobservados_cantidad', 'superasesor', 'bancos', 'tipotransferencia', 'titulares','movimientosSinConciliar'));
    }



    public function indextabla(Request $request)
    {
        $movimientos = MovimientoBancario::where('estado', '1');//->get();
        $buscar_banco=$request->banco;
        $buscar_tipo=$request->tipo;
        $buscar_titular=$request->titular;
        if($buscar_banco)
        {
            $movimientos = $movimientos->where('banco','like','%'.$buscar_banco.'%');
        }

        if($buscar_titular)
        {
            $movimientos = $movimientos->where('titular','like','%'.$buscar_titular.'%');
        }

        $movimientos = $movimientos->select(
            [
                'movimiento_bancarios.id',
                //'movimiento_bancarios.id as id2',
                DB::raw(" (CASE WHEN movimiento_bancarios.id<10 THEN concat('MOV000',movimiento_bancarios.id)
                            WHEN movimiento_bancarios.id<100  THEN concat('MOV00',movimiento_bancarios.id)
                            WHEN movimiento_bancarios.id<1000  THEN concat('MOV0',movimiento_bancarios.id)
                            ELSE concat('MOV',movimiento_bancarios.id) END) AS id2"),
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                'movimiento_bancarios.tipo',
                'movimiento_bancarios.descripcion_otros',
                DB::raw('(DATE_FORMAT(movimiento_bancarios.fecha, "%Y-%m-%d")) as fecha'),
                //DB::raw('(DATE_FORMAT(movimiento_bancarios.fecha, "%d/%m/%Y")) as fecha2'),
                DB::raw("(CASE WHEN movimiento_bancarios.pago =0 THEN 'SIN CONCILIAR' ELSE 'CONCILIADO' END) AS pago"),
                'movimiento_bancarios.estado',
                'movimiento_bancarios.created_at',
            ]
        )
        ->orderBy('updated_at','desc');//actualizacion de orden para movimientos

        return Datatables::of(DB::table($movimientos))
                    ->addIndexColumn()
                    ->addColumn('action', function($movimiento){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function indextablaconciliar(Request $request)
    {
        //return $request->all();
        $query = null;
        $query = MovimientoBancario::where('estado', '1')->where("pago",'0')
            ->select(
                'movimiento_bancarios.id',
                'movimiento_bancarios.banco',
                'movimiento_bancarios.titular',
                'movimiento_bancarios.importe',
                DB::raw('DATE_FORMAT(movimiento_bancarios.fecha, "%Y-%m-%d") as fecha'),
                DB::raw('DATE_FORMAT(movimiento_bancarios.fecha, "%d/%m/%Y") as fechamodal'),
                //DB::raw('DATE_FORMAT(fecha, "%d/%m/%Y") as fecha'),
                //'movimiento_bancarios.fecha',
                'movimiento_bancarios.tipo',

            );//->get();

        $conciliar=$request->conciliar;
        $excluir=$request->excluir;
        $fechadeposito=$request->fechadeposito;
        //return $excluir;
        //return $conciliar;//2218

        //reques conciliar
        $comparar=DetallePago::where('id',$conciliar)->first();
        //return $comparar;
        $banco_compara=$comparar->banco;
        //return $banco_compara; //BBVA

        if($banco_compara=='INTERBANK')
        {
            //$banco_compara='IBK';
        }

        if ($banco_compara!='' and !is_null($banco_compara) ) {
            if($banco_compara=='YAPE')
            {
                $banco_compara='BCP';
            }
            $query->where('banco','LIKE','%'.$banco_compara.'%');
        }


        $monto_compara=$comparar->monto;
        //return $monto_compara;
        //return  $monto_compara;

        /*if ($monto_compara!='' and is_null($monto_compara) ) {
            $query->where('importee',$monto_compara.'%');
        }*/

        //$monto_compara=

        if($monto_compara)
        {

            //$fechadeposito = Carbon::createFromFormat('d/m/Y', $request->fechadeposito)->format('Y-m-d');
            //$query->where('movimiento_bancarios.importe',$monto.'%');
            $monto_ma_3= floatval($monto_compara)+3;
            $monto_me_3= floatval($monto_compara)-3;

            $query->where('movimiento_bancarios.importe','>=',$monto_me_3);
            $query->where('movimiento_bancarios.importe','<=',$monto_ma_3);
            //$query->whereBetween('movimiento_bancarios.importe',[$monto_me_3,$monto_ma_3]);


        }


        $titular_compara=$comparar->titular;
        //return $titular_compara;

        if ($titular_compara!='' || is_null($titular_compara) ) {

            if($titular_compara=='EPIFANIO HUAMAN SOLANO' || $titular_compara=='EPIFANIO SOLANO HUAMAN')
            {
                $query->where('titular','LIKE','%'.'EPIFANIO'.'%');
            }else{
                $query->where('titular','LIKE','%'.$titular_compara.'%');
            }


        }

        $fecha_compra=$comparar->fecha;
        //$min=$request->de;

        //return 'a';
        if(!$request->fechadeposito){
            $query=$query;
        }
        else{
            $fechadeposito = Carbon::createFromFormat('d/m/Y', $request->fechadeposito)->format('Y-m-d');
            $query->where(DB::raw('DATE(movimiento_bancarios.fecha)'),'>=',''.$fechadeposito.'');
        }

        //$fechadeposito=$request->fechadeposito;
        //$fechadeposito=$fechadeposito;//04/10/2022

        //$fechadeposito = Carbon::createFromFormat('d/m/Y', $fechadeposito)->format('Y-m-d');
        //return $fechadeposito;


        //return $fechadeposito;

        //return $fecha_compra;

        /*if ($fechadeposito!='' || !is_null($fechadeposito) ) {

            $query->where(DB::raw('DATE(movimiento_bancarios.fecha)'),'>=',''.$fechadeposito.'');
        }*/
        //return $fecha_compra;
        //return $request->excluir;

        if ($excluir!='' || is_null($excluir) ) {

            $array_excluir=explode(",",$excluir);
            //return $array_excluir;
            $query->whereNotIn('id',$array_excluir);
            //whereNotIn('book_price', [100,200]
               }

        $movimientos = $query->orderBy('fecha', 'ASC');//->get();

        return Datatables::of(DB::table($movimientos))
                    ->addIndexColumn()
                    ->addColumn('action', function($movimiento){
                        $btn='';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }

    public function tipomovimiento(Request $request)//tipo movimiento
    {
        if (!$request->banco) {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
        } else {
            $html = '<option value="">' . trans('---- SELECCIONE ----') . '</option>';
            $tiposmovimientos = TipoMovimiento::where('tipo_movimientos.banco', $request->banco)->get();
            foreach ($tiposmovimientos as $tiposmovimiento) {
                $html .= '<option value="' . $tiposmovimiento->descripcion . '">' . $tiposmovimiento->descripcion . '</option>';
            }
        }
        return response()->json(['html' => $html]);
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
        $monto = $request->monto;
        $descrip_otros = $request->descrip_otros;
        $monto=str_replace(',','',$monto);

        $movimientos = MovimientoBancario::create([
            'banco' => $request->banco,
            'titular' => $request->titulares,
            'importe' => $monto,
            'tipo' => $request->tipotransferencia,
            'fecha' => Carbon::parse($request->fecha),
            'pedido' => '0',
            'estado' => '1',
            'pago' => '0',
            'detpago' => '0',
            'cabpago' => '0',
            'descripcion_otros' =>$descrip_otros
        ]);

        //return redirect()->route('movimientos.index')->with('info', 'registrado');
        return redirect()->route('movimientos.index')->with('info', 'registrado');
    }

    public function repeat(Request $request)
    {
        // $request->all();
        //return $request->titulares;
        $monto = $request->monto;
        //$descrip_otros = $request->descrip_otros;
        $monto=str_replace(',','',$monto);
        $monto=str_replace('.00','',$monto);
        //$titular=explode('%20',$request->titulares);
        $titular = $request->titulares;
        $titular=str_replace('%20',' ',$titular);

        //return "banco ".$request->banco." titular ".$titular." importe ".$monto." tipo ".$request->tipo." fecha ".$request->fecha;

        $movimiento_repeat=MovimientoBancario::where('banco',$request->banco)
                            ->where('titular',$titular)
                            ->where('importe',$monto)
                            ->where('tipo',$request->tipo)
                            ->where(DB::raw('CAST(fecha as date)'), '=', $request->fecha)
                            //->where('fecha',$request->fecha)
                            ->where('estado',"1")->count();
        if($movimiento_repeat == 0)
        {
            $html="sigue|0";

        }else{
            $movimiento_repeat_select=MovimientoBancario::where('banco',$request->banco)
                            ->where('titular',$titular)
                            ->where('importe',$monto)
                            ->where('tipo',$request->tipo)
                            ->where('fecha',$request->fecha)
                            ->where('estado',"1")->first();
            $repetido=$movimiento_repeat_select->id;
            $html="bloqueo|".$repetido;


        }
        //$html=$titular;
        //$html=var_dump($request);
        return response()->json(['html' => $html]);
    }

    public function register(Request $request)
    {
        $monto = $request->monto;
        $monto=str_replace(',','',$monto);
        $monto=str_replace('.00','',$monto);
        $titular = $request->titulares;
        $titular=str_replace('%20',' ',$titular);
        $descrip_otros=$request->descrip_otros;

        $movimientos = MovimientoBancario::create([
            'banco' => $request->banco,
            'titular' => $titular,
            'importe' => $monto,
            'tipo' => $request->tipo,
            'fecha' => Carbon::parse($request->fecha),
            'pedido' => '0',
            'estado' => '1',
            'pago' => '0',
            'detpago' => '0',
            'cabpago' => '0',
            'descripcion_otros' =>$descrip_otros
        ]);


        $html="ok|0";

        return response()->json(['html' => $html]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movimiento = MovimientoBancario::where('id', $id)->first();

        $pago=Pago::join('users as u', 'pagos.user_id', 'u.id')
                    ->where("pagos.id",$movimiento->cabpago)
            ->select(
                'pagos.id as id',
                'u.identificador as users',
                'pagos.condicion as condicion',
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),
                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
            )
            ->first();

        $pagoPedidos = PagoPedido::join('pedidos as p', 'pago_pedidos.pedido_id', 'p.id')
            ->join('detalle_pedidos as dp', 'p.id', 'dp.pedido_id')
            ->select('pago_pedidos.id',
                    'dp.codigo',
                    'p.id as pedidos',
                    'p.condicion',
                    'dp.total',
                    'pago_pedidos.pagado',
                    'pago_pedidos.abono'
                    )
            ->where('pago_pedidos.estado', '1')
            ->where('p.estado', '1')
            ->where('dp.estado', '1')
            ->where('pago_pedidos.pago_id', $movimiento->cabpago)
            ->get();


        $detallepago=DetallePago::where("id",$movimiento->detpago)->first();
        //
        return view('movimientos.show', compact('movimiento','pago','detallepago','pagoPedidos'));
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
        $movimiento = MovimientoBancario::where('id', $id)->first();
        $pago=Pago::join('users as u', 'pagos.user_id', 'u.id')
                    ->where("pagos.id",$movimiento->cabpago)
            ->select(
                'pagos.id as id',
                'u.identificador as users',
                DB::raw(" (CASE WHEN (select count(dpago.id) from detalle_pagos dpago where dpago.pago_id=pagos.id and dpago.estado in (1) )>1 then 'V' else 'I' end) as cantidad_voucher "),
                DB::raw(" (CASE WHEN (select count(ppedidos.id) from pago_pedidos ppedidos where ppedidos.pago_id=pagos.id and ppedidos.estado in (1)  )>1 then 'V' else 'I' end) as cantidad_pedido "),
            )
            ->first();
        $detallepago=DetallePago::where("id",$movimiento->detpago)->first();

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

        $monto = $request->monto;
        $descrip_otros = $request->descrip_otros;
        $monto=str_replace(',','',$monto);

        $movimientos = MovimientoBancario::create([
            'banco' => $request->banco,
            'titular' => $request->titulares,
            'importe' => $monto,
            'tipo' => $request->tipotransferencia,
            'fecha' => Carbon::parse($request->fecha),
            'pedido' => '0',
            'estado' => '1',
            'pago' => '0',
            'detpago' => '0',
            'cabpago' => '0',
            'descripcion_otros' =>$descrip_otros
        ]);


    }

    public function actualiza(Request $request)
    {
        //

        $monto = $request->monto;
        $descrip_otros = $request->descrip_otros;
        $monto=str_replace(',','',$monto);

        $movimientos = MovimientoBancario::create([
            'banco' => $request->banco,
            'titular' => $request->titulares,
            'importe' => $monto,
            'tipo' => $request->tipotransferencia,
            'fecha' => Carbon::parse($request->fecha),
            'pedido' => '0',
            'estado' => '1',
            'pago' => '0',
            'detpago' => '0',
            'cabpago' => '0',
            'descripcion_otros' =>$descrip_otros
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyid(Request $request)
    {
        //modificar primero
        if (!$request->hiddenIDdelete) {
            $html=$request->hiddenIDdelete."--";
        } else {

            $html=$request->hiddenIDdelete." id";
            $movimiento_id=$request->hiddenIDdelete;

            //Cliente::where('clientes.id',$request->hiddenID)
            $movimiento = MovimientoBancario::where('movimiento_bancarios.id', $movimiento_id);//->first();

            try {
                DB::beginTransaction();

                $movimiento->update([
                    'estado' => 0
                ]);

                DB::commit();

                $html="eliminado ".$request->hiddenIDdelete;
            }
            catch (\Throwable $th) {
                throw $th;
                $html="error";

            }


        }
        return response()->json(['html' => $html]);
    }
}
