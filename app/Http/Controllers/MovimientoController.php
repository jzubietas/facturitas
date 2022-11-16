<?php

namespace App\Http\Controllers;

use App\Models\MovimientoBancario;
use App\Models\Pago;
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
                ->where('condicion', 'OBSERVADO')
                ->count();
        
        $superasesor = User::where('rol', 'Super asesor')->count();

        $bancos = [
            "BCP" => 'BCP',
            "BBVA" => 'BBVA',
            "IBK" => 'INTERBANK',
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
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS'
        ];

        return view('movimientos.index', compact('pagosobservados_cantidad', 'superasesor', 'bancos', 'tipotransferencia', 'titulares'));
    }



    public function indextabla(Request $request)
    {
        $movimientos = null;

        $movimientos = MovimientoBancario::where('estado', '1');//->get();
        $buscar_banco=$request->banco;
        $buscar_tipo=$request->tipo;
        $buscar_titular=$request->titular;
        //return $buscar_banco;
        if($buscar_banco)
        {
            $movimientos = $movimientos->where('banco','like','%'.$buscar_banco.'%');
        }

        if($buscar_tipo)
        {
            $movimientos = $movimientos->where('tipo','like','%'.$buscar_tipo.'%');
        }

        if($buscar_titular)
        {
            $movimientos = $movimientos->where('titular','like','%'.$buscar_titular.'%');
        }

        $movimientos = $movimientos->get([
            'movimiento_bancarios.id',
            'movimiento_bancarios.banco',
            'movimiento_bancarios.titular',
            'movimiento_bancarios.importe',
            'movimiento_bancarios.tipo',
            'movimiento_bancarios.descripcion_otros',
            'movimiento_bancarios.fecha',
            DB::raw("(CASE WHEN movimiento_bancarios.pago =0 THEN 'SIN CONCILIAR' ELSE 'CONCILIACION' END) AS pago"),
            //"case when movimiento_bancarios.pago=0 then 'SIN CONCILIAR' else 'CONCILIACION' END",
            'movimiento_bancarios.estado',
            'movimiento_bancarios.created_at',
        ]);

        return Datatables::of($movimientos)
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
        $query = MovimientoBancario::where('estado', '1')->where("pago",'0');//->get();

        $conciliar=$request->conciliar;
        $excluir=$request->excluir;
        //return $excluir;
        //return $conciliar;//2218

        //reques conciliar 
        $comparar=DetallePago::where('id',$conciliar)->first();
        //return $comparar;
        $banco_compara=$comparar->banco;
        //return $banco_compara; //BBVA

        if($banco_compara=='INTERBANK')
        {
            $banco_compara='IBK';
        }

        if ($banco_compara!='' and !is_null($banco_compara) ) {
            if($banco_compara=='YAPE')
            {
                $banco_compara='BCP';
            }
            $query->where('banco','LIKE','%'.$banco_compara.'%');
        }

        
        //monto_compara=$comparar->monto;
        //return  $monto_compara;

        /*if ($monto_compara!='' and is_null($monto_compara) ) {
            $query->where('importee',$monto_compara.'%');
        }*/

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
        //return $fecha_compra;

        /*if ($fecha_compra!='' || is_null($fecha_compra) ) {
            $query->whereDate('fecha','>',''.$fecha_compra.'');
        }*/
        //return $fecha_compra;
        //return $request->excluir;

        if ($excluir!='' || is_null($excluir) ) {

            $array_excluir=explode(",",$excluir);
            //return $array_excluir;
            $query->whereNotIn('id',$array_excluir); 
            //whereNotIn('book_price', [100,200]
               }
        
        $movimientos = $query->orderBy('fecha', 'ASC')->get();

        return Datatables::of($movimientos)
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
        $monto = $request->monto;
        //$descrip_otros = $request->descrip_otros;
        $monto=str_replace(',','',$monto);
        $monto=str_replace('.00','',$monto);
        //$titular=explode('%20',$request->titulares);
        $titular = $request->titulares;
        $titular=str_replace('%20',' ',$titular);

        $movimiento_repeat=MovimientoBancario::where('banco',$request->banco)
                            ->where('titular',$titular)
                            ->where('importe',$monto)
                            ->where('tipo',$request->tipo)
                            ->where('fecha',$request->fecha)
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movimiento = MovimientoBancario::where('id', $id)->first();
        //
        return view('movimientos.show', compact('movimiento'));
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
