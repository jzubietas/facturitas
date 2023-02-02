<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ResumidoNoBF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resumido:noBF';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'info:info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $informacion = DB::table('nobf')
            /*->where('s_2023_02', 'BASE FRIA')
            ->where('a_2023_02', 0)
            ->having(DB::raw('anulados+activos'), '>', 0)*/
            ->skip(0)->take(1)
            ->get();
        $row = $informacion;
        foreach ($row as $key => $value){
            $this->info(($value->fecha_ultimo_pedido_con_anulados));
        }
        return 0;
    }
}
