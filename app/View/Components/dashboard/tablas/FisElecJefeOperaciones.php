<?php

namespace App\View\Components\dashboard\tablas;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class FisElecJefeOperaciones extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $resultados = $this->genData();
        return view('components.dashboard.tablas.fis-elec-jefe-operaciones', compact('resultados'));
    }

    public function genData()
    {
        $jefesOpe = User::activo()
            ->where('rol', '=', User::ROL_JEFE_OPERARIO)
            ->where('id', '=', Auth::user()->id)
            ->first();

        $data = [];
        $operarios = User::activo()
            ->where('rol', '=', User::ROL_OPERARIO)
            ->where('jefe', $jefesOpe->id)
            ->get();

        foreach ($operarios as $user) {
            $asesores = User::activo()
                ->whereIn('rol', [User::ROL_ASESOR, User::ROL_ASESOR_ADMINISTRATIVO])
                ->where('operario','=', $user->id)
                ->pluck('id');


            $fi = Pedido::query()
                ->activo()
                ->porAtenderEstatus()
                ->whereIn('user_id', $asesores)
                ->whereIn(
                    'id',
                    DetallePedido::query()->select('pedido_id')
                        ->activo()
                        ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                        ->where('detalle_pedidos.tipo_banca', 'like', '%FISICO%')
                )
                ->count();

            $el = Pedido::query()
                ->activo()
                ->whereIn('user_id', $asesores)
                ->porAtenderEstatus()
                ->whereIn(
                    'id',
                    DetallePedido::query()->select('pedido_id')
                        ->activo()
                        ->whereRaw('detalle_pedidos.pedido_id=pedidos.id')
                        ->where('detalle_pedidos.tipo_banca', 'like', '%ELECTRONICA%')
                )
                ->count();


            $data[] = (object)[
                "operario" => $user->name,
                "fisico" => $fi,
                "electronico" => $el
            ];
        }
        return $data;
    }
}
