<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Suggestion;
use App\Models\Product;
use App\Models\Order;
use DB;
use Carbon\Carbon;
use Excel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $new_suggest = Suggestion::where('status', '=', false)->count();
        $user = User::count();
        $product = Product::count();
        $new_order = Order::where('status', '=', false)->count();

        $sellingProducts = Product::select(DB::raw('products.name, count(products.name) as count'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereMonth('order_items.created_at', Carbon::now()->month)
            ->groupBy('products.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return Response()->json([
            'success' => true,
            'new_suggest' => $new_suggest,
            'user' => $user,
            'product' => $product,
            'new_order' => $new_order,
            'sellingProducts' => $sellingProducts,
        ]);
    }

    public function export()
    {
        $sellingProducts = Product::select(DB::raw('products.name, count(products.name) as count'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->whereMonth('order_items.created_at', Carbon::now()->month)
            ->groupBy('products.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->toArray();
        
        $paymentsArray = [];
        $paymentsArray[] = ['name', 'count'];

        Excel::create('export', function($excel) use ($sellingProducts) {
            $excel->sheet('sheet 1', function($sheet) use($sellingProducts) {
                $sheet->fromArray($sellingProducts);
            });
        })->download('xls');
    }
}
