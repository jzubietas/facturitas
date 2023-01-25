<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\DireccionEnvio;
use App\Models\DireccionGrupo;
use App\Models\Distrito;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OlvaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distribuir = [
            "NORTE" => 'NORTE',
            "CENTRO" => 'CENTRO',
            "SUR" => 'SUR',
        ];

        $condiciones = [
            "1" => 1,
            "2" => 2,
            "3" => 3
        ];

        $destinos = [
            "LIMA" => 'LIMA',
            "PROVINCIA" => 'PROVINCIA'
        ];

        $distritos = Distrito::whereIn('provincia', ['LIMA', 'CALLAO'])
            ->where('estado', '1')
            ->pluck('distrito', 'distrito');

        $departamento = Departamento::where('estado', "1")
            ->pluck('departamento', 'departamento');

        $direcciones = DireccionEnvio::join('direccion_pedidos as dp', 'direccion_envios.id', 'dp.direccion_id')
            ->select([
                'direccion_envios.id',
                'direccion_envios.distrito',
                'direccion_envios.direccion',
                'direccion_envios.referencia',
                'direccion_envios.nombre',
                'direccion_envios.celular',
                'dp.pedido_id as pedido_id',
            ])
            ->where('direccion_envios.estado', '1')
            ->where('dp.estado', '1')
            ->get();

        $superasesor = User::where('rol', 'Super asesor')->count();

        if (Auth::user()->rol == "Asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Super asesor") {
            $ver_botones_accion = 0;
        } else if (Auth::user()->rol == "Encargado") {
            $ver_botones_accion = 1;
        } else {
            $ver_botones_accion = 1;
        }
        return view('envios.olva.index', compact('condiciones', 'distritos', 'direcciones', 'destinos', 'superasesor', 'ver_botones_accion', 'departamento', 'distribuir'));
    }


    public function table()
    {
        $pedidos_provincia = DireccionGrupo::join('clientes', 'clientes.id', 'direccion_grupos.cliente_id')
            ->join('users', 'users.id', 'clientes.user_id')
            ->activo()
            ->whereIn('direccion_grupos.condicion_envio_code', [
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            ])
            ->whereNull('direccion_grupos.courier_failed_sync_at')
            ->where('direccion_grupos.distribucion', 'OLVA')
            ->where('direccion_grupos.motorizado_status', '0')
            ->whereNull('direccion_grupos.add_screenshot_at')
            ->select([
                'direccion_grupos.*',
                "clientes.celular as cliente_celular",
                "clientes.nombre as cliente_nombre",
            ]);

        add_query_filtros_por_roles_pedidos($pedidos_provincia, 'users.identificador');

        return datatables()->query(DB::table($pedidos_provincia)
            ->orderByDesc('courier_failed_sync_at')
            ->orderByDesc('id'))
            ->addIndexColumn()
            ->editColumn('created_at_format', function ($pedido) {
                if ($pedido->created_at != null) {
                    return Carbon::parse($pedido->created_at)->format('d-m-Y h:i A');
                } else {
                    return '';
                }
            })
            ->editColumn('direccion_format', function ($pedido) {
                return collect(explode(',', $pedido->direccion))->trim()
                    ->map(function ($f) use ($pedido) {
                        if ($pedido->courier_failed_sync_at != null) {
                            return '<b class="d-flex">' . $f . '<i data-jqconfirm="edit_tracking" data-action="' . route('envios.seguimientoprovincia.update', [
                                    'direccion_grupo_id' => $pedido->id,
                                    'action' => 'update_tracking',
                                ]) . '" data-code="' . $f . '" role="button" class="fa fa-pencil-alt rounded p-1 bg-info"></i></b>';
                        }
                        return '<b>' . $f . '</b>';
                    })->join('<br>');
            })
            ->editColumn('referencia_format', function ($pedido) {
                $html = collect(explode(',', $pedido->referencia))->trim()->map(fn($f) => '<b>' . $f . '</b>')->join('<br>') . '<br>';


                $html .= collect(explode(',', $pedido->observacion))->trim()->map(fn($f) => '<a target="_blank" href="' . \Storage::disk('pstorage')->url($f) . '"><i class="fa fa-file-pdf"></i>Ver Rutulo</a>')->join('<br>');

                $html .= '<p>';
                return $html;
            })
            ->addColumn('condicion_envio_format', function ($pedido) {
                $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);
                $html = '<span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
                return $html;
            })
            ->addColumn('action', function ($pedido) {
                return '<button data-action="' . route('envios.olva.store', $pedido->id) . '" data-jqconfirm="notificado" class="btn btn-warning">Notificado</button>';
            })
            ->rawColumns(['action', 'referencia_format', 'condicion_envio_format', 'direccion_format'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DireccionGrupo $grupo)
    {
        $this->validate($request, [
            'file' => 'required|file'
        ]);
        if ($grupo->add_screenshot_at == null) {
            $file = $request->file('file');
            $grupo->addMedia($file)
                ->usingFileName(\Str::random(5) . '_' . $file->getClientOriginalName())
                ->toMediaCollection('tienda_olva_notificado');

            $grupo->update([
                'add_screenshot_at' => now()
            ]);
        } else {
            return response()->json([
                'data' => $grupo,
                'success' => false
            ]);
        }
        return response()->json([
            'data' => $grupo,
            'success' => true
        ]);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
