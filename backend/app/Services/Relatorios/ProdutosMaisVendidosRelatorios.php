<?php
namespace App\Services\Relatorios;

use App\Models\Pedido as Model;
use App\Models\Produto;
use App\Models\ProdutosImages;
use App\Services\NegociosServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProdutosMaisVendidosRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();
       
        $where = ' 1 = 1';   
        
        if(isset($params['search']) && !empty($params['search'])) {
            $where .= " AND (";
            $where .= "p.codigo like '%{$params['search']}%'";
            $where .= " OR c.razao_social like '%{$params['search']}%'";
            $where .= " OR c.nome_fantasia like '%{$params['search']}%'";
            $where .= " OR c.cnpj like '%{$params['search']}%'";
            $where .= " OR c.cpf like '%{$params['search']}%'";
            $where .= " OR c.bairro like '%{$params['search']}%'";
            $where .= " OR c.cidade like '%{$params['search']}%'";
            $where .= " OR c.uf like '%{$params['search']}%'";
            $where .= " OR c.cep like '%{$params['search']}%'";
            $where .= " OR v.name like '%{$params['search']}%'";
            $where .= ")";
        }
    
        if(isset($params['cnpj']) && !empty($params['cnpj'])) {
            $where .= " AND (REPLACE(REPLACE(REPLACE(c.cpf,'.', ''),'-', ''),'/', '') like '%".$params['cnpj']."%'";
            $where .= " OR REPLACE(REPLACE(REPLACE(c.cnpj,'.', ''),'-', ''),'/', '') like '%".$params['cnpj']."%'";
            $where .= ")";
        }
    
        if(isset($params['codigo']) && !empty($params['codigo'])) {
            $where .= " AND p.codigo like '%".$params['codigo']."%'";
        }
    
        if(isset($params['estado']) && !empty($params['estado'])) {
            $where .= " AND c.uf like '%".$params['estado']."%'";
        }
    
        if(isset($params['vendedor']) && !empty($params['vendedor'])) {
            $where .= " AND v.name like '%".$params['vendedor']."%'";
        }
        
        if(isset($params['bairro']) && !empty($params['bairro'])) {
            $where .= " AND c.bairro like '%".$params['bairro']."%'";
        }
    
        if(isset($params['cidade']) && !empty($params['cidade'])) {
            $where .= " AND c.cidade like '%".$params['cidade']."%'";
        }
    
        if(isset($params['status']) && !empty($params['status'])){
            $where .= " AND p.status like '%".$params['status']."%'";
        }

        if(isset($params['pedidos']) && !empty($params['pedidos']) && $params['all_pedidos'] !== 'true'){
            $where .= " AND ip.produto_id in (".implode(',', $params['pedidos']).")";
        }
    
        if(isset($params['periodo']) && !empty($params['periodo'])) {
            if($params['periodo'] == 'today') {
                $dateCarbon = Carbon::now()->format('Y-m-d');
                $where .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') = '{$dateCarbon}'";
            }
            if($params['periodo'] == 'yesterday') {
                $dateCarbon = Carbon::now()->subDay()->format('Y-m-d');
                $where .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') = '{$dateCarbon}'";
            }
            if($params['periodo'] == 'month') {
                $dateCarbon = Carbon::now()->format('Y-m');
                $where .= " AND DATE_FORMAT(p.created_at, '%Y-%m') = '{$dateCarbon}'";
            }
        }
    
        if(isset($params['data_inicio']) && !empty($params['data_inicio'])) {
            if(isset($params['data_inicio']) && !isset($params['data_fim'])) {
                $where .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') >= '{$params['data_inicio']}'";
            }
            if(isset($params['data_inicio']) && isset($params['data_fim'])) {
                $where .= " AND DATE_FORMAT(p.created_at, '%Y-%m-%d') between '{$params['data_inicio']}' AND '{$params['data_fim']}'";
            }
        }
    
        if(isset($params['vendedor_id']) && !empty($params['vendedor_id'])) {
            $where .= "AND p.vendedor_id like %{$params['vendedor_id']}%";
        }
    
        if(isset($params['ordem']) && $params['ordem'] == 'true') {
            $params['ordem'] = 'desc';
        } else {
            $params['ordem'] = 'asc';
        }

        $data = DB::table(DB::Raw("
            (
                SELECT sum(ip.quantidade) as quantidade, ip.produto_id 
                FROM pedidos as p
                INNER JOIN itens_pedidos as ip ON ip.pedido_id = p.id
                INNER JOIN clients as c ON c.id = p.cliente_id
                INNER JOIN colaboradors as v ON v.id = c.vendedor_id
                WHERE ".$where. "
                GROUP BY ip.produto_id
            ) as t
        "))
        ->select('t.*')
        ->orderBy(DB::raw("quantidade"), $params['ordem'])
        ->get();

        foreach($data as $item) {
            $item->produto = Produto::find($item->produto_id);
            $list = ProdutosImages::where('produto_id', $item->produto_id)->get();
            foreach($list as $image) {
                $item->produto->image = url('/images').'/'.$image->path;
            }
        }
        
       
        $this->name = 'maisvendidos';
        $this->view = 'relatorios.maisvendidos';
        $this->data = $data;
    }

}