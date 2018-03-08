<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use App\Models\Rate;
use Carbon\Carbon;
use DB;
use Exception;
use JWTAuth;

class RateController extends Controller
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
            $product = Product::where('slug', '=', $request->name)->firstOrFail();
            $product_id = $product->id;
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        } catch (Exception $e) {
            return Response()->json([
                trans('setting.message.fails.me.not_found'),
            ], 404);
        }

        $data = [
            'rate' => $request->rate,
            'product_id' => $product_id,
            'user_id' => $user_id,
            'created_at' => Carbon::now(),
        ];

        DB::beginTransaction();
        try {
            Rate::create($data);

            $rate = Rate::where('product_id','=',$product_id)->avg('rate');
            $product->update([
                'rate' => $rate,
            ]);

            DB::commit();

            return Response()->json([
                'success' => true,
                'rate' => $product->rate,
            ], 200);
        } catch (Exception $e) {
            DB::rollback();

            return Response()->json([
                'success' => false,
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
        //
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
