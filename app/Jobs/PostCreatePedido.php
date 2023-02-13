<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\ListadoResultado;
use App\Models\Pedido;
use Illuminate\Foundation\Bus\Dispatchable;

class PostCreatePedido
{
    use Dispatchable;

    /**
     * @var Pedido
     */
    protected $cliente_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id)
    {
        $this->cliente_id = $client_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("PostCreatePedido -> " . $this->cliente_id);

        $cantidadPedidos = Pedido::query()->activo()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('cliente_id', '=', $this->cliente_id)
            ->count();



        //update listado_resultados set s_2022_12=''
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->update([
                's_2023_02' => '',
                'a_2023_02' => $cantidadPedidos
            ]);

        if($cantidadPedidos==1)
        {
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->update([
                    'a_2023_02' => $cantidadPedidos
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2022_12', '=', 'BASE FRIA')
                ->where('a_2023_01', '=', 0)
                ->update([
                    's_2023_02' => 'NUEVO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2022_12', '=', 'BASE FRIA')
                ->where('a_2023_01', '>', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where(function ($query) {
                    $query->where('s_2023_01', 'ABANDONO RECIENTE')
                        ->orWhere('s_2023_01', 'ABANDONO PERMANENTE');
                })
                ->where('a_2023_02', '=', 0)
                ->update([
                    's_2023_02' => 'ABANDONO PERMANENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'ABANDONO RECIENTE')
                ->where('a_2023_02', '>', 0)
                ->update([
                    's_2023_02' => 'RECUPERADO ABANDONO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'ABANDONO PERMANENTE')
                ->where('a_2023_02', '>', 0)
                ->update([
                    's_2023_02' => 'RECUPERADO ABANDONO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'RECURRENTE')
                ->where('a_2023_02', '=', 0)
                ->where('a_2023_01', '=', 0)
                ->update([
                    's_2023_02' => 'ABANDONO RECIENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'RECURRENTE')
                ->where('a_2023_02', '>=', 0)
                ->where('a_2023_01', '>=', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where(function ($query) {
                    $query->where('s_2023_01', 'RECUPERADO RECIENTE')
                        ->orWhere('s_2023_01', 'RECUPERADO ABANDONO');
                })
                ->where('a_2023_02', '>=', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'NUEVO')
                ->where('a_2023_02', '>=', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'NO EXISTE')
                ->where('a_2023_02', '>', 0)
                ->update([
                    's_2023_02' => 'NUEVO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'NO EXISTE')
                ->where('a_2023_02', '=', 0)
                ->update([
                    's_2023_02' => 'NO EXISTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'ABANDONO')
                ->where('a_2023_02', '=', 0)
                ->update([
                    's_2023_02' => 'ABANDONO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'ABANDONO')
                ->where('a_2023_02', '>', 0)
                ->update([
                    's_2023_02' => 'RECUPERADO ABANDONO'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'RECUPERADO ABANDONO')
                ->where('a_2023_02', '>=', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            ListadoResultado::query()
                ->where('id', $this->cliente_id)
                ->where('s_2023_01', 'RECUPERADO RECIENTE')
                ->where('a_2023_02', '>=', 0)
                ->update([
                    's_2023_02' => 'RECURRENTE'
                ]);
            $situacion=ListadoResultado::where('id', $this->cliente_id)->first()->s_2023_02;

            Cliente::query()
                ->where('id', $this->cliente_id)
                ->update([
                    'situacion' => $situacion
                ]);
        }


    }
}
