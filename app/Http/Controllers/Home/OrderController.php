<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use DB;
use Exception;
use JWTAuth;
use Mail;
use Carbon\Carbon;
use App\Mail\OrderShipped;
use App\Jobs\SendOrderEmail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;
        } catch (Exception $e) {
            return Response()->json([
                trans('setting.message.fails.me.not_found'),
            ], 404);
        }

        $flag = false;
        $arr = [];
        foreach ($request->order_items as $key => $value) {
            $product = Product::findOrFail($value['id']);
            if($product->stock < $value['quantity']) {
                $flag = true;
                $arr[] = [
                    'name' => $product->name,
                    'stock' => $product->stock,
                ];
            }
        }
        if ($flag) {
            return Response()->json([
                'success' => false,
                'error' => trans('setting.message.fails.order.stock'),
                'data' => $arr,
            ], 422);
        }

        $data = [
            "address" => $request->address,
            "phone" => $request->phone,
            "user_id" => $user->id,
            "price" => $request->price,
        ];

        DB::beginTransaction();
        try {
            $order = Order::create($data);

            $orderItems = [];
            foreach ($request->order_items as $key => $value) {
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $value['id'],
                    'quantity' => $value['quantity'],
                    'created_at' => Carbon::now(),
                ];
                Product::findOrFail($value['id'])
                    ->decrement('stock', $value['quantity']);
            }

            OrderItem::insert($orderItems);

            // $mailTo = $user->email;
            $mailTo = 'hieumilo194@gmail.com';
            $content = [
                'order_items' => $request->order_items,
                'name' => $user->name,
                'email' => $user->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'price' => $request->price,
            ];

            $sendMail = (new SendOrderEmail($mailTo, $content))
                ->onConnection('database')
                ->onQueue('emails');
            dispatch($sendMail);

            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.order.create'),
            ], 200);
        } catch (Exception $e) {
            DB::rollback();

            return Response()->json([
                'success' => false,
                'error' => trans('setting.message.fails.order.create'),
                'data' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderItems = OrderItem::where('order_id', '=', $id)->with('product')->get();

        return Response()->json([
            'success' => true,
            'orderItems' => $orderItems,
        ], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
