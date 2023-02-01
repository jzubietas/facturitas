<?php

namespace App\Jobs;

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
                's_2023_02' => ''
            ]);


        //update listado_resultados inner join count_pedidos_cliente on count_pedidos_cliente.id=listado_resultados.id set listado_resultados.a_2022_12=count_pedidos_cliente.pedidos_2022_12
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->update([
                'a_2023_02' => $cantidadPedidos
            ]);

        //update listado_resultados set s_2022_12='BASE FRIA' where s_2022_11='BASE FRIA' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2022_12', '=', 'BASE FRIA')
            ->where('a_2023_01', '=', 0)
            ->update([
                's_2023_02' => 'BASE FRIA'
            ]);

        //update listado_resultados set s_2022_12='NUEVO' where s_2022_11='BASE FRIA' and a_2022_12>0'
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2022_12', '=', 'BASE FRIA')
            ->where('a_2023_01', '>', 0)
            ->update([
                's_2023_02' => 'NUEVO'
            ]);

        //update listado_resultados set s_2022_12='ABANDONO PERMANENTE' where s_2022_11='ABANDONO RECIENTE' and a_2022_12=0
        //update listado_resultados set s_2022_12='ABANDONO PERMANENTE' where s_2022_11='ABANDONO PERMANENTE' and a_2022_12=0
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

        //update listado_resultados set s_2022_12='RECUPERADO RECIENTE' where s_2022_11='ABANDONO RECIENTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'ABANDONO RECIENTE')
            ->where('a_2023_02', '>', 0)
            ->update([
                's_2023_02' => 'RECUPERADO RECIENTE'
            ]);

        //update listado_resultados set s_2022_12='RECUPERADO ABANDONO' where s_2022_11='ABANDONO PERMANENTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'ABANDONO PERMANENTE')
            ->where('a_2023_02', '>', 0)
            ->update([
                's_2023_02' => 'RECUPERADO ABANDONO'
            ]);

        //update listado_resultados set s_2022_12='ABANDONO RECIENTE' where s_2022_11='RECURRENTE' and a_2022_12=0 and a_2022_11=0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'RECURRENTE')
            ->where('a_2023_02', '=', 0)
            ->where('a_2023_01', '=', 0)
            ->update([
                's_2023_02' => 'ABANDONO RECIENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12=0 and a_2022_11>0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12>0 and a_2022_11=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12>0 and a_2022_11>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'RECURRENTE')
            ->where('a_2023_02', '>=', 0)
            ->where('a_2023_01', '>=', 0)
            ->update([
                's_2023_02' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO' and a_2022_12=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'RECUPERADO')
            ->where('a_2023_02', '>=', 0)
            ->update([
                's_2023_02' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='NUEVO' and a_2022_12=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='NUEVO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'NUEVO')
            ->where('a_2023_02', '>=', 0)
            ->update([
                's_2023_02' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='NUEVO' where s_2022_11='NO EXISTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'NO EXISTE')
            ->where('a_2023_02', '>', 0)
            ->update([
                's_2023_02' => 'NUEVO'
            ]);

        //update listado_resultados set s_2022_12='NO EXISTE' where s_2022_11='NO EXISTE' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'NO EXISTE')
            ->where('a_2023_02', '=', 0)
            ->update([
                's_2023_02' => 'NO EXISTE'
            ]);

        //update listado_resultados set s_2022_12='ABANDONO' where s_2022_11='ABANDONO' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'ABANDONO')
            ->where('a_2023_02', '=', 0)
            ->update([
                's_2023_02' => 'ABANDONO'
            ]);
        //update listado_resultados set s_2022_12='RECUPERADO ABANDONO' where s_2022_11='ABANDONO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'ABANDONO')
            ->where('a_2023_02', '>', 0)
            ->update([
                's_2023_02' => 'RECUPERADO ABANDONO'
            ]);
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO ABANDONO' and a_2022_12=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO ABANDONO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'RECUPERADO ABANDONO')
            ->where('a_2023_02', '>=', 0)
            ->update([
                's_2023_02' => 'RECURRENTE'
            ]);


        /*
update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO RECIENTE' and a_2022_12=0
update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO RECIENTE' and a_2022_12>0
        */
        ListadoResultado::query()
            ->where('id', $this->cliente_id)
            ->where('s_2023_01', 'RECUPERADO RECIENTE')
            ->where('a_2023_02', '>=', 0)
            ->update([
                's_2023_02' => 'RECURRENTE'
            ]);

    }
}
