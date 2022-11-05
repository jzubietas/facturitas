<?php

namespace App\Http\Controllers;

use App\Models\MovimientoBancario;
use App\Models\Pago;
use App\Models\User;
use App\Models\TipoMovimiento;
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

        $movimientos = $movimientos->get();

        return Datatables::of($movimientos)
                    ->addIndexColumn()
                    ->addColumn('action', function($movimiento){     
                        $btn='';
                        /* if(Auth::user()->rol == "Administrador"){*/
                            //$btn=$btn.'<a href="'.route('movimientos.show', $movimiento['id']).'" class="btn btn-info btn-sm">Ver</a>';
                            //$btn=$btn.'<a href="'.route('movimientos.edit', $movimiento['id']).'" class="btn btn-warning btn-sm">Editar</a>';
                            //$btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$movimiento['id'].'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                        /* }else if(Auth::user()->rol == "Encargado"){
                            $btn=$btn.'<a href="'.route('pagos.show', $pago['id']).'" class="btn btn-info btn-sm">Ver</a>';
                            $btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';
                            $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pago['id'].'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                        }else if(Auth::user()->rol == "Asesor"){
                            $btn=$btn.'<a href="'.route('pagos.show', $pago['id']).'" class="btn btn-info btn-sm">Ver</a>';
                            $btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';
                            $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pago['id'].'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                        }else{
                            $btn=$btn.'<a href="'.route('pagos.show', $pago['id']).'" class="btn btn-info btn-sm">Ver</a>';
                            $btn=$btn.'<a href="'.route('pagos.edit', $pago['id']).'" class="btn btn-warning btn-sm">Editar</a>';
                            $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-delete="'.$pago['id'].'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                        } */
                        
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
            'fecha' => $request->fecha,
            'pedido' => '0',
            'estado' => '1',
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
