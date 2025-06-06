<?php

use App\Models\Client;
use App\Models\Cor;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutosImages;
use App\Models\OrdemProducao;
use App\Models\ProdutoProducao;
use App\Models\EstoqueProducao;
use App\Models\ProdutoMontagem;
use App\Models\TabelaPreco;
use App\Models\StatusProducao;
use App\Models\TreinosModel;
use App\Services\Relatorios\OrdemProducaoStatusRelatorios;
use App\Services\Relatorios\PedidosProducaoRelatorios;
use App\Services\NegociosServices;
use App\Services\OrdemProducaoNegocios;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// use NumberFormatter;

Route::get('/', function() {

    $search = 'Max';
    // $data = TreinosModel::with('aluno')->whereHas('aluno', function(Builder $query) use ($search) {
    //     $query->where('nome', 'like', "%{$search}%");
    // })->paginate(10);
    $this->orderBy = '';
    $params = [];
    $search = isset($params['search']) ? $params['search'] : '';
        $data = TreinosModel::with('aluno')
        ->whereHas('aluno', function(Builder $query) use ($search) {
            $query->where('nome', 'like', "%{$search}%");
        })
        ->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->where($this->columnSearch, 'like', "%{$params['search']}%");
            }
            return $query;
        })
        ->when($this->orderBy, function($query, $orderBy) {
            if($orderBy === 'desc') {
                return $query->orderBy('id', 'desc');
            }
        })
        ->paginate(10);
    return response()->json($data);

    return view('welcome');
});

Route::get('/relatorios', 'RelatoriosController@index');
Route::get('/relatorio', function () { 
    $data = Client::take(4)->get();
    
    foreach($data as $item) {
        $item->vendedor;
        $item->tabela;
    }
    
    // return view('relatorios.clientes', [
    //     'data' => $data,
    //     'date' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s')
    // ]);
    
    $pdf = Pdf::loadView('relatorios.clientes', [
        'data' => $data,
        'date' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s')
    ]);
    return $pdf->stream('invoice.pdf');
    // return $pdf->download('invoice.pdf');
});


