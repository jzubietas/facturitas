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
            "EPIFANIO HUAMAN SOLANO" => 'EPIFANIO HUAMAN SOLANO',
            "NIKSER DENIS ORE RIVEROS" => 'NIKSER DENIS ORE RIVEROS'
        ];

        return view('movimientos.index', compact('pagosobservados_cantidad', 'superasesor', 'bancos', 'tipotransferencia', 'titulares'));
    }



    public function indextabla(Request $request)
    {
        $movimientos = null;

        $movimientos = MovimientoBancario::where('estado', '1')->get();
        
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
        $monto=str_replace(',','',$monto);

        //return $request->all();

        $movimientos = MovimientoBancario::create([
            'banco' => $request->banco,
            'titular' => $request->titulares,
            'importe' => $request->monto,
            'tipo' => $request->tipotransferencia,
            'fecha' => $request->fecha,
            'pedido' => '0',
            'estado' => '1'
        ]);

        return redirect()->route('movimientos.index')->with('info', 'registrado');
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
            $html='';
        } else {
            //$pago_id=;
            $html='';
            $movimiento_id=$request->hiddenIDdelete;
            /*$pago = Pago::where('id', $request->hiddenID)
                        ->where('estado', '1')
                        ->first();//solo 1*/
            
            $movimiento = MovimientoBancario::where('id', $movimiento_id)->first();

            try {
                DB::beginTransaction();

                $movimiento->update([            
                    'estado' => '0'
                ]);
            }
            catch (\Throwable $th) {
                throw $th;
                /*DB::rollback();
                dd($th);*/
            }
            
            
        }
        return response()->json(['html' => $html]);
    }
}
