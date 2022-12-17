<?php

namespace App\View\Components\dashboard\graficos;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\View\Component;

class MetaProgressBar extends Component
{
    public $general = [];
    public $progressData = [];

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
        if (auth()->user()->rol == User::ROL_ADMIN) {
            $this->generalData();
        } else {
            $this->general = (object)[
                "enabled" => true,
            ];
        }
        $this->asesores();
        return view('components.dashboard.graficos.meta-progress-bar');
    }

    public function generalData()
    {
        $pedidtosActivos = Pedido::query()->activo()->whereBetween('created_at',[
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();
        $pedidtosPagados = Pedido::query()->activo()->pagados()->whereBetween('created_at',[
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();
        if($pedidtosActivos>0) {
            $progress = intval(($pedidtosPagados / $pedidtosActivos) * 100);
        }else{
            $progress=0;
        }
        $this->general = (object)[
            "enabled" => true,
            "code" => '',
            "name" => 'General',
            "progress" => $progress,
            "activos" => $pedidtosActivos,
            "pagados" => $pedidtosPagados,
        ];
    }

    public function asesores()
    {
        $asesores = User::query()->activo()->rolAsesor()->get();
        $progressData = [];
        foreach ($asesores as $asesor) {
            if (auth()->user()->rol != User::ROL_ADMIN) {
                if (auth()->user()->rol != User::ROL_ENCARGADO) {
                    if (auth()->user()->id != $asesor->id) {
                        continue;
                    }
                }else{
                    if (auth()->user()->id != $asesor->supervisor) {
                        continue;
                    }
                }
            }

            $all = Pedido::query()->whereUserId($asesor->id)->activo()->whereBetween('created_at',[
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])->count();
            $pay = Pedido::query()->whereUserId($asesor->id)->activo()->whereBetween('created_at',[
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ])->pagados()->count();

            if ($all > 0) {
                $p = intval(($pay / $all) * 100);
            } else {
                $p = 0;
            }

            //$p2 = intval(($pay / ($pedidtosActivos/count($asesores))) * 100);

            $progressData[] = [
                "code" => "{$asesor->identificador}",
                "name" => $asesor->name,
                "progress" => $p,
                //"progress2" => $p2,
                "activos" => $all,
                "pagados" => $pay,
            ];

        }
        $this->progressData = collect($progressData)->sortByDesc('progress')->all();
    }

}
