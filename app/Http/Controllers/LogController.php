<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\DatabaseInsertLogs;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use App\Log;
use File;


class LogController extends Controller
{
	/**
     * Displays front end view
     *
     * Lê um arquivo .log com caminho indicado pelo usuário e retorna uma tabela com os dados organizados.
     * Não armazena informações no banco de dados.
     *
     * @return \Illuminate\View\View
     */
    public function single(request $request)
    {
    	$file = $request->file('file');
        $contents = File::get($file);
        // "[]" indica o final de cada log
        $contents = explode("[]", $contents);
        foreach ($contents as $key => $value) {
            if($value == ' ') unset($contents[$key]); // os logs vazios são retirados
        }        
        foreach ($contents as $key => $value) {
            // Pego a data que está entre "[]"
            $regrex = '/\[(.*?)\]/';
            preg_match_all($regrex, $value, $resultado);
            // $resultado[0][0] = data com []
            // $resultado[1][0] = data sem []
            
            if(!empty($resultado[1][0])){
                $registro = self::criarSaida($resultado, $value);
            }
            $saida[] = $registro; // envio para p BD
        }
    	return view('single', ['contents'=> $saida, 'modo' => 'Mostrando logs de um único arquivo']);
    }

    /**
     * Displays front end view
     *
     * Lê os arquivos da pasta storage/logs/temp, armazena as informações no banco de dados.
     * Retorna com os dados organizados em uma tabela.
     * Ao final do processo os arquivos da pasta referida são apagados.
     *
     * @return \Illuminate\View\View
     */
    public function storage(){
        // DatabaseInsertLogs::dispatch()
                // ->delay(Carbon::now()->addMinutes(15));
        dispatch(new DatabaseInsertLogs());
        // dd('á');

        
        return view('storage', ["modo" => 'Mostrando logs dos arquivos da pasta temp']);
    }

    /**
     * Displays front end view
     *
     * Exibe os logs que já estão no banco de dados.
     *
     * @return \Illuminate\View\View
     */
    public function data(){
        return view('storage', ["modo" => 'Exibindo os logs armazenados na base de dados']);
    }

    /**
     * 
     * Armazena um registro de log no banco de dados.
     */
    public static function insert($registro){
        $id = DB::table('logs')->insertGetId(
            [
                'level' => $registro['level'], 'message' => $registro['message'], 'request' => $registro['request'], 'user_id' => $registro['user_id'], 'user_email' => $registro['user_email'], 'accessed' => $registro['accessed']
            ]
        );
    }

    /**
     * 
     * Exclui os arquivos e pastas dentro de storage/logs/temp.
     */
    public static function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) { 
            if(is_dir("$dir/$file")){
                self::delTree("$dir/$file");
            }else{
                unlink("$dir/$file");
            } 
        }
        if (storage_path("logs/temp/") != $dir) {
            rmdir($dir);
        }
    }

    /**
     * 
     * Passa a data para o formato brasileiro.
     * @return 
     */
    public static function dateToBr($data){
        $data = explode(' ', $data);
        $dataBr = explode('-', $data[0]);

        return $dataBr[2].'/'.$dataBr[1].'/'.$dataBr[0].' '.$data[1];
    }

    /**
     * 
     * Passa um registro de log para o formato correto para exibição/armazenamento.
     *
     * @return $registro;
     */
    public static function criarSaida($resultado, $value){
        $registro['accessed'] = $resultado[1][0];
        if( strstr($resultado[1][0],":"))
            $registro['accessed'] = self::dateToBr($resultado[1][0]);

        $value = str_replace($resultado[0][0], "", $value);
        $registro['channel'] = explode('.', $value)[0];
        $value = str_replace(explode('.', $value)[0].'.', "", $value);
        $registro['level'] = str_replace(':', '', explode(' ', $value)[0]);
        $value = str_replace($registro['level'].':', "", $value);

        // VERIFICAR ESTA PARTE DO CODIGO
        if($registro['level']=='ERROR'){
            $registro['message'] = $value;
            $registro['user_id'] = '';
            $registro['user_email'] = '';
            $registro['request'] = '';
        }else{
            $registro['message'] = explode(' ', $value)[1];
            $value = str_replace($registro['message'], "", $value);
            $value = json_decode($value, true);
            $registro['user_id'] = $value['user_id'];
            $registro['user_email'] = $value['user_email'];
            $registro['request'] = '';
            if(isset($value['request']))
                $registro['request'] = $value['request'];
        }
        return $registro;
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request){
        $logs = Log::select(['level','accessed','user_id','user_email','message', 'request']);
        if ($request->accessed) {
            $logs->where('accessed', $request->accessed);
        }
        if($request->tipo){
            $logs->where('level', $request->tipo);
        }

        return Datatables::of($logs)->make(true);
    }

}