<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Suggestion;
use App\Models\SuggestionImage;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use JWTAuth;
use Mail;
use Exception;
use DB;

class SuggestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suggests = Suggestion::with('images')->with('category')->get();

        return Response()->json(['suggests' => $suggests], 200);
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
            $suggest = Suggestion::with('images')->with('category')->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        return Response()->json(['suggest' => $suggest], 200);
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
            $suggest = Suggestion::with('images')->with('category')->findOrFail($id);
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
            'name' => $suggest->name,
            'slug' => str_slug($suggest->name, '-'),
            'price' => $suggest->price,
            'description' => $suggest->description,
            'stock' => 0,
            'category_id' => $suggest->category_id,
            'user_id' => $user_id,
            'created_at' => Carbon::now(),
        ];

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            $images = [];
            if (!$suggest->images->isEmpty()) {
                foreach ($suggest->images as $value) {
                    $images[] = [
                        'image' => $value->image,
                        'product_id' => $product->id,
                        'created_at' => Carbon::now(),
                    ];
                }
            } else {
                $images[] = [
                    'image' => config('settings.path_image') . 'product/default.png',
                    'product_id' => $product->id,
                    'created_at' => Carbon::now(),
                ];
            }

            ProductImage::insert($images);

            $suggest->update([
                'status' => true,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            $mailto = $suggest->email;
            $content = $request->message;
            $subject = $request->subject;
            Mail::send([], [], function ($message) use ($mailto, $content, $subject) {
                $message->to($mailto);
                $message->subject($subject);
                $message->setBody($content, 'text/html');
            });

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.suggest.update'),
                'suggest' => $suggest,
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'success' => false,
                'error' => trans('setting.message.fails.suggest.update'),
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
            $suggest = Suggestion::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex->getMessage()], 404);
        }

        $suggest->delete();

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.suggest.delete'),
        ], 200);
    }
}
