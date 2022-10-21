<?php

namespace App\Http\Controllers;

/* use Validator; */
use App\Models\Cliente;
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

        if (Auth::user()->rol == "Asesor"){
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '1')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);
        }else if (Auth::user()->rol == "Super asesor"){
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '1')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
                ->where('clientes.user_id', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);
        }else if (Auth::user()->rol == "Encargado"){
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '1')
                ->where('u.supervisor', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
                ->where('u.supervisor', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
                ->where('u.supervisor', Auth::user()->id)
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);
        }else{
            $clientes1 = Cliente:://CLIENTES CON PEDIDOS CON DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '1')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes2 = Cliente:://CLIENTES CON PEDIDOS SIN DEUDA
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                ->where('clientes.deuda', '0')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio')
                        ]);
            $clientes3 = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','0')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion'
                        ]);
        }

        $superasesor = User::where('rol', 'Super asesor')->count();

        return view('clientes.index', compact('clientes1', 'clientes2', 'clientes3', 'anios', 'dateM', 'dateY', 'superasesor'));
    }
    
    public function indextabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

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
        
        //return $dataTable->render('base_fria.index');
        //if ($request->ajax()) {
            $data = Cliente:://CLIENTES SIN PEDIDOS
                join('users as u', 'clientes.user_id', 'u.id')
                ->join('pedidos as p', 'clientes.id', 'p.cliente_id')
                ->where('clientes.estado','1')
                ->where('clientes.tipo','1')
                ->where('clientes.pidio','1')
                //->where('clientes.deuda', '1')
                ->groupBy(
                    'clientes.id',
                    'clientes.nombre',
                    'clientes.celular', 
                    'clientes.estado', 
                    'u.name',
                    'u.identificador',
                    'clientes.provincia',
                    'clientes.distrito',
                    'clientes.direccion',
                    'clientes.deuda'
                )
                ->get(['clientes.id', 
                        'clientes.nombre', 
                        'clientes.celular', 
                        'clientes.estado', 
                        'u.name as user',
                        'u.identificador',
                        'clientes.provincia',
                        'clientes.distrito',
                        'clientes.direccion',
                        DB::raw('count(p.created_at) as cantidad'),
                        DB::raw('MAX(p.created_at) as fecha'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                        DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                        DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                        DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                        'clientes.deuda',
                        ]);

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                           //$btn = '<a href="" data-target="#modal-convertir" data-toggle="modal" data-opcion="'.$row->id.'"><button class="btn btn-info btn-sm">Convertir a cliente</button></a>';
                          
                           $btn='<a href="'.route('clientes.edit', $row).'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>';
                           $btn = $btn.'<a href="" data-target="#modal-delete" data-toggle="modal" data-opcion="'.$row->id.'"><button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Eliminar</button></a>';
                           
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
    public function edit(Cliente $cliente)
    {
        $users = User::where('users.estado','1')
        ->where('users.rol', 'Asesor')
        ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $cliente->id)->get();

        return view('clientes.edit', compact('cliente', 'users', 'porcentajes'));
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

        $users = User::
            where('estado', '1')    
            ->whereIn('rol', ['Asesor', 'Super asesor'])        
            ->pluck('name', 'id');

        return view('base_fria.index', compact('clientes', 'superasesor', 'users'));
    }

    public function createbf()
    {
        $users = User::where('users.estado','1')
        ->where('users.rol', 'Asesor')
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
}
