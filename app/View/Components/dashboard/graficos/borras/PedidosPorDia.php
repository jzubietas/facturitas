<?php

namespace App\View\Components\dashboard\graficos\borras;

use App\Abstracts\GraficosComponent\GraficosComponent;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Str;

class PedidosPorDia extends GraficosComponent
{
    public $rol = null;
    public $dataChart = [];


    public function __construct($rol, $title = null, $labelX = null, $labelY = null, string $height = '370')
    {
        parent::__construct($title, $labelX, $labelY, $height);
        $this->rol = $rol;
        $this->genId = Str::slug($rol, '') . Str::random(40);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (method_exists($this, $this->rol)) {
            $this->{$this->rol}();
        }
        return view('components.dashboard.graficos.borras.pedidos-por-dia');
    }

    public function Asesor()
    {
        $this->applyData(function ($query) {
            $query->where('users.rol', '=', $this->rol);
        });
    }

    public function Encargado()
    {
        $this->applyData(function ($query) {
            $query->where(function ($query) {
                $query->where('users.rol', '=', 'Encargado');
            });
        },function ($user){
            $ids = User::query()->whereRol('Asesor')->whereSupervisor($user->id)->pluck('id');
            $pedidos = Pedido::query()->where('pedidos.estado', '<>', '0')
                ->whereIn('user_id', $ids)
                ->whereDate('pedidos.created_at', '=', now()->startOfDay())
                ->count();
            return [
                'label' => $user->name . ' (' . $user->identificador . ')',
                'y' => $pedidos,
            ];
        });

    }

    public function Administrador()
    {
        $this->applyData(function ($query) {
            $query->where(function ($query) {
                $query->where('users.rol', '=', 'Asesor');
                $query->orWhere('users.identificador', '=', 'B');
            });
        });
    }

    public function applyData(callable $callback,callable $callbackSubConsulta=null)
    {
        $query = User::query();
        $callback($query);
        $this->dataChart = $query
            //->whereRol($this->rol)
            ->where('users.estado', '=', 1)
            ->get()
            ->map(function (User $user)use ($callbackSubConsulta) {
                if($callbackSubConsulta!=null){
                    return $callbackSubConsulta($user);
                }
                $pedidos = $user->pedidosActivos()
                    ->whereDate('pedidos.created_at', '=', now()->startOfDay())
                    ->count();
                return [
                    'label' => $user->identificador,
                    'y' => $pedidos,
                ];
            })
            ->filter(function ($data) {
                return data_get($data, 'y') > 0;
            })->values();
    }
}
