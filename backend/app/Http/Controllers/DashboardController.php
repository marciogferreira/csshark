<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Adverts;
use App\Models\ImportsAdverts;
use App\Models\Pedido;
use App\Models\Products;
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
            $params['isFilterDate'] = false;
            if(isset($params['data_ini']) && !empty($params['data_ini']) && isset($params['data_fim']) && !empty($params['data_fim'])) {
                $dataIni = Carbon::parse($params['data_ini']);
                $dataFim = Carbon::parse($params['data_fim']);
                $params['isFilterDate'] = true;
                if($dataIni->getTimestamp() > $dataFim->getTimestamp()) {
                    throw new Exception("Data de Início não pode ser maior do que a data fim.");
                }
            }

            $where = '';
            if($params['isFilterDate']) {
                // $where = " AND date_format(date_created, '%Y-%m-%d') between {$params['data_ini']} and {$params['data_fim']}";
                $where = " AND date_created between '{$params['data_ini']}' and '{$params['data_fim']}'";
            }

            // $date = Carbon::now()->format('Ymd');
            // $orderByDay = DB::select("SELECT sum(total) as total FROM orders WHERE date_format(date_created, '%Y%m%d') = '{$date}'");

            // $date = Carbon::now()->format('Ym');
            // $ordersByMonth = DB::select("SELECT sum(total) as total FROM orders WHERE date_format(date_created, '%Y%m') = '{$date}'");

            // $orders = DB::select("SELECT sum(total) as total FROM orders WHERE 1 = 1 {$where}");
            // $products = DB::select("SELECT sum(available_quantity) as quant, count(id) as total FROM products");

            // $adverts = DB::select("SELECT count(id) as total FROM adverts");
            // $ordersItems = DB::select("SELECT sum(quantity) as total FROM orders_items");

            // $advertsMoreSales = DB::select("SELECT 
            //         t.total, t.advert_id, a.title, a.permalink, a.import_id FROM (
            //         SELECT count(id) as total, advert_id
            //         FROM orders_items 
            //         GROUP BY advert_id
            //     ) as t 
            //     INNER JOIN adverts as a ON a.id = t.advert_id
            //     ORDER BY t.total DESC
            //     LIMIT 10");


            
            // $produtsMoreSales = [];
            // foreach($advertsMoreSales as $key => $item) {
            //     if(!empty($item->import_id)) {
                    
            //         $total = 0;
                    
            //         if(isset($produtsMoreSales[$product->id]['total'])) {
            //             $total = $produtsMoreSales[$product->id]['total'] + $item->total;
            //         } else {
            //             $total = $item->total;
            //         }
            //         $produtsMoreSales[$product->id] = [
            //             'title' => $product->title,
            //             'permalink' => $item->permalink,
            //             'total' => $total,
            //         ];
            //     }
            // }
            $resultProducts = [];
            // foreach($produtsMoreSales as $item) {
            //     $resultProducts[] = $item;
            // }

            if(isset($params['vendedor_id']) && !empty($parmas['vendedor_id'])) {
                $list = Pedido::where('vendedor_id', $params['vendedor_id'])
                ->when($params, function($query, $params) {
                    if($params['isFilterDate']) {
                        return $query->whereBetween('created_at', [$params['data_ini'], $params['data_fim']]);
                    }
                    return $query;
                })
                ->get();
            } else {
                $list = Pedido::
                when($params, function($query, $params) {
                    if($params['isFilterDate']) {
                        return $query->whereBetween('created_at', [$params['data_ini'], $params['data_fim']]);
                    }
                    return $query;
                })
                ->get();
            }

            foreach($list as $item) {
                $total = 0;
                foreach($item->itens as $produto) {
                    $produto->produto;
                    $total = $total + ($produto->quantidade * $produto->preco);
                }
                $item->total = $total - ($total * $item->desconto / 100);
            }
            
            $totalGeral = 0;
            foreach($list as $item) {
                $totalGeral += $item->total;
            }

            // Mensal
            

            return response()->json([
                'data' => [
                    'total_geral' => number_format($totalGeral),
                    'total_mensal' => number_format($totalGeral),
                    'total_hoje' => number_format($totalGeral),
                    // 'last_sales_all' => number_format($orders[0]->total, 2, '.', ''),
    
                    // 'total_sales_products' => $ordersItems[0]->total,
                    // 'last_sales' => number_format($ordersByMonth[0]->total, 2, '.', ''),
                    // 'quant_products' => $products[0]->quant,
                    
                    // 'products_more_sales' => $resultProducts,
                    // 'adverts_more_sales' => $advertsMoreSales,
                    // 'adverts_more_sales_ml' => [],
                    
                    // 'total_adverts' => $adverts[0]->total,
                    // 'total_products' => $products[0]->total,
                    'total_clients' => 0,
                    // "teste" => "SELECT sum(total) as total FROM orders WHERE 1 = 1 {$where}",
                    //'total_clients' => $clients[0]->clients,
                ]
            ]);
            
            // return response()->json([
            //     'data' => [
            //         'last_sales_by_day' => 0,
            //         'last_sales_by_month' => 0,
            //         'last_sales_all' => 0,

            //         'total_sales_products' => 0,
            //         'last_sales' => 0,
            //         'quant_products' => 0,
                    
            //         'products_more_sales' => [],
            //         'total_adverts' => 0,
            //         'total_products' => 0,
            //         'total_clients' => 0,
            //     ]
            // ]);
        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);

        }
    }
}