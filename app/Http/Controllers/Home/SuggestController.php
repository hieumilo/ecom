<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use App\Models\SuggestionImage;
use Validator;
use Carbon\Carbon;
use Exception;
use DB;
use App\Helper\Helper;

class SuggestController extends Controller
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
        $data = [
            'email' => $request->email,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_at' => Carbon::now(),
        ];

        $rules = [
            'email' => 'required|email|max:255',
            'name' => 'required|max:255',
            'price' => 'numeric|min:0',
            'category_id' => 'numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.suggest.create'),
                'error' => $validator->messages(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $suggest = Suggestion::create($data);

            if ($request->file) {
                $arr_files = [];
                foreach ($request->file as $file) {
                    $arr_files[] = [
                        'image' => Helper::upload($file, 'product'),
                        'suggestion_id' => $suggest->id,
                        'created_at' => Carbon::now(),
                    ];
                }
                SuggestionImage::insert($arr_files);
            }
            
            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.suggest.create'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'error' => trans('setting.message.fails.suggest.create'),
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
