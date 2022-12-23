<?php

namespace App\Console\Commands;

use App\Models\Pago;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class MigrateStatusPago extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pago:status';

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
        foreach (Pago::$migrateCondiciones as $status => $code) {
            Pago::query()->where('condicion', '=', $status)->update([
                "condicion" => $status,
                "condicion_code" => $code,
            ]);
        }
        return 0;
    }
}
