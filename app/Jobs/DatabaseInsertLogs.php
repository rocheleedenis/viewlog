<?php

namespace App\Jobs;

use App\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use File;
use App\Http\Controllers\LogController;

class DatabaseInsertLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $log;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * 
     * @return void
     */
    public function handle()
    {
        $diretorio = dir(storage_path('logs/temp'));
        
        $diretorios = $saida = array();
        while($nomeDiretorio = $diretorio -> read()){
            if(!strstr($nomeDiretorio, '.')) $diretorios[] = $nomeDiretorio;
        }
        $diretorio -> close();
        if(count($diretorios)){
            foreach ($diretorios as $key => $nomeDiretorio) {
                $diretorio = dir(storage_path("logs/temp/$nomeDiretorio"));

                while($nomeArquivo = $diretorio -> read()){
                    if(strstr($nomeArquivo, '.log')){
                        // criar função pra não repetir esta
                        
                        $contents = File::get(storage_path("logs/temp/$nomeDiretorio/$nomeArquivo"));

                        // "[]" separa os logs
                        $contents = explode("[]", $contents);
                        foreach ($contents as $key => $value) {
                            if($value == ' ') unset($contents[$key]);
                        }  

                        foreach ($contents as $key => $value) {
                            // Pego a data que está entre "[]"
                            $regrex = '/\[(.*?)\]/';
                            preg_match_all($regrex, $value, $resultado);
                            // $resultado[0][0] = data com []
                            // $resultado[1][0] = data sem []
                            
                            if(!empty($resultado[1][0])){
                                $registro = LogController::criarSaida($resultado, $value);
                            }
                            LogController::insert($registro);
                        }
                    }
                }
                $diretorio -> close();
            }
        }
        LogController::delTree(storage_path("logs/temp/"));
    }
}
