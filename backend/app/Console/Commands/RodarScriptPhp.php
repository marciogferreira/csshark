<?php

namespace App\Console\Commands;

use App\Models\AlunosModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RodarScriptPhp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica Alunos Inadiplentes';

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

        $alunos = AlunosModel::whereNull('data_ultima_ativacao')->get();
        echo " Verificações de Preenchimento: ".$alunos->count();
        foreach($alunos as $aluno) {
            $aluno->data_ultima_ativacao = $aluno->crated_at->format('Y-m-d');
            $aluno->save();
        }

        $alunos = AlunosModel::whereNotNull('data_ultima_ativacao')->get();
        echo " Verificações de Validade: ".$alunos->count();
        foreach($alunos as $aluno) {
            $dataHoje = Carbon::now()->format('Y-m-d');
            $dataRegistro = Carbon::parse($aluno->data_ultima_ativacao)->addMonth(1)->format('Y-m-d');
            if($dataHoje == $dataRegistro) {
                $aluno->status = false;
            }
            $aluno->save();
        }

        
    }
}
