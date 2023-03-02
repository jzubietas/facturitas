<?php

namespace App\Jobs;

use App\Models\Cliente;
use Illuminate\Foundation\Bus\Dispatchable;


class PostUpdateSituacion
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
  protected $cliente_id;
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
        Cliente::createSituacionByCliente($this->cliente_id);
        //Cliente::createSituacionNulosByCliente($this->cliente_id);
    }
}
