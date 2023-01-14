<?php

namespace App\Console\Commands;

use App\Models\Distrito;
use Illuminate\Console\Command;

class NormalizarDistritos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:distritos';

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
        Distrito::query()
            ->whereIn('distrito', [
                'ANCON',
               // 'CALLAO - MI PERU',
                'CARABAYLLO',
                'COMAS',
                'COMAS - COLLIQUE',
                'INDEPENDENCIA',
               // 'SANTA ROSA',
                'VENTANILLA',
                'SAN MARTIN DE PORRES',
            ])
            ->update([
                'zona' => 'NORTE'
            ]);

        Distrito::query()
            ->whereIn('distrito', [
                'SAN ISIDRO - CHACARILLA',
            ])
            ->delete();

        Distrito::query()->create([
            'distrito' => 'LURIN',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);

        Distrito::query()->create([
            'distrito' => 'PACHACAMAC',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);
        Distrito::query()->create([
            'distrito' => 'MANCHAY',
            'zona' => 'SUR',
            'provincia' => 'LIMA',
        ]);

        return 0;
    }
}
