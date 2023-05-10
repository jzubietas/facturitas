<?php

namespace App\Jobs;

use App\Models\Cliente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostCreatePedidoClienteUltimoPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $cliente_id;
    public function __construct($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cliente::updateUltimoPedidoCliente($this->cliente_id);
    }
}
