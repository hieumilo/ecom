<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use App\Models\ProductImage;
use Validator;
use Carbon\Carbon;
use Exception;
use DB;
use App\Helper\Helper;
use JWTAuth;
use Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('images')->with('category')->get();

        return Response()->json(['products' => $products], 200);
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

        $data = [
            'name' => $request->name,
            'slug' => str_slug($request->name, '-'),
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'user_id' => $user_id,
            'created_at' => Carbon::now(),
        ];

        $rules = [
            'name' => 'required|max:255|unique:products,name',
            'price' => 'numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.product.create'),
                'error' => $validator->messages(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            if ($request->file) {
                $arr_files = [];
                foreach ($request->file as $file) {
                    $arr_files[] = [
                        'image' => Helper::upload($file, 'product'),
                        'product_id' => $product->id,
                        'created_at' => Carbon::now(),
                    ];
                }
                ProductImage::insert($arr_files);
            } else {
                ProductImage::insert([
                    'image' => config('settings.path_image') . 'product/default.png',
                    'product_id' => $product->id,
                    'created_at' => Carbon::now(),
                ]);
            }
            
            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.product.create'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'error' => trans('setting.message.fails.product.create'),
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
        try {
            $product = Product::with('images')->with('category')->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        return Response()->json(['product' => $product], 200);
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
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;
        } catch (Exception $e) {
            return Response()->json([
                trans('setting.message.fails.me.not_found'),
            ], 404);
        }

        $data = [
            'name' => $request->name,
            'slug' => str_slug($request->name, '-'),
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'user_id' => $user_id,
            'updated_at' => Carbon::now(),
        ];

        $rules = [
            'name' => 'required|max:255|unique:products,name,' . $id,
            'price' => 'numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.product.udate'),
                'error' => $validator->messages(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            $product->update($data);

            if ($request->images) {
                ProductImage::where('product_id', '=', $id)
                    ->whereNotIn('id', $request->images)
                    ->delete();
            }

            if ($request->file) {
                $arr_files = [];
                foreach ($request->file as $file) {
                    $arr_files[] = [
                        'image' => Helper::upload($file, 'product'),
                        'product_id' => $product->id,
                    ];
                }
                ProductImage::insert($arr_files);
            }
            
            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.product.update'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'error' => trans('setting.message.fails.product.update'),
                'data' => $e->getMessage(),
            ], 422);
        }
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
            $product = Product::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        $product->delete();

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.product.delete'),
        ], 200);
    }

    public function import(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get()->toArray();
        $insert = [];
        foreach ($data as $key => $value) {
            $value['created_at'] = Carbon::now();
            $insert[] = $value;
        }

        DB::beginTransaction();
        try {
            $products = Product::insert($insert);
            $ids = ProductImage::select('product_id')->get();
            $products_id = Product::select('id')->whereNotIn('id', $ids)->get()->toArray();
            $images = [];
            foreach ($products_id as $key => $value) {
                $images[] = [
                    'image' => config('settings.path_image') . 'product/default.png',
                    'product_id' => $value['id'],
                    'created_at' => Carbon::now(),
                ];
            }
            ProductImage::insert($images);
            
            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.product.create'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'error' => trans('setting.message.fails.product.create'),
                'data' => $e->getMessage(),
            ], 422);
        }
    }
}
