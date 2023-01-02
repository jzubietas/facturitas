<?php

namespace App\Http\Controllers;

/* use Validator; */

use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\PagoPedido;
use App\Models\Pedido;
use App\Models\Porcentaje;
use App\Models\User;
use App\Models\ListadoResultado;
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
        $mirol = Auth::user()->rol;

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

        return view('clientes.index', compact('anios', 'dateM', 'dateY', 'superasesor', 'mirol'));
    }

    public function indextabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente:://CLIENTES SIN PEDIDOS
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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

                DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as ultimo_pedido "),

                DB::raw('count(p.created_at) as cantidad'),
                DB::raw('MAX(p.created_at) as fecha'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%d")) as dia'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%m")) as mes'),
                DB::raw('MAX(DATE_FORMAT(p.created_at, "%Y")) as anio'),
                DB::raw('MONTH(CURRENT_DATE()) as dateM'),
                DB::raw('YEAR(CURRENT_DATE()) as dateY'),
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and cast(ped.created_at as date) >='" . now()->startOfMonth()->format('Y-m-d') . "' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and cast(ped2.created_at as date) <='" . now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d') . "'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                'clientes.deuda',
                //DB::raw(" (select lr.s_2022_11 from clientes c inner join listado_resultados lr on c.id=lr.id limit 1) as situacion")
                'clientes.situacion'
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $data = $data->WhereIn("u.identificador", $usersasesores);


        } else if (Auth::user()->rol == "Jefe de llamadas") {
            /*$usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);*/
        } elseif (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        }
        //$data=$data->get();

        return datatables()->query(DB::table($data))//Datatables::of($data)
        ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
        //}
    }

    public function clientestablasituacion(Request $request)
    {
        $idconsulta = $request->cliente;
        $idconsulta;
        $data = ListadoResultado::where('id', $idconsulta)
            ->select('id',
                'a_2021_11',
                'a_2021_12',
                'a_2022_01',
                'a_2022_02',
                'a_2022_03',
                'a_2022_04',
                'a_2022_05',
                'a_2022_06',
                'a_2022_07',
                'a_2022_08',
                'a_2022_09',
                'a_2022_10',
                'a_2022_11',
                'a_2022_12',
                's_2021_11',
                's_2021_12',
                's_2022_01',
                's_2022_02',
                's_2022_03',
                's_2022_04',
                's_2022_05',
                's_2022_06',
                's_2022_07',
                's_2022_08',
                's_2022_09',
                's_2022_10',
                's_2022_11',
                's_2022_12',
            );

        return datatables()->query(DB::table($data))
            ->toJson();

    }

    public function create()
    {
        /*$users = User::where('users.estado','1')
        ->whereIn('users.rol', ['Asesor', 'Super asesor'])
        ->pluck('identificador', 'id');*/

        $users = User::select(
            DB::raw("CONCAT(identificador,' (ex ',IFNULL(exidentificador,''),')') AS identificador"), 'id'
        )
            ->where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->pluck('identificador', 'id');

        return view('clientes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
                /*'icelular'=> $request->icelular,*/
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

            $user = User::where('id', $request->user_id)->first();

            if ($user->exidentificador == '01' ||
                $user->exidentificador == '03' ||
                $user->exidentificador == '05' ||
                $user->exidentificador == '07' ||
                $user->exidentificador == '09' ||
                $user->exidentificador == '11' ||
                $user->exidentificador == '13' ||
                $user->exidentificador == '15' ||
                $user->exidentificador == '17' ||
                $user->exidentificador == '19'
            ) {
                $letra = "A";
            }

            if ($user->exidentificador == '02' ||
                $user->exidentificador == '04' ||
                $user->exidentificador == '06' ||
                $user->exidentificador == '08' ||
                $user->exidentificador == '10' ||
                $user->exidentificador == '12' ||
                $user->exidentificador == '14' ||
                $user->exidentificador == '16' ||
                $user->exidentificador == '18' ||
                $user->exidentificador == '20'
            ) {
                $letra = "B";
            }

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

        return redirect()->route('clientes.index')->with('info', 'registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //return $cliente;
        $users = User::where('users.estado', '1')
            ->where('users.rol', 'Asesor')
            ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $cliente->id)->get();

        return view('clientes.show', compact('cliente', 'users', 'porcentajes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $mirol = Auth::user()->rol;
        $users = User::where('users.estado', '1')
            ->where('users.rol', 'Asesor')
            ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $cliente->id)->get();

        return view('clientes.edit', compact('cliente', 'users', 'porcentajes', 'mirol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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

        if ($request->tipo === '1') {
            return redirect()->route('clientes.index')->with('info', 'actualizado');
        } else {
            return redirect()->route('basefria')->with('info', 'actualizado');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->update([
            'estado' => '0'
        ]);

        return redirect()->route('clientes.index')->with('info', 'eliminado');
    }

    public function destroyid(Request $request)
    {
        $cliente->where("id", $request->clienteId)
            ->update([
                'estado' => '0'
            ]);

        return redirect()->route('clientes.index')->with('info', 'eliminado');
    }

    public function createbf()
    {
        $usersB = User::where('users.estado', '1')
            ->where('identificador', 'B')
            ->first();

        $users = collect();
        $users->put($usersB->id, $usersB->identificador);
        $usersall = User::select(
            DB::raw("CONCAT(identificador,' (ex ',IFNULL(exidentificador,''),')') AS identificador"), 'id'
        )
            ->where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->pluck('identificador', 'id');
        foreach ($usersall as $key => $value) {
            $users->put($key,$value);
        }
        return view('base_fria.create', compact('users'));
    }

    public function storebf(Request $request)
    {
        /* $request->validate([
                'celular' => 'required|unique:clientes',*/

        $cliente = Cliente::where('celular', $request->celular)->first();
        $letra = "";
        if ($cliente !== null) {

            $user = User::where('id', $cliente->user_id)->first();

            $messages = [
                'unique' => 'EL CELULAR INGRESADO SE ENCUENTA ASIGNADO AL ASESOR ' . $user->identificador,
            ];


            if ($user->exidentificador == '01' ||
                $user->exidentificador == '03' ||
                $user->exidentificador == '05' ||
                $user->exidentificador == '07' ||
                $user->exidentificador == '09' ||
                $user->exidentificador == '11' ||
                $user->exidentificador == '13' ||
                $user->exidentificador == '15' ||
                $user->exidentificador == '17' ||
                $user->exidentificador == '19'
            ) {
                $letra = "A";
            }

            if ($user->exidentificador == '02' ||
                $user->exidentificador == '04' ||
                $user->exidentificador == '06' ||
                $user->exidentificador == '08' ||
                $user->exidentificador == '10' ||
                $user->exidentificador == '12' ||
                $user->exidentificador == '14' ||
                $user->exidentificador == '16' ||
                $user->exidentificador == '18' ||
                $user->exidentificador == '20'
            ) {
                $letra = "B";
            }

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
            'estado' => '1',
            'icelular' => $letra,
        ]);

        return redirect()->route('basefria')->with('info', 'registrado');
    }

    public function editbf(Cliente $cliente)
    {
        $users = User::where('users.estado', '1')
            ->where('users.rol', 'Asesor')
            ->pluck('name', 'id');

        return view('base_fria.edit', compact('cliente', 'users'));
    }

    public function clientedeasesor(Request $request)
    {
        $mirol = Auth::user()->rol;


        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';

        $clientes = Cliente::query()
            ->where('clientes.estado', '=', '1')
            ->where('clientes.tipo', '=', '1')
            ->when($request->user_id, function ($query) use ($request) {
                return $query->whereIn('clientes.user_id', User::query()->select('users.id')->whereIdentificador($request->user_id));
            })->orderBy('clientes.id')
            ->get();


        foreach ($clientes as $cliente) {
            $cliente->pedidos_mes_deuda = $cliente->pedidos()->activo()->noPagados()->whereBetween('pedidos.created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
            $cliente->pedidos_mes_deuda_antes = $cliente->pedidos()->activo()->noPagados()->where('pedidos.created_at', '<=', now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay())->count();

            //Auth::user()->rol=='Administrador'
            if ($mirol == 'Administrador' || $mirol == 'Asistente de Administración' || Auth::user()->identificador == 'B') {
                $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular . (($cliente->icelular != null) ? '-' . $cliente->icelular : '') . '  -  ' . $cliente->nombre . '</option>';
            } else {
                if ($cliente->crea_temporal == 1) {
                    //falta considerar el tiempo ahora menos el tiempo activado temporal
                    $html .= '<option style="color:lightblue" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . ' (Temporal)</option>';
                } else {
                    //considerar deuda real
                    $saldo = DetallePedido::query()->whereEstado(1)
                        ->whereIn('pedido_id',
                            Pedido::query()->select('pedidos.id')
                                ->where('pedidos.cliente_id', '=', $cliente->id)
                                ->activo()
                        )->sum('saldo');
                    //pago | pagado
                    $deuda_anterior = Pedido::query()->noPagados()->activo()
                        ->where('pedidos.cliente_id', '=', $cliente->id)
                        ->whereBetween('created_at', [now()->startOfMonth()->subMonth()->startOfMonth(), now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()])
                        ->count();

                    $deuda_pedidos_5 = Pedido::query()->noPagados()->activo()
                        ->where('pedidos.cliente_id', '=', $cliente->id)
                        ->count();

                    //aca evalua primero si tiene deuda el mes anterior y si tiene pedidos cond deuda mayor a 5
                    if ($deuda_anterior > 0 || $deuda_pedidos_5 > 5) {
                        if ($mirol == User::ROL_APOYO_ADMINISTRATIVO) {
                            $html .= '<option style="color:red" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo > 0 ? 'Con Deuda' : '') . ')</option>';
                        } else {
                            $html .= '<option disabled style="color:red" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo > 0 ? 'Con Deuda' : '') . ')</option>';
                        }
                    } else {
                        //y si no tiene esod e arriba pasa a evaluar esta parte
                        if ($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes == 0) {
                            $html .= '<option style="color:' . ($saldo == 0 ? 'green' : 'lightblue') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo == 0 ? 'Sin Deuda ' : 'Con Deuda') . ')</option>';
                        } else if (($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes > 0) || ($cliente->pedidos_mes_deuda == 0 && $cliente->pedidos_mes_deuda_antes > 0)) {
                            if ($mirol == User::ROL_APOYO_ADMINISTRATIVO) {
                                $html .= '<option style="color:' . ($saldo == 0 ? 'green' : 'red') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                            } else {
                                $html .= '<option ' . ($saldo == 0 ? '' : 'disabled') . ' style="color:' . ($saldo == 0 ? 'green' : 'red') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                            }

                        } else {
                            $html .= '<option style="color:' . ($saldo == 0 ? 'green' : 'red') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo == 0 ? 'Sin Deuda' : 'Con Deuda') . ')</option>';
                        }
                    }
                }
            }
        }

        return response()->json(['html' => $html]);
    }

    public function clientedeasesorpagos(Request $request)
    {
        //ahora con el identificador de  Usuarios
        $mirol = Auth::user()->rol;
        $clientes = null;
        $clientes = Cliente::whereIn('user_id',
            User::query()->select('users.id')
                ->whereIn('users.rol', ['Asesor', User::ROL_ADMIN, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('users.estado', '1')
                ->where('users.identificador', $request->user_id)
        )
            ->where('clientes.estado', '1')
            ->where("clientes.tipo", "1");
        $html = "";

        $clientes = $clientes->orderBy('id', 'ASC')
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
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='".now()->startOfMonth()->format('Y-m-d h:i:s')."' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='".now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d h:i:s')."' and ped2.estado=1) as pedidos_mes_deuda_antes "),
            ]);


            //$cliente->pedidos_mes_deuda = $cliente->pedidos()->activo()->noPagados()->whereBetween('pedidos.created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
            //$cliente->pedidos_mes_deuda_antes = $cliente->pedidos()->activo()->noPagados()->where('pedidos.created_at', '<=', now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay())->count();

        $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';

        foreach ($clientes as $cliente) {
            //Auth::user()->rol=='Administrador'
            if ($mirol == 'Administrador') {
                $html .= '<option style="color:black" value="' . $cliente->id . '">' . $cliente->celular . (($cliente->icelular != null) ? '-' . $cliente->icelular : '') . '  -  ' . $cliente->nombre . '</option>';
            } else {
                /*if($cliente->crea_temporal==1)
                {
                    //falta considerar el tiempo ahora menos el tiempo activado temporal
                    $html .= '<option style="color:yellow" value="' . $cliente->id . '">' . $cliente->celular.'-'.$cliente->icelular. '  -  ' . $cliente->nombre . '</option>';
                }else*/
                {
                    $saldo = DetallePedido::query()->whereEstado(1)->whereIn('pedido_id',
                        Pedido::query()->select('pedidos.id')
                            ->where('pedidos.cliente_id', '=', $cliente->id)
                            ->whereEstado(1)
                    )->sum('saldo');

                    //considerar deuda real
                    if ($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes == 0) {
                        $html .= '<option ' . ($saldo == 0 ? 'disabled' : '') . ' style="color:' . ($saldo == 0 ? 'green' : 'lightblue') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo == 0 ? 'Sin Deuda' : '') . ')</option>';
                    } else if (($cliente->pedidos_mes_deuda > 0 && $cliente->pedidos_mes_deuda_antes > 0) || ($cliente->pedidos_mes_deuda == 0 && $cliente->pedidos_mes_deuda_antes > 0)) {
                        $html .= '<option ' . ($saldo == 0 ? 'disabled' : '') . ' style="color:' . ($saldo == 0 ? 'green' : 'black') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    } else {
                        $html .= '<option ' . ($saldo == 0 ? 'disabled' : '') . '  style="color:' . ($saldo == 0 ? 'green' : 'red') . '" value="' . $cliente->id . '">' . $cliente->celular . '-' . $cliente->icelular . '  -  ' . $cliente->nombre . '  (' . ($saldo == 0 ? 'Sin Deuda' : '') . ')</option>';
                    }
                }
            }
        }

        return response()->json(['html' => $html]);
    }


    public function clientedeasesorparapagos(Request $request)
    {
        if (!$request->user_id || $request->user_id == '') {
            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
        } else {

            $html = '<option value="">' . trans('---- SELECCIONE CLIENTE ----') . '</option>';
            $clientes = Cliente::where('clientes.user_id', $request->user_id)
                ->where('clientes.tipo', '1')
                ->get();
            foreach ($clientes as $cliente) {
                if ($cliente->deuda == "0") {
                    $html .= '<option disabled style="color:#000" value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '</option>';
                } else {
                    if (Auth::user()->rol == 'Asesor') {
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    } else if (Auth::user()->rol == 'Llamadas') {
                        $html .= '<option   style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    } else if (Auth::user()->rol == 'Jefe de lamadas') {
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '**CLIENTE CON DEUDA**</option>';
                    } else {
                        $html .= '<option  style="color:#fff" value="' . $cliente->id . '">' . $cliente->celular . '  -  ' . $cliente->nombre . '</option>';
                    }
                }

            }
        }

        return response()->json(['html' => $html]);
    }

    public function pedidosenvioclientetabla(Request $request)
    {
        $pedidos = null;
        if (!$request->cliente_id) {
        } else {

            $idrequest = $request->cliente_id;
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
                //->where('pedidos.condicion_envio', 1)
                ->whereIn('pedidos.condicion_envio_code', [Pedido::ENVIADO_OPE_INT, Pedido::RECEPCION_COURIER_INT, Pedido::ENVIO_COURIER_JEFE_OPE_INT]);
            //->whereIn('pedidos.envio', [Pedido::ENVIO_CONFIRMAR_RECEPCION, Pedido::ENVIO_RECIBIDO]);
            //->get();

            return Datatables::of(DB::table($pedidos))
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function indexabandono()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

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

        return view('clientes.abandonos', compact('anios', 'dateM', 'dateY', 'superasesor', 'mirol'));
    }

    public function indexRecientes()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

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

        return view('clientes.AbandonoRecientes', compact('anios', 'dateM', 'dateY', 'superasesor', 'mirol'));
    }

    public function indexRecientesIntermedio()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

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

        return view('clientes.AbandonoRecientesIntermedio', compact('anios', 'dateM', 'dateY', 'superasesor', 'mirol'));
    }

    public function indexabandonotabla(Request $request)
    {
        //



        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente:://CLIENTES SIN PEDIDOS
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->when($request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [Cliente::ABANDONO_RECIENTE]);
            })
            ->when(!$request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [Cliente::ABANDONO]);
            })
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='".now()->startOfMonth()->format('Y-m-d h:i:s')."' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='".now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d h:i:s')."'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                'clientes.deuda',
                'clientes.situacion'
                , DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido")
                , DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigoultimopedido ")
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $data = $data->WhereIn("u.identificador", $usersasesores);

        } else if (Auth::user()->rol == "Jefe de llamadas") {

            /*$usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);*/

        } elseif (Auth::user()->rol == "Asesor") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        } else {

            $data = $data;

        }
        //$data=$data->get();

        return Datatables::of(DB::table($data))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        //}
    }

    public function indexabandonointermediotabla(Request $request)
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
            ->join('listado_resultados as lr', 'clientes.id', 'lr.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->where('clientes.situacion', 'CASI ABANDONO')
            //->where('lr.s_2022_11','ABANDONO RECIENTE')
            //->where('lr.s_2022_12','ABANDONO')
            /*->when($request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [Cliente::ABANDONO_RECIENTE]);
            })
            ->when(!$request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [Cliente::ABANDONO_PERMANENTE]);
            })*/
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='".now()->startOfMonth()->format('Y-m-d h:i:s')."' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='".now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d h:i:s')."'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                'clientes.deuda',
                'clientes.situacion'
                , DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido")
                , DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigoultimopedido ")
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);

        } elseif (Auth::user()->rol == "Asesor") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        } else {

            $data = $data;

        }

        return Datatables::of(DB::table($data))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function indexrecurrente()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

        $data = null;

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
        if (Auth::user()->rol == "Llamadas" || Auth::user()->rol == "Llamadas") {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                ->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        } else {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                //->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }
        return view('clientes.recurrentes', compact('superasesor', 'users', 'dateM', 'dateY', 'anios', 'mirol'));
    }

    public function indexnuevo()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

        $data = null;

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
        if (Auth::user()->rol == "Llamadas" || Auth::user()->rol == "Llamadas") {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                ->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        } else {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                //->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }
        return view('clientes.nuevos', compact('superasesor', 'users', 'dateM', 'dateY', 'mirol', 'anios'));
    }

    public function indexrecuperado()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

        $data = null;

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
        if (Auth::user()->rol == "Llamadas" || Auth::user()->rol == "Llamadas") {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                ->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        } else {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                //->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }
        return view('clientes.recuperados', compact('superasesor', 'users', 'dateM', 'dateY', 'mirol', 'anios'));
    }


    public function indexRecuperadoRecientes()
    {
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');
        $mirol = Auth::user()->rol;

        $data = null;

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
        if (Auth::user()->rol == "Llamadas" || Auth::user()->rol == "Llamadas") {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                ->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        } else {
            $users = User::
            where('estado', '1')
                ->whereIn('rol', ['Asesor', 'Super asesor'])
                //->where('users.llamada', Auth::user()->id)
                ->pluck('identificador', 'id');
        }
        return view('clientes.recuperadosRecientes', compact('superasesor', 'users', 'dateM', 'dateY', 'mirol', 'anios'));
    }


    public function indexnuevotabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente:://CLIENTES SIN PEDIDOS
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->whereIn('clientes.situacion', [Cliente::NUEVO])
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='".now()->startOfMonth()->format('Y-m-d h:i:s')."' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='".now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d h:i:s')."'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                'clientes.deuda',
                //DB::raw(" (select lr.s_2022_11 from clientes c inner join listado_resultados lr on c.id=lr.id limit 1) as situacion")
                'clientes.situacion'
                , DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido")
                , DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigoultimopedido ")
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $data = $data->WhereIn("u.identificador", $usersasesores);


        } else if (Auth::user()->rol == "Jefe de llamadas") {

          /*$usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);*/
        }


        elseif (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        } else {

            $data = $data;

        }
        //$data=$data->get();

        return Datatables::of(DB::table($data))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        //}
    }

    public function indexrecurrentetabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente:://CLIENTES SIN PEDIDOS
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->whereIn('clientes.situacion', [Cliente::RECURRENTE])
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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
                //DB::raw(" (select lr.s_2022_11 from clientes c inner join listado_resultados lr on c.id=lr.id limit 1) as situacion")
                'clientes.situacion'
                , DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido")
                , DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigoultimopedido ")
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $data = $data->WhereIn("u.identificador", $usersasesores);


        } else if (Auth::user()->rol == "Jefe de llamadas") {
            /*$usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);*/
        } elseif (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn('u.identificador', $usersasesores);
        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        } else {

            $data = $data;

        }
        //$data=$data->get();

        return Datatables::of(DB::table($data))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        //}
    }

    public function indexrecuperadotabla(Request $request)
    {
        //
        $dateM = Carbon::now()->format('m');
        $dateY = Carbon::now()->format('Y');

        $data = null;

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

        $data = Cliente:://CLIENTES SIN PEDIDOS
        join('users as u', 'clientes.user_id', 'u.id')
            ->leftjoin('pedidos as p', 'clientes.id', 'p.cliente_id')
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '1')
            ->when($request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [$request->situacion]);
            })
            ->when(!$request->has("situacion"), function ($query) use ($request) {
                $query->whereIn('clientes.situacion', [Cliente::RECUPERADO_PERMANENTE]);
            })
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
                'clientes.pidio',
                'clientes.situacion'
            )
            ->select('clientes.id',
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
                DB::raw(" (select count(ped.id) from pedidos ped where ped.cliente_id=clientes.id and ped.pago in (0,1) and ped.pagado in (0,1) and ped.created_at >='".now()->startOfMonth()->format('Y-m-d h:i:s')."' and ped.estado=1) as pedidos_mes_deuda "),
                DB::raw(" (select count(ped2.id) from pedidos ped2 where ped2.cliente_id=clientes.id and ped2.pago in (0,1) and ped2.pagado in (0,1) and ped2.created_at <='".now()->startOfMonth()->subMonth()->endOfMonth()->endOfDay()->format('Y-m-d h:i:s')."'  and ped2.estado=1) as pedidos_mes_deuda_antes "),
                'clientes.deuda',
                //DB::raw(" (select lr.s_2022_11 from clientes c inner join listado_resultados lr on c.id=lr.id limit 1) as situacion")
                'clientes.situacion'
                , DB::raw("(select DATE_FORMAT(dp1.created_at,'%Y-%m-%d %h:%i:%s') from pedidos dp1 where dp1.estado=1 and dp1.cliente_id=clientes.id order by dp1.created_at desc limit 1) as fechaultimopedido")
                , DB::raw(" (select (dp.codigo) from pedidos dp where dp.estado=1 and dp.cliente_id=clientes.id order by dp.created_at desc limit 1) as codigoultimopedido ")
            );

        if (Auth::user()->rol == "Llamadas") {

            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            //$pedidos=$pedidos->WhereIn('pedidos.user_id',$usersasesores);
            $data = $data->WhereIn("u.identificador", $usersasesores);


        } else if (Auth::user()->rol == "Jefe de llamadas") {
            /*$usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);*/
        } elseif (Auth::user()->rol == "Asesor") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn('u.identificador', $usersasesores);

        } else if (Auth::user()->rol == "Encargado") {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.supervisor', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data = $data->WhereIn("u.identificador", $usersasesores);
        }

        return Datatables::of(DB::table($data))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        //}
    }

    public function deudasCopyAjax(Request $request)
    {
        $this->validate($request, [
            'cliente_id' => 'required'
        ]);
        $cliente = Cliente::query()->with('user')->findOrFail($request->cliente_id);
        $mensajesRandom = [];
        $mensajesRandom[] = '*Amigo buenos dias, por favor neceisto que me cancele el pago, le mando el resumen*';
        $mensajesRandom[] = '*Amigo buenos días el saldito pendiente que tenemos por favor no se olvide, le envio el detalle.';
        $mensajesRandom[] = '*Buenas tardes estimado, le envio el total de la deuda para que me deposite por favor, le mando el resumen para que me cancele. ';

        $messajeKey = array_rand($mensajesRandom);
        $messaje = $mensajesRandom[$messajeKey];
        $pedidos = Pedido::query()->with(['cliente', 'pagoPedidos', 'detallePedidos'])
            ->join('detalle_pedidos as dp', 'pedidos.id', 'dp.pedido_id')
            ->select(
                'pedidos.id',
                'pedidos.created_at',
                'dp.nombre_empresa',
                'dp.cantidad',
                'dp.porcentaje',
                'dp.ft',
                'dp.courier',
                'dp.total',
                'dp.saldo as diferencia',
            )
            ->where('pedidos.created_at', '<=', now()->subDays(7)->format('Y-m-d H:i:s'))
            ->where('pedidos.cliente_id', $cliente->id)
            ->where('pedidos.condicion_code', '<>', Pedido::ANULADO_INT)
            ->get()
            ->map(function (Pedido $pedido) {
                $pedido->adelanto = $pedido->pagoPedidos()->whereEstado(1)->sum('abono');
                // $pedido->deuda_total = $pedido->detallePedidos()->sum("saldo");
                return $pedido;
            });
        $totalDeuda = $pedidos->sum('diferencia');

        return response()->json([
            "html" => view('clientes.response.modal_data_clientes_deuda', compact('messaje', 'pedidos', 'totalDeuda', 'cliente'))->render()
        ]);
    }
}
