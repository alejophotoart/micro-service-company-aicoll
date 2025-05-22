<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class LogsDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:logs-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar todo el contenido del archivo laravel.log para que este no llene espacio inutil en el servidor';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        Storage::disk('local')->delete(storage_path('logs/*.log'));

        $this->info('Todos los archivos de log han sido eliminados.');
        
        return Command::SUCCESS;
    }
}
