<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    public function getNewProducts()
    {
        $products = Product::with('images')
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->take(config('settings.home_product'))
            ->get();

        return Response()->json(['products' => $products], 200);
    }

    public function getCategories()
    {
        $categories = Category::where('parent_id', '=', 0)
            ->get();

        return Response()->json([
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
                'categories' => $this->list_categories($category->categories),
            ];
        }

        return $data;
    }

    public function getProductsByCategory(Request $request, $slug)
    {
        try {
            $category = Category::where('slug', '=', $slug)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        $category_id = $category->id;
        $products = Product::with('images')
            ->with('category')
            ->where('category_id', '=', $category_id);
        if ($request->name) {
            $products = $products->orderBy('name', $request->name);
        }
        if ($request->price) {
            $products = $products->orderBy('price', $request->price);
        }
        if ($request->rate) {
            $products = $products->orderBy('rate', $request->rate);
        }
        $products = $products->paginate(config('settings.paginate'));

        return Response()->json(['products' => $products], 200);
    }

    public function getProduct($name)
    {
        try {
            $product = Product::where('slug', '=', $name)
                ->with('images')
                ->with('category')
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }
        
        return Response()->json(['product' => $product], 200);
    }

    public function getProductsRecently(Request $request)
    {
        $id = explode(",",$request->id);
        $products = Product::with('images')
            ->with('category')
            ->whereIn('id', $id)
            ->get();

        return Response()->json(['products' => $products], 200);
    }

    public function getProductsSearch(Request $request)
    {
        $product = Product::with('images')
            ->with('category')
            ->where('name', 'LIKE', "%{$request->search}%");
        $products = $product->paginate(config('settings.search_paginate'));
        $count = $product->count();

        return Response()->json([
            'products' => $products,
            'count' => $count,
        ], 200);
    }
}
