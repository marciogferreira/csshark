<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Adverts;
use App\Models\AlunosModel;
use App\Models\Frequencias;
use App\Models\ImportsAdverts;
use App\Models\ModalidadesModel;
use App\Models\Pedido;
use App\Models\Products;
use App\Models\TurmasModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {

    public function __construct() {
    }

    /**
    * @Route("/dashboard/totais")
    */
    public function totais() {

        try {

            $params = request()->input();
            
            $hoje = Carbon::now()->format('Y-m-d');

            return response()->json([
                'data' => [
                   'total_alunos' => AlunosModel::all()->count(),
                   'total_alunos_ativos' => AlunosModel::where('status', true)->get()->count(),
                   'total_alunos_inativos' => AlunosModel::where('status', false)->get()->count(),
                   'total_turmas' => TurmasModel::get()->count(),
                   'total_modalidades' => ModalidadesModel::get()->count(),
                   'total_presencas_hoje' => Frequencias::where('data', $hoje)->get()->count(),
                ]
            ]);
            
        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);

        }
    }
}