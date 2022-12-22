<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

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
        $database = \DB::connection()->getDatabaseName();
        $default = config('database.default');
        $p = config('database.connections.' . $default . '.password');
        $u = config('database.connections.' . $default . '.username');

        if (!file_exists(storage_path("backups"))) {
            mkdir(storage_path("backups"));
        }
        $filename = storage_path("backups/" . now()->format('Y_m_d_H_i_s') . '_' . time() . '.sql');
        $credentialsFile = __DIR__ . '/mysql-credentials.cnf';
        $data = "
[client]
user=$u
password=$p
host=127.0.0.1
";
        file_put_contents($credentialsFile, $data);
        try {
            $this->executeCommand("mysqldump --defaults-extra-file=\"$credentialsFile\" --routines $database > $filename");
            unlink($credentialsFile);
        } catch (\Exception $ex) {
            unlink($credentialsFile);
            throw $ex;
        }
        return 0;
    }

    public function executeCommand($cmd)
    {
        $process = Process::fromShellCommandline($cmd);

        $captureOutput = function ($type, $line) {
            $this->info($type . ' --- ' . $line);
        };

        $process->setTimeout(null)->run($captureOutput);

        if ($process->getExitCode()) {
            $exception = new ProcessFailedException($process);
            report($exception);
            throw $exception;
        }
    }
}
