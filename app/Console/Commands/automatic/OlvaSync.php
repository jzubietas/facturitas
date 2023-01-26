<?php

namespace App\Console\Commands\automatic;

use App\Jobs\SyncOlvaJob;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class OlvaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olva:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $grupos = DireccionGrupo::query()->inOlvaPending()/*->whereNull('courier_failed_sync_at')*/->get();
        foreach ($grupos as $grupo) {
            SyncOlvaJob::dispatch($grupo->id)->onQueue('olva');
        }

        return 0;
    }
}
