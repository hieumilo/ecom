<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Category;
use Validator;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('parent_id', '=', 0)->get();

        return Response()->json([
            'success' => true,
            'categories' => $this->list_categories($categories),
        ], 200);
    }

    function list_categories($categories)
    {
        $data = [];

        foreach($categories as $category)
        {
            $data[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent_id' => $category->parent_id,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'categories' => $this->list_categories($category->categories),
            ];
        }

        return $data;
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
            'name' => $request->name,
            'slug' => str_slug($request->name, '-'),
            'parent_id' => $request->parent_id ? $request->parent_id : 0,
            'created_at' => Carbon::now(),
        ];

        $rules = [
            'name' => 'required|max:255|unique:categories,name',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.category.create'),
                'error' => $validator->messages(),
            ], 422);
        }

        Category::create($data);

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.category.create'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        try {
            $category = Category::where('slug', '=', $name)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        return Response()->json([
            'success' => true,
            'category' => $category,
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
    public function update(Request $request, $name)
    {
        try {
            $category = Category::where('slug', '=', $name)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        if ($category->id == $request->parent_id) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.category.parent'),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'slug' => str_slug($request->name, '-'),
            'parent_id' => $request->parent_id,
            'updated_at' => Carbon::now(),
        ];

        $rules = [
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'numeric',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.category.update'),
                'error' => $validator->messages(),
            ], 422);
        }

        $category->update($data);

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.category.update'),
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
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }
        
        if ($category->categories->isNotEmpty()) {
            return Response()->json([
                'suggess' => false,
                'message' => trans('setting.message.fails.category.delete'),
            ], 422);
        }

        $category->delete();

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.category.delete'),
        ], 200);
    }
}
