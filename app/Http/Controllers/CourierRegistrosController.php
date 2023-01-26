<?php

namespace App\Http\Controllers;

use App\Models\CourierRegistro;
use App\Models\DireccionGrupo;
use App\Models\MovimientoBancario;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierRegistrosController extends Controller
{
    public function indexi()
    {
        $superasesor = User::where('rol', 'Super asesor')->count();
        return view('courier_registro.index', compact('superasesor' ));
    }
    public function indextabla(Request $request)
    {
        $relacionado=$request->relacionado;
        $courier = CourierRegistro::where('courier_registros.id','<>', '0')
            ->rightJoin('direccion_grupos as a','a.referencia','courier_registros.courier_registro')
            ->select([
                'courier_registros.*',
                'a.referencia as permitir','a.correlativo as direcciongrupo_correlativo',
        ])
            ->where("a.estado","1")->whereNotNull("a.referencia")->whereNull("a.courier_failed_sync_at")
        ->where('courier_registros.relacionado',$relacionado);
        return Datatables::of(DB::table($courier))
            ->addIndexColumn()
            ->editColumn('relacionado', function($courier){
                if($courier->relacionado=='1')
                {
                    return '<span class="bagde bagde-success">Relacionado ('.$courier->direcciongrupo_correlativo.')</span>';
                }
                return 'Sin relacionar';
            })
            ->editColumn('status', function($courier){
                if($courier->status=='1')
                {
                    return '<span class="badge badge-warning">Activo</span>';
                }
                return 'Sin relacionar';
            })
            ->addColumn('action', function($courier){
                $btn='';
                $btn .= '<ul class="list-unstyled pl-0">';
                if($courier->relacionado=="0")
                {
                    if($courier->permitir!=null)
                    {
                        $btn .= '<li>
                            <a href="" class="btn-sm text-warning" data-target="#modal-relacionar-registro_courier"
                                data-toggle="modal" data-ide="' . $courier->id . '"
                                data-status="' . $courier->status . '" data-relacionado="' . $courier->relacionado . '"
                                data-courierreg="'.$courier->id.'" data-courierreg-registro="'.$courier->courier_registro.'">
                                <i class="fas fa-envelope text-success"></i> Relacionar</a></li>
                            </a>
                        </li>';
                    }else{
                        $btn .='<li><span class="badge badge-danger">Corregir manual</span></li>';
                    }

                }
                $btn .= '</ul>';
                return $btn;
            })
            ->rawColumns(['action','status','relacionado'])
            ->make(true);
    }
    public function register(Request $request)
    {
        $numregistro = $request->numregistro;

        $registro = CourierRegistro::create([
            'courier_registro' => $numregistro,
            'user_created'=>auth()->user()->id,
            'status'=>"1"
        ]);
        $html="ok|0";
        return response()->json(['html' => $html]);
    }
    public function validarregister(Request $request)
    {
        if($request->numregistro){
            $numregistro = trim($request->numregistro);
            $count=CourierRegistro::where('courier_registro',$numregistro)->where('status','1')->count();
            if($count)
            {
                if($count>0){
                    return response()->json(['html' => 1]);
                }else{
                    return response()->json(['html' => 0]);
                }
            }
        }else{
            return response()->json(['html' => 2]);
        }

    }

    public function Relacionar(Request $request)
    {
        $courierregistro = $request->courierregistro;
        $env = $request->direcciongrupo;
        if ($request->direcciongrupo && $request->courierregistro) {
            $row_courierregistro = CourierRegistro::where("courier_registro",$courierregistro);
            $row_env=DireccionGrupo::findOrFail($env);
            DB::beginTransaction();
            $row_courierregistro->update([
                "user_updated" => auth()->user()->id,
                "relacionado" => "1",
                "rel_direcciongrupo" => $row_env->id,
                "rel_fechadp"=>$row_env->fecha,
                "rel_importe"=>$row_env->importe,
                "rel_tracking"=>$row_env->direccion,
                "rel_userid"=>$row_env->user_id,
                "rel_fecharel"=>now(),
            ]);
            $row_env->update(['relacionado'=>"1"]);
            DB::commit();
            return response()->json(['html' => $row_env->id]);
        }
        else{
            return response()->json(['html' => 0]);
        }

    }

}
