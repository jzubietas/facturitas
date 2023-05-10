<?php

namespace App\View\Components\dashboard\graficos;

use App\Abstracts\Widgets;
use App\Models\Pedido;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class CobranzasMesesProgressBar extends Widgets
{
    public $progressData = [];

    public $general = [];

    public $totales = [];
    private $addids = [];

    public $total_dias = 4;


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->generateDataByMonth($this->total_dias);
        $title = $this->getDateTitle();
        if (\auth()->user()->rol == User::ROL_ASESOR) {
            $this->progressData = [];
        }
        $totalMonths = $this->generalTotal();

        return view('components.dashboard.graficos.cobranzas-meses-progress-bar', compact('title', 'totalMonths'));
    }

    public function getStartDate(){
        return $this->startDate->clone();
    }
    public function getEndDate(){
        return $this->endDate->clone();
    }

    function getQueryActivos()
    {
        return Pedido::query()->activo()->whereBetween('pedidos.created_at', [
            $this->getStartDate(),
            $this->getEndDate(),
        ]);
    }

    function getQueryPay()
    {
        return Pedido::query()
            ->select(['pedidos.id'])
            //->select(['pedidos.id', \DB::raw('sum(detalle_pedidos.total) AS total'), \DB::raw('sum(pago_pedidos.abono) as abonado')])
            ->activo()
            ->pagados()
            //->join('detalle_pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->join('pago_pedidos', 'pago_pedidos.pedido_id', '=', 'pedidos.id')
            //->join('pagos', 'pagos.id', '=', 'pago_pedidos.pago_id')
            //->where('detalle_pedidos.estado', '=', 1)
            ->where('pago_pedidos.estado', '=', 1)
            //->whereNotIn('pedidos.user_id', $asesorB)
            ->groupBy('pedidos.id')
            ->whereBetween('pedidos.created_at', [
                $this->getStartDate(),
                $this->getEndDate(),
            ]);
    }

    public function getDataProgress($identificador, $ids, $name, CarbonInterface $date, &$restartTotal)
    {
        $all = $this->getQueryActivos()->whereIn('pedidos.user_id', $ids)->count();

        if ($all > 0) {
            $all -= $restartTotal;
        }

        $pay = \DB::table(
            $this->getQueryPay()->whereIn('pedidos.user_id', $ids)
                ->whereBetween('pago_pedidos.created_at', [
                    $date->clone(),
                    $date->clone()->endOfMonth()->endOfDay(),
                ]), 'temp_table'
        )
            ->count();
        $restartTotal += $pay;

        if ($all > 0) {
            $p = round(($pay / $all) * 100, 2);
        } else {
            $p = 0;
        }
        return [
            "identificador" => $identificador,
            "code" => "Asesor {$identificador}",
            "name" => $name,
            "activos" => $all,
            "pagados" => $pay,
            "progress" => $p,
            "date" => $date->clone(),
        ];
    }

    public function generalTotal()
    {
        $asesorB = User::activo()->whereIdentificador('B')->pluck('id')->values()->all();

        $all = $this->getQueryActivos()->whereNotIn('pedidos.user_id', $asesorB)->count();

        $pay = \DB::table(
            $this->getQueryPay()
                ->whereNotIn('pedidos.user_id', $asesorB)
                ->whereBetween('pago_pedidos.created_at', [
                    $this->getStartDate(),
                    $this->getStartDate()->addMonths($this->total_dias - 1)->endOfMonth()->endOfDay(),
                ])->getQuery(), 'temp_table'
        )
            //->whereRaw('total=abonado')
            ->count();


        if ($all > 0) {
            $p = round(($pay / $all) * 100, 2);
        } else {
            $p = 0;
        }

        return [
            "total" => $all,
            "pagados" => $pay,
            "progress" => $p,
        ];
    }

    public function getMonthYear(CarbonInterface $date)
    {
        return \Str::upper(substr($date->monthName, 0, 3) . ' ' . $date->format('y'));
    }

    public function generateDataByMonth($countDates = 4)
    {
        if (auth()->user()->rol == User::ROL_LLAMADAS) {//HASTA MAÑANA
            //$id = auth()->user()->id;
            $asesores =[];// User::rolAsesor()->where('llamada', '=', $id)->get();
        } else {
            $encargado = null;
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                $encargado = auth()->user()->id;
            }
            $asesores = User::query()
                ->activo()
                ->rolAsesor()
                ->when($encargado != null, function ($query) use ($encargado) {
                    return $query->where('supervisor', '=', $encargado);
                })
                ->get();
        }

        $progressData = [];
        $asesoresIdentificadores = [];
        $asesoresNames = [];
        foreach ($asesores as $asesor) {
            if (auth()->user()->rol != User::ROL_ADMIN
                && auth()->user()->rol != User::ROL_JEFE_LLAMADAS//HASTA MAÑANA
                //&& auth()->user()->rol != User::ROL_LLAMADAS//HASTA MAÑANA
                ) {
                if (auth()->user()->rol != User::ROL_ENCARGADO) {
                    if (auth()->user()->id != $asesor->id) {
                        continue;
                    }
                } else {
                    if (auth()->user()->id != $asesor->supervisor) {
                        continue;
                    }
                }
            }
            if ($asesor->identificador == 'B') {
                continue;
            }

            if (!isset($asesoresIdentificadores[$asesor->identificador])) {
                $asesoresIdentificadores[$asesor->identificador] = [];
                $asesoresNames[$asesor->identificador] = [];
            }
            $asesoresIdentificadores[$asesor->identificador][] = $asesor->id;
            $asesoresNames[$asesor->identificador][] = explode(" ", $asesor->name)[0];
        }
        foreach ($asesoresIdentificadores as $identificador => $ids) {
            $currentCount = 0;
            $restartTotal = 0;
            while ($currentCount < $countDates) {
                $dateCurrent = $this->getStartDate()->addMonths($currentCount);
                $progressData[$identificador][$this->getMonthYear($dateCurrent)] = $this->getDataProgress($identificador, $ids, collect($asesoresNames[$identificador])->first(), $dateCurrent, $restartTotal);
                $currentCount++;
            }
            $a = collect($progressData[$identificador])->values()->sum('activos');
            $pp = collect($progressData[$identificador])->values()->sum('pagados');
            if ($a > 0) {
                $p = round(($pp / $a) * 100, 2);
            } else {
                $p = 0;
            }
            $this->totales[$identificador] = [
                'activos' => $a,
                'pagados' => $pp,
                'progress' => $p,
            ];
        }
        $alldata = [];
        $activos = [];
        $pagados = [];
        foreach ($progressData as $dates) {
            foreach ($dates as $datestr => $item) {
                $all = data_get($item, 'activos');
                $pay = data_get($item, 'pagados');
                $activos[$datestr][] = $all;
                $pagados[$datestr][] = $pay;
            }
        }
        //$restartTotal = 0;
        foreach ($activos as $datestr => $list) {
            $all = collect($list)->sum();// - $restartTotal;
            $pay = collect($pagados[$datestr])->sum();
            //$restartTotal += $pay;
            if ($all > 0) {
                $p = round(($pay / $all) * 100, 2);
            } else {
                $p = 0;
            }
            if (auth()->user()->rol == User::ROL_ADMIN) {
                $name = 'General';
            } else {
                $name = auth()->user()->name;
            }
            $alldata[$datestr] = [
                "code" => '',
                "name" => $name,
                "progress" => $p,
                "activos" => $all,
                "pagados" => $pay,
            ];
        }

        $this->progressData = $progressData;
        $this->general = $alldata;

    }

}
