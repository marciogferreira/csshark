<?php

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
Route::get('/relatorios-mobile', 'RelatoriosController@index');    

Route::post('aluno/novo', 'AlunosController@store');


Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('/relatorios', 'RelatoriosController@index');    
    
    // Options
    Route::put('alunos-status/{id}', 'AlunosController@updateStatus');
    Route::get('alunos/options', "AlunosController@options");  
    Route::get('modalidades/options', "ModalidadesController@options");  
    Route::get('turmas/options', "TurmasController@options");  
    Route::get('produtos/options', "ProdutosController@options");  
    Route::get('colaborador/options', "ColaboradorController@options");  
    Route::get('users/options', "UsersController@options");  

    Route::get('produtos/images/{produto_id}', "ProdutosController@getImages");  
    Route::post('produtos/images', "ProdutosController@createImages");  
    Route::delete('produtos/images/{id}', "ProdutosController@deleteImages");  
    
    Route::post('logout-user', 'AuthController@desconectDevice');
    
    // Crud Rest API
    Route::get('dashboard/totais', "DashboardController@totais");
    Route::get('ficha-aluno/{email}', "AlunosTreinosController@fichaByAluno");
    Route::get('ficha-aluno-id/{aluno_id}', "AlunosTreinosController@fichaByAlunoId");
    
    Route::resource('users', 'UsersController');
    Route::resource('colaborador', 'ColaboradorController');
    Route::resource('frequencias', 'FrequenciasController');
    Route::resource('alunos', 'AlunosController');
    Route::resource('modalidades', 'ModalidadesController');
    Route::resource('treinos', 'TreinosController');
    Route::resource('modelos-treinos', 'ModelosTreinosController');
    Route::resource('turmas', 'TurmasController');
    Route::resource('alunos-turmas', 'AlunosTurmasController');


    Route::resource('colaborador', 'ColaboradorController');
    // PERMISSOES
    Route::resource('permissoes', 'PermissoesController');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

