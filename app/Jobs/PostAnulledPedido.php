<?php

namespace App\Jobs;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostAnulledPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        \Log::info("PostAnulledPedido -> " . $this->cliente_id);

        $cantidadPedidos = Pedido::query()->activo()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('cliente_id', '=', $this->cliente_id)
            ->count();
    }
}
