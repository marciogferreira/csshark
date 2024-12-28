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
use App\Services\Relatorios\OrdemProducaoStatusRelatorios;
use App\Services\Relatorios\PedidosProducaoRelatorios;
use App\Services\NegociosServices;
use App\Services\OrdemProducaoNegocios;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// use NumberFormatter;

Route::get('/', function() {
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


