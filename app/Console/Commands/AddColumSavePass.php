<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class AddColumSavePass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:add-save-pass';

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
        \Schema::table('users', function (Blueprint $table) {
            $table->string("password_save",255)->nullable()->after('password');
        });
      
        return 0;
    }
}
