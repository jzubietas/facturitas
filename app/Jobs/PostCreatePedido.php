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
    protected $pedido;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("PostCreatePedido -> " . $this->pedido->cliente_id);

        $cantidadPedidos = Pedido::query()->activo()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('cliente_id', '=', $this->pedido->cliente_id)
            ->count();

        //update listado_resultados set s_2022_12=''
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->update([
                's_2022_12' => ''
            ]);


        //update listado_resultados inner join count_pedidos_cliente on count_pedidos_cliente.id=listado_resultados.id set listado_resultados.a_2022_12=count_pedidos_cliente.pedidos_2022_12
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->update([
                'a_2022_12' => $cantidadPedidos
            ]);

        //update listado_resultados set s_2022_12='BASE FRIA' where s_2022_11='BASE FRIA' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', '=', 'BASE FRIA')
            ->where('a_2022_12', '=', 0)
            ->update([
                's_2022_12' => 'BASE FRIA'
            ]);

        //update listado_resultados set s_2022_12='NUEVO' where s_2022_11='BASE FRIA' and a_2022_12>0'
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', '=', 'BASE FRIA')
            ->where('a_2022_12', '>=', 0)
            ->update([
                's_2022_12' => 'NUEVO'
            ]);

        //update listado_resultados set s_2022_12='ABANDONO PERMANENTE' where s_2022_11='ABANDONO RECIENTE' and a_2022_12=0
        //update listado_resultados set s_2022_12='ABANDONO PERMANENTE' where s_2022_11='ABANDONO PERMANENTE' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where(function ($query) {
                $query->where('s_2022_11', 'ABANDONO RECIENTE')
                    ->orWhere('s_2022_11', 'ABANDONO PERMANENTE');
            })
            ->where('a_2022_12', '=', 0)
            ->update([
                's_2022_12' => 'ABANDONO PERMANENTE'
            ]);

        //update listado_resultados set s_2022_12='RECUPERADO RECIENTE' where s_2022_11='ABANDONO RECIENTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'ABANDONO RECIENTE')
            ->where('a_2022_12', '>', 0)
            ->update([
                's_2022_12' => 'RECUPERADO RECIENTE'
            ]);

        //update listado_resultados set s_2022_12='RECUPERADO ABANDONO' where s_2022_11='ABANDONO PERMANENTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'ABANDONO PERMANENTE')
            ->where('a_2022_12', '>', 0)
            ->update([
                's_2022_12' => 'RECUPERADO ABANDONO'
            ]);

        //update listado_resultados set s_2022_12='ABANDONO RECIENTE' where s_2022_11='RECURRENTE' and a_2022_12=0 and a_2022_11=0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'RECURRENTE')
            ->where('a_2022_12', '=', 0)
            ->where('a_2022_11', '=', 0)
            ->update([
                's_2022_12' => 'ABANDONO RECIENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12=0 and a_2022_11>0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12>0 and a_2022_11=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECURRENTE' and a_2022_12>0 and a_2022_11>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'RECURRENTE')
            ->where('a_2022_12', '>=', 0)
            ->where('a_2022_11', '>=', 0)
            ->update([
                's_2022_12' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO' and a_2022_12=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='RECUPERADO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'RECUPERADO')
            ->where('a_2022_12', '>=', 0)
            ->update([
                's_2022_12' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='NUEVO' and a_2022_12=0
        //update listado_resultados set s_2022_12='RECURRENTE' where s_2022_11='NUEVO' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'NUEVO')
            ->where('a_2022_12', '>=', 0)
            ->update([
                's_2022_12' => 'RECURRENTE'
            ]);

        //update listado_resultados set s_2022_12='NUEVO' where s_2022_11='NO EXISTE' and a_2022_12>0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'NO EXISTE')
            ->where('a_2022_12', '>', 0)
            ->update([
                's_2022_12' => 'NUEVO'
            ]);

        //update listado_resultados set s_2022_12='NO EXISTE' where s_2022_11='NO EXISTE' and a_2022_12=0
        ListadoResultado::query()
            ->where('id', $this->pedido->cliente_id)
            ->where('s_2022_11', 'NO EXISTE')
            ->where('a_2022_12', '=', 0)
            ->update([
                's_2022_12' => 'NO EXISTE'
            ]);

    }
}
