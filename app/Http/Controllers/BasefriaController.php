<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Porcentaje;
use App\Models\User;

//use App\DataTables\BasefriaDataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\HtmlString;

class BasefriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


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

        return view('base_fria.index', compact('superasesor', 'users'));
    }

    public function indextabla(Request $request)
    {
        //
        //return $dataTable->render('base_fria.index');
        //if ($request->ajax()) {

        DB::enableQueryLog();


        $data = Cliente::
        join('users as u', 'clientes.user_id', 'u.id')
            ->select('clientes.id',
                'clientes.nombre',
                'clientes.icelular',
                'clientes.celular',
                'u.identificador as identificador',
                'u.rol'
            )
            ->where('clientes.estado', '1')
            ->where('clientes.tipo', '0');
        //->get();

        if (Auth::user()->rol == 'Llamadas') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
            $data = $data->WhereIn("u.identificador", $usersasesores);

        } /*
        else if(Auth::user()->rol == 'Jefe de llamadas')

        {


            $usersasesores = User::where('users.rol', 'Asesor')
                -> where('users.estado', '1')
                -> where('users.llamada', Auth::user()->id)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');

            $data=$data->WhereIn("u.identificador",$usersasesores);



        }
        */

        else if (Auth::user()->rol == 'Asesor') {
            $usersasesores = User::where('users.rol', 'Asesor')
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.id as id")
                )
                ->pluck('users.id');

            $data = $data->WhereIn('u.id', $usersasesores);

        } else if (Auth::user()->rol == 'Encargado') {

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
        $data = $data->get();

        // dd(DB::getQueryLog());
        // exit;

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function cargarid(Request $request)
    {
        if (!$request->basefria_id) {
            $html = '';
        } else {
            $data = Cliente::
            join('users as u', 'clientes.user_id', 'u.id')
                ->select('clientes.id',
                    'clientes.nombre',
                    'clientes.celular',
                    'u.identificador as identificador',
                    'u.rol'
                )
                ->where('clientes.estado', '1')
                ->where('clientes.tipo', '0')
                ->where('clientes.id', $request->basefria_id)
                ->get();

            $html = $data;
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
        $usersB = User::where('users.estado', '1')
            ->whereIn('rol', [User::ROL_ASESOR_ADMINISTRATIVO])
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
            $users->put($key, $value);
        }
        return view('base_fria.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $searchCliente = Cliente::query()->with('user')->where('celular', '=', $request->celular)->first();

        $messages = [];

        if ($searchCliente != null) {
            $messages = [
                'celular.unique' => 'EL CELULAR INGRESADO SE ENCUENTA ASIGNADO AL ASESOR <b>' . $searchCliente->user->identificador.'</b>',
            ];
        }
        $request->validate([
            'celular' => 'required|unique:clientes',
        ], $messages);

        //return $request;
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
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            /* DB::rollback();
            dd($th); */
        }
        return redirect()->route('basefria')->with('info', 'registrado');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $basefrium)
    {
        //
        //return $basefrium;
        $mirol = Auth::user()->rol;
        $users = User::where('users.estado', '1')
            ->whereIn('users.rol', ['Asesor', 'ASESOR ADMINISTRATIVO'])
            ->pluck('name', 'id');
        $porcentajes = Porcentaje::where('cliente_id', $basefrium->id)->get();

        return view('base_fria.edit', compact('basefrium', 'users', 'porcentajes', 'mirol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $basefrium)
    {
        //return $basefrium;
        $request->validate([
            //'nombre' => 'required',
            //'dni' => 'required',
            'celular' => 'required',
            //'provincia' => 'required',
            //'distrito' => 'required',
            //'direccion' => 'required',
            //'referencia' => 'required',
            //'porcentaje' => 'required',
        ]);

        $basefrium->update([
            'nombre' => $request->nombre,
            'dni' => $request->dni,
            'celular' => $request->celular,
            'tipo' => '0'
        ]);

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
    public function destroy($id)
    {
        //
    }

    public function updatebfpost(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            //'dni' => 'required',
            'celular' => 'required',
            //'provincia' => 'required',
            //'distrito' => 'required',
            // 'direccion' => 'required',
            // 'referencia' => 'required',
            'porcentaje' => 'required',
        ]);
        //$id=null;
        //Selection::whereId($id)->update($request->all());
        $cliente = Cliente::where('clientes.id', $request->hiddenID)->update([
            'nombre' => $request->nombre,
            //'dni' => $request->dni,
            'celular' => $request->celular,
            //'provincia' => $request->provincia,
            // 'distrito' => $request->distrito,
            // 'direccion' => $request->direccion,
            //'referencia' => $request->referencia,
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

        return redirect()->route('clientes.index')->with('info', 'registrado');
    }

    public function celularduplicado(Request $request)
    {

        $request->celular;
        $validar = Cliente::where('celular', $request->celular)->count();
        $status = true;
        $data = 'NO PUEDE CONTINUAR';
        if ($validar > 0) {
            $status = false;
            $data = 'NO PUEDE CONTINUAR';
        }

        return response()->json([
            "html" => array('status' => $status, 'data' => $data)
        ]);
    }
}
