<?php

namespace App\Http\Controllers;

/* use Validator; */
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Porcentaje;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol=Auth::user()->rol;

        $anios = [
            "2020" => '2020 - 2021',
            "2021" => '2021 - 2022',
            "2022" => '2022 - 2023',
            "2023" => '2023 - 2024',
            "2024" => '2024 - 2025',
            "2025" => '2025 - 2026',
            "2026" => '2026 - 2027',
            "2027" => '2027 - 2028',
            "2028" => '2028 - 2029',
            "2029" => '2029 - 2030',
            "2030" => '2030 - 2031',
            "2031" => '2031 - 2032',
        ];

        
        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('clientes.index', compact( 'anios', 'dateM', 'dateY', 'superasesor','mirol'));
    }
    
    public function indextabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data=null;

        $anios = [
            "2020" => '2020 - 2021',
            "2021" => '2021 - 2022',
            "2022" => '2022 - 2023',
            "2023" => '2023 - 2024',
            "2024" => '2024 - 2025',
            "2025" => '2025 - 2026',
            "2026" => '2026 - 2027',
            "2027" => '2027 - 2028',
            "2028" => '2028 - 2029',
            "2029" => '2029 - 2030',
            "2030" => '2030 - 2031',
            "2031" => '2031 - 2032',
        ];
        if (Auth::user()->rol == "Llamadas"){
            $data = Cliente::
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('u.llamada', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00') as pedidos_mes_deuda "),
                        DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00') as pedidos_mes_deuda_antes "),
                        'clientes.deuda',
                        ]);
        }
        else if (Auth::user()->rol == "Jefe de llamadas"){
            $data = Cliente::
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('u.llamada', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00') as pedidos_mes_deuda "),
                        DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00') as pedidos_mes_deuda_antes "),
                        'clientes.deuda',
                        ]);
        }
        elseif (Auth::user()->rol == "Asesor"){
            $data = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.user_id', Auth::user()->id)
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        'clientes.deuda',
                        ]);

        }else if (Auth::user()->rol == "Super asesor"){
            $data = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        'clientes.deuda',
                        ]);
        }else if (Auth::user()->rol == "Encargado"){
            $data = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                //->where('clientes.user_id', Auth::user()->id)//corregir aqui los usuarios que sean asesores
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        'clientes.deuda',
                        ]);
        }else{
            

            $data = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
                //->whereIn('p.pago', ['0', '1'])
                //->whereIn('p.pagado', ['0', '1'])
                //->where('p.created_at','<',$date_menos)
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                
                //->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.icelular', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda',
                    'clientes.pidio'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.icelular', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        'clientes.pidio',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00' and ped.estado=1) as pedidos_mes_deuda "),
                        DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                        'clientes.deuda',
                        ]);
                       // return $data;
        }
        
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){     
                        $btn="";                          
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        //}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function pedidostiempo(Request $request)
    {
        if (!$request->cliente_id_tiempo) {
            $html="";

        }else{
            $cliente_id_tiempo=$request->cliente_id_tiempo;
            $pcantidad_pedido=$request->pcantidad_pedido;
            $pcantidad_tiempo=$request->pcantidad_tiempo;

            $html=$cliente_id_tiempo."|".$pcantidad_pedido."|".$pcantidad_tiempo;
            $user=Cliente::where("celular",$request->cliente_id_tiempo);
            //$jefe = User::find($request->asesor, ['jefe']);
            $user->update([
                //'deuda' => "0",
                'crea_temporal' => "1",
                'activado_tiempo' => $pcantidad_tiempo,
                'activado_pedido' => $pcantidad_pedido,
                'temporal_update' => Carbon::now()->addMinutes($pcantidad_tiempo)
            ]);
            //intval($pcantidad_tiempo)
            //Carbon::now()->addMinutes(5);
            //$user->timestamp('temporal_update')->useCurrent();

        }
        
        return response()->json(['html' => $html]);
        //return redirect()->route('users.asesores')->with('info', 'asignado');
    }

    public function create()
    {
        $users = User::where('users.estado','1')
        ->whereIn('users.rol', ['Asesor', 'Super asesor'])
        ->pluck('identificador', 'id');

        return view('clientes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'celular' => 'required|unique:clientes',            
        ]);

        try {
            DB::beginTransaction();

            $cliente = Cliente::create([            
            'nombre' => $request->nombre,
            'celular' => $request->celular,
            'user_id' => $request->user_id,
            'tipo' => $request->tipo,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'dni' => $request->dni,
            'deuda' => '0',
            'pidio' => '0',
            'estado' => '1'
            ]);

            // ALMACENANDO PORCENTAJES
            $nombreporcentaje = $request->nombreporcentaje;
            $valoresporcentaje = $request->porcentaje;
            $cont = 0;

            /* return $porcentaje; */
            while ($cont < count((array)$nombreporcentaje)) {

            $porcentaje = Porcentaje::create([
                    'cliente_id' => $cliente->id,
                    'nombre' => $nombreporcentaje[$cont],
                    'porcentaje' => $valoresporcentaje[$cont],
                ]);
                $cont++;
            }
            
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            /* DB::rollback();
            dd($th); */
        }

        return redirect()->route('clientes.index')->with('info','registrado');        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //return $cliente;
        $users = User::where('users.estado','1')
        ->where('users.rol', 'Asesor')
        ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $cliente->id)->get();

        return view('clientes.show', compact('cliente', 'users', 'porcentajes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $mirol=Auth::user()->rol;
        $users = User::where('users.estado','1')
        ->where('users.rol', 'Asesor')
        ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $cliente->id)->get();
        
        return view('clientes.edit', compact('cliente', 'users', 'porcentajes','mirol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'celular' => 'required',            
        ]);

        $cliente->update($request->all());

        $idporcentaje = $request->idporcentaje;
        $valoresporcentaje = $request->porcentaje;
        $cont = 0;
        /* return $request->all(); */        
        $valor = Porcentaje::find($idporcentaje); /* return $valor; */
        while ($cont < count((array)$idporcentaje)) {
            $valor[$cont]->update([
                    'porcentaje' => $valoresporcentaje[$cont]
            ]);
            $cont++;
        }

        if($request->tipo === '1'){
            return redirect()->route('clientes.index')->with('info','actualizado');
        }
        else{
            return redirect()->route('basefria')->with('info','actualizado');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->update([
            'estado' => '0'
        ]);
        
        return redirect()->route('clientes.index')->with('info','eliminado');
    }


    public function indexbf()
    {
        if (Auth::user()->rol == "Asesor"){
            $clientes = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id', 
                    'clientes.nombre', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name as user',
                    'u.identificador')
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0')
            ->where('clientes.user_id', Auth::user()->id)
            ->get();            

        }else if (Auth::user()->rol == "Super asesor"){
            $clientes = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id', 
                    'clientes.nombre', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name as user',
                    'u.identificador')
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0')
            ->where('clientes.user_id', Auth::user()->id)
            ->get();
        }else{
            $clientes = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id', 
                    'clientes.nombre', 
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name as user',
                    'u.identificador')
            ->where('clientes.estado','1')
            ->where('clientes.tipo','0')
            ->get();
        }

        $superasesor = User::where('rol', 'Super asesor')->count();

        if (Auth::user()->rol == "Llamadas" || Auth::user()->rol == "Llamadas")
        {
            $users = User::
                where('estado', '1')    
                ->whereIn('rol', ['Asesor', 'Super asesor']) 
                ->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }else{
            $users = User::
                where('estado', '1')    
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                //->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }

        return view('base_fria.index', compact('clientes', 'superasesor', 'users'));
    }

    public function createbf()
    {
        $users=User::select(
                    DB::raw("CONCAT(identificador,' (ex ',IFNULL(exidentificador,''),')') AS identificador"),'id'
                    )
                    ->where('users.rol', 'Asesor')
                    ->where('users.estado','1')
            ->pluck('identificador', 'id');



        return view('base_fria.create', compact('users'));
    }

    public function storebf(Request $request)
    {   
        /* $request->validate([
                'celular' => 'required|unique:clientes',*/

        $cliente = Cliente::where('celular', $request->celular)->first();        
        if($cliente !== null){
            $user = User::where('id', $cliente->user_id)->first();
            
            $messages = [
                'unique' => 'EL CELULAR INGRESADO SE ENCUENTA ASIGNADO AL ASESOR '.$user->identificador,
            ];
    
            $validator = Validator::make($request->all(), [
                'celular' => 'required|unique:clientes',
            ], $messages);
     
            if ($validator->fails()) {
                return redirect('clientes.createbf')
                            ->withErrors($validator)
                            ->withInput();
            } 
        }

            $cliente = Cliente::create([            
            'nombre' => $request->nombre,
            'celular' => $request->celular,
            'user_id' => $request->user_id,
            'tipo' => $request->tipo,
            'deuda' => '0',
            'pidio' => '0',
            'estado' => '1'
            ]);

        return redirect()->route('basefria')->with('info','registrado');        
    }

    public function editbf(Cliente $cliente)
    {
        $users = User::where('users.estado','1')
        ->where('users.rol', 'Asesor')
        ->pluck('name', 'id');

        return view('base_fria.edit', compact('cliente', 'users'));
    }

    public function updatebfpost(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'dni' => 'required',
            'celular' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'referencia' => 'required',
            'porcentaje' => 'required',
        ]);
        //$id=null;
        //Selection::whereId($id)->update($request->all());
        $cliente = Cliente::where('clientes.id',$request->hiddenID)->update([
            'nombre' => $request->nombre,
            'dni' => $request->dni,
            'celular' => $request->celular,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'deuda' => '0',
            'pidio' => '0',
            'tipo' => '1',
            'saldo' => '0'

        ]);

        try {
            DB::beginTransaction();
            
        // ALMACENANDO PAGO-PEDIDOS
        $nombreporcentaje = $request->nombreporcentaje;
        $valoresporcentaje = $request->porcentaje;
        $cont = 0;

        /* return $porcentaje; */
        while ($cont < count((array)$nombreporcentaje)) {

            Porcentaje::create([
                    'cliente_id' => $request->hiddenID,//$cliente->id,//
                    'nombre' => $nombreporcentaje[$cont],
                    'porcentaje' => $valoresporcentaje[$cont],
                ]);
                $cont++;
            }
        DB::commit();
        } catch (\Throwable $th) {
            throw $th;            
        }

        //return redirect()->route('clientes.index')->with('info','registrado');
    }

    public function updatebf(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required',
            'dni' => 'required',
            'celular' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'referencia' => 'required',
            'porcentaje' => 'required',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'dni' => $request->dni,
            'celular' => $request->celular,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'referencia' => $request->referencia,
            'deuda' => '0',
            'pidio' => '0',
            'tipo' => '1'
        ]);
        try {
            DB::beginTransaction();
            
        // ALMACENANDO PAGO-PEDIDOS
        $nombreporcentaje = $request->nombreporcentaje;
        $valoresporcentaje = $request->porcentaje;
        $cont = 0;

        /* return $porcentaje; */
        while ($cont < count((array)$nombreporcentaje)) {

            Porcentaje::create([
                    'cliente_id' => $cliente->id,
                    'nombre' => $nombreporcentaje[$cont],
                    'porcentaje' => $valoresporcentaje[$cont],
                ]);
                $cont++;
            }
        DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            /* DB::rollback();
            dd($th); */
        }

        return redirect()->route('clientes.index')->with('info','registrado');
    }


    public function clientedeasesor(Request $request)
    {
        //ahora con el identificador de  Usuarios
        $mirol=Auth::user()->rol;
        $clientes = null;
        $clientes = Cliente::join('users as u', 'clientes.user_id', 'u.id')->where('clientes.estado', '1')->where("clientes.tipo","1");
        $html="";

        //valida deuda excepto para administrador o por tener tiempo temporal

        if (!$request->user_id  || $request->user_id=='')
        {
            $clientes = $clientes;
        }else{
            $clientes = $clientes->where('u.identificador', $request->user_id );
        }
        $clientes=$clientes->orderBy('id', 'ASC')
            ->get([
                'clientes.id',
                'clientes.deuda',
                'clientes.crea_temporal',
                'clientes.activado_tiempo',
                'clientes.activado_pedido',
                'clientes.temporal_update',
                'clientes.icelular',
                'clientes.celular',
                'clientes.nombre',
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00' and ped2.estado=1) as pedidos_mes_deuda_antes "),
            ]);
        
        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';

        foreach ($clientes as $cliente) 
        {
            //Auth::user()->rol=='Administrador'
            if($mirol=='Administrador')
            {
                $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.  ( ($cliente->icelular!=null)? '-'.$cliente->icelular :''  ) .'  -  ' . $cliente->nombre . '</option>';
            }else{
                if($cliente->crea_temporal==1)
                {
                    //falta considerar el tiempo ahora menos el tiempo activado temporal
                    $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';
                }else{
                    //considerar deuda real
                    if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes==0)
                    {
                        $html .= '<option style="color:lightblue" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';    
                    }else if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if($cliente->pedidos_mes_deuda==0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';
                    }
                }
            }
        }
        
        return response()->json(['html' => $html]);
    }

    public function clientedeasesorpagos(Request $request)
    {
        //ahora con el identificador de  Usuarios
        $mirol=Auth::user()->rol;
        $clientes = null;
        $clientes = Cliente::join('users as u', 'clientes.user_id', 'u.id')->where('clientes.estado', '1')->where("clientes.tipo","1");
        $html="";

        //valida deuda excepto para administrador o por tener tiempo temporal

        if (!$request->user_id  || $request->user_id=='')
        {
            $clientes = $clientes;
        }else{
            $clientes = $clientes->where('u.identificador', $request->user_id );
        }
        $clientes=$clientes->orderBy('id', 'ASC')
            ->get([
                'clientes.id',
                'clientes.deuda',
                'clientes.crea_temporal',
                'clientes.activado_tiempo',
                'clientes.activado_pedido',
                'clientes.temporal_update',
                'clientes.icelular',
                'clientes.celular',
                'clientes.nombre',
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='2022-11-01 00:00:00' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='2022-10-31 00:00:00' and ped2.estado=1) as pedidos_mes_deuda_antes "),
            ]);
        
        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';

        foreach ($clientes as $cliente) 
        {
            //Auth::user()->rol=='Administrador'
            if($mirol=='Administrador')
            {
                $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.  ( ($cliente->icelular!=null)? '-'.$cliente->icelular :''  ) .'  -  ' . $cliente->nombre . '</option>';
            }else{
                /*if($cliente->crea_temporal==1)
                {
                    $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';
                }else*/
                {
                    //considerar deuda real
                    if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes==0)
                    {
                        $html .= '<option style="color:lightblue" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';    
                    }else if($cliente->pedidos_mes_deuda>0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if($cliente->pedidos_mes_deuda==0 && $cliente->pedidos_mes_deuda_antes>0)
                    {
                        $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';
                    }
                }
            }
        }
        
        return response()->json(['html' => $html]);
    }


    public function clientedeasesorparapagos(Request $request)
    {
        if (!$request->user_id  || $request->user_id=='') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        }else{

            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                                ->where('clientes.tipo', '1')
                                ->get();        
            foreach ($clientes as $cliente) {
                if($cliente->deuda=="0")
                {
                    $html .= '<option disabled style="color:#000" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                }else{
                    if( Auth::user()->rol=='Asesor' )
                    {
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Llamadas' ){
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else if( Auth::user()->rol=='Jefe de lamadas' ){
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    }else{
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular. '  -  ' . $cliente->nombre . '</option>';
                    }
                }
                
            }
        }
        
        return response()->json(['html' => $html]);
    }

    public function pedidosenvioclientetabla(Request $request)
    {        
        $pedidos=null;
        if (!$request->cliente_id) {            
        } else {
            
            $idrequest=$request->cliente_id;       
            $pedidos = Pedido::join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
                ->select('pedidos.id', 
                        'dp.codigo',
                        'dp.nombre_empresa',
                        //DB::raw(" (select dd.nombre_empresa from detalle_pedidos de where de.pedido_id=direcion_grupos.id) as clientes "),                     
                        )
                ->where('pedidos.cliente_id', $idrequest)
                ->where('pedidos.estado', '1')
                ->where('dp.estado', '1')
                //->where('pedidos.envio', '1')
                ->where('pedidos.condicion_envio', 'PENDIENTE DE ENVIO')
                ->get();

                
                

            
            return Datatables::of($pedidos)
                    ->addIndexColumn()                  
                    ->make(true);
        }       
    }
}
