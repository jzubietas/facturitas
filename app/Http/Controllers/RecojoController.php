<?php

namespace App\Http\Controllers;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecojoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index(Request $request)
  {

    if ($request->has('datatable')) {
      $query = DireccionGrupo::
      join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
        ->join('users as u', 'u.id', 'c.user_id')
        /*->when($fecha_consulta != null, function ($query) use ($fecha_consulta) {
          $query->whereDate('direccion_grupos.fecha_salida', $fecha_consulta);
        })*/
        ->select([
          'direccion_grupos.*',
        ]);

      $query
        ->where('direccion_grupos.estado', '1')
        ->where('direccion_grupos.condicion_envio_code', Pedido::ENTREGADO_RECOJO_JEFE_OPE_INT);

      /*if (\auth()->user()->rol == User::ROL_MOTORIZADO) {
        $query = $query->where('direccion_grupos.motorizado_id', '=', auth()->id());
      }*/


      //add_query_filtros_por_roles($query, 'u');
      return datatables()->query(DB::table($query))
        ->addIndexColumn()
        /*->editColumn('fecha_recepcion', function ($pedido) {
          if ($pedido->fecha_recepcion != null) {
            return Carbon::parse($pedido->fecha_recepcion)->format('d-m-Y h:i A');
          } else {
            return '';
          }
        })*/
        ->editColumn('condicion_envio', function ($pedido) {
          $color = Pedido::getColorByCondicionEnvio($pedido->condicion_envio);

          return '<span class="badge badge-dark p-8" style="color: #fff; background-color: #347cc4; font-weight: 600; margin-bottom: -2px;border-radius: 4px 4px 0px 0px; font-size:8px;  padding: 4px 4px !important; font-weight: 500;">Direccion agregada</span><span class="badge badge-success" style="background-color: #00bc8c !important;
                    padding: 4px 8px !important;
                    font-size: 8px;
                    margin-bottom: -4px;
                    color: black !important;">Con ruta</span><span class="badge badge-success" style="background-color: ' . $color . '!important;">' . $pedido->condicion_envio . '</span>';
        })
        ->addColumn('action', function ($pedido)  {

          $btn = '<ul class="list-unstyled mt-sm-20">';
          $btn = '</ul>';

          return $btn;
        })
        ->rawColumns(['action', 'condicion_envio'])
        ->toJson();
    }
    return view('operaciones.recojo.index');
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
        //
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
    public function destroy($id)
    {
        //
    }
}
