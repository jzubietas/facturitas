<?php

namespace App\Console\Commands;

use App\Models\Ruc;
use Illuminate\Console\Command;

class RucRemoveXXX extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruc:renames';

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
        $rucs = Ruc::query()->where('empresa', 'like', 'XXX%');

        $bar = $this->output->createProgressBar($rucs->count());
        $rucs = $rucs->get();
        foreach ($rucs as $ruc) {
            $ruc->update([
                'empresa' => \Str::replace("xxx", "", \Str::replace("XXX", "", $ruc->empresa))
            ]);
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}
