<?php
namespace App\Services\Relatorios;

use App\Models\Produto as Model;
use App\Models\Produto;
use App\Models\TabelaPreco;

class TabelaProdutoRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->orientation = 'landscape';
        $this->model = new Model();

        
        $tabela = TabelaPreco::find($params['tabela_id']);
        $produtos = Produto::whereIn('tipo', [Produto::TIPO_PRODUTO, Produto::TIPO_SEMI_PRODUTO])
        ->orderBy('titulo')
        ->get();
        
        foreach($produtos as $p) {
            $p->preco = $p->valor + (($p->valor * $tabela->porcentagem) / 100);    
        }
        
        $data = [
            'produtos' => $produtos,
            'tabela' => $tabela
        ];
        
        $this->name = 'tabela_precos';
        $this->view = 'relatorios.tabela_precos';
        $this->data = $data;
    }

}