<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use JWTAuth;
use Mail;
use Exception;
use DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('user')->get();

        return Response()->json(['orders' => $orders], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order = Order::with('user')->with(['items' => function($query) {
                $query->select([
                    'order_items.id', 
                    'order_items.quantity', 
                    'order_items.order_id', 
                    'products.name', 
                    'products.price',
                ]);
                $query->join('products', 'products.id', '=', 'order_items.product_id');
            }])->findOrFail($id);

            return Response()->json([
                'order' => $order,
            ], 200);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        $order->update([
            'status' => true,
        ]);

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.order.update'),
            'order' => $order,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            foreach ($order->items as $key => $value) {
                Product::findOrFail($value['product_id'])
                    ->increment('stock', $value['quantity']);
            }
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        $order->delete();

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.order.delete'),
        ], 200);
    }
}
