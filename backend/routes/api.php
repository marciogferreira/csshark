<?php

use App\Models\OrdemProducao;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoMontagem;
use App\Models\ProdutoProducao;
use App\Models\StatusProducao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['message' => 'Sessão Expirada.']);
})->name('login');


Route::post('login', 'AuthController@login');
Route::post('me', 'AuthController@me');
Route::post('login-app', 'AuthController@loginApp');

Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');

Route::post('notificacoes', 'NotificationsController@receiver');
Route::post('notifications', 'NotificationsController@receiver');

Route::get('operation-migration-imports', 'MigrationController@imports');

Route::get('/relatorios-mobile', 'RelatoriosController@index');    
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('/relatorios', 'RelatoriosController@index');    
    
    // Options

    Route::get('alunos/options', "AlunosController@options");  
    Route::get('modalidades/options', "ModalidadesController@options");  
    Route::get('turmas/options', "TurmasController@options");  

    Route::get('produtos/options', "ProdutosController@options");  
    Route::get('clientes/options', "ClientsController@options");  
    Route::get('colaborador/options', "ColaboradorController@options");  
    Route::get('fornecedores/options', "FornecedoresController@options");  
    Route::get('cargos/options', "CargosController@options");  
    Route::get('forma-pagamento/options', "FormaPagamentoController@options");  
    Route::get('itens-tipos-pagamentos/options', "ItensTiposPagamentosController@options");  
    Route::get('tabela-precos/options', "TabelaPrecosController@options");  
    Route::get('users/options', "UsersController@options");  
    Route::get('status-producao/options', 'StatusProducaoController@options');
    Route::get('cores/options', 'CoresController@options');
    Route::get('produto-producao-por-produto/{produto_id}', 'ProdutoProducaoController@getList');

    Route::get('produtos-montagem/list-produtos/{produto_id}', "ProdutosMontagemController@listProdutos");  
    Route::get('dependencias/list-produtos/{produto_id}', "DependenciasController@listProdutos");  
    Route::get('produtos/list-produtos-by-porcentagem/{id}', "ProdutosController@listProdutosByPorcentagem");  
    Route::get('itens-pedidos/agrupar', 'ItensPedidosController@agrupar');
    Route::get('ultimas-vendas', "UltimasVendasController@index");  
    Route::get('maisvendidos', "MaisVendidosController@index");  
    Route::get('status-producao-por-ordem/{ordem_id}', "StatusProducaoController@listaPorOrdem");  
    
    // CONSULTA STATUS_PRODUCAO - ORDEM_PEDIDOS
    Route::get('consult-ordens-with-status/{status_producao_id}/{ordem_producao_id}', 'PedidosController@consultOrdensWithStatus');

    Route::get('produtos/images/{produto_id}', "ProdutosController@getImages");  
    Route::post('produtos/images', "ProdutosController@createImages");  
    Route::delete('produtos/images/{id}', "ProdutosController@deleteImages");  
    
    Route::post('ordem-producao/adicionar-pedido-op', "OrdemProducaoController@adicionarPedidoOp");  
    Route::delete('ordem-producao/remove-pedido/{pedido_id}', "OrdemProducaoController@removePedido");  
    
    Route::post('add-produtos', 'ProdutosMontagemController@addProduto');
    Route::post('visita-check', 'VisitaController@check');
    Route::post('estoque-producao/remanejar-estoque-produto', 'EstoqueProducaoController@remanejarProduto');
    
    Route::post('logout-user', 'AuthController@desconectDevice');
    
    // Crud Rest API
    Route::get('dashboard/totais', "DashboardController@totais");
    Route::get('ficha-aluno/{email}', "AlunosTreinosController@fichaByAluno");
    

    Route::resource('users', 'UsersController');
    Route::resource('colaborador', 'ColaboradorController');
    
    Route::resource('alunos', 'AlunosController');
    Route::resource('modalidades', 'ModalidadesController');
    Route::resource('treinos', 'TreinosController');
    Route::resource('turmas', 'TurmasController');
    Route::resource('alunos-turmas', 'AlunosTurmasController');
    Route::resource('alunos-treinos', 'AlunosTreinosController');   


    Route::resource('clients', 'ClientsController');
    Route::resource('visitas', 'VisitaController');
    Route::resource('colaborador', 'ColaboradorController');
    Route::resource('cargos', 'CargosController');
    Route::resource('fornecedores', 'FornecedoresController');
    Route::resource('tipo-despesa', 'TipoDespesaController');
    Route::resource('forma-pagamento', 'FormaPagamentoController');
    Route::resource('produtos', 'ProdutosController');
    Route::resource('tabela-precos', 'TabelaPrecosController');
    Route::resource('produtos-montagem', 'ProdutosMontagemController');
    Route::resource('dependencias', 'DependenciasController');
    Route::resource('pedidos', 'PedidosController');
    Route::resource('historicos', 'HistoricosController');
    Route::resource('visita', 'VisitaController');
    Route::resource('itens-pedidos', 'ItensPedidosController');
    Route::resource('itens-tipos-pagamentos', 'ItensTiposPagamentosController');
    Route::resource('producao', 'ProducaoController');
    Route::resource('historico-producao', 'HistoricoProducaoController');
    Route::resource('status-producao', 'StatusProducaoController');
    Route::resource('comissao-producao', 'ComissaoProducaoController');
    Route::resource('lancar-produzidos', 'LancarProduzidosController');
    Route::resource('ordem-producao', 'OrdemProducaoController');
    Route::resource('estoque-producao', 'EstoqueProducaoController');
    Route::resource('cores', 'CoresController');
    Route::resource('produto-producao', 'ProdutoProducaoController');

    // PERMISSOES
    Route::resource('permissoes', 'PermissoesController');

    // APP - VENDEDOR - ROUTES
    Route::resource('clients-vendedor', 'ClientsVendedorController');
    Route::resource('orders-vendedor', 'ClientsVendedorController');
    Route::resource('visita_vendedores', 'VisitaVendedoresController');
    Route::resource('pedidos_vendedores', 'PedidosVendedoresController');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

