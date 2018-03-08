<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Comment;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use JWTAuth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name)
    {
        try {
            $product = Product::where('slug', '=', $name)->firstOrFail();
            $product_id = $product->id;
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        $comments = Comment::with('user')
            ->where('product_id', '=', $product_id)
            ->where('parent_id', '=', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return Response()->json([
            'success' => true,
            'comments' => $this->list_comments($comments),
        ], 200);
    }

    function list_comments($comments)
    {
        $data = [];

        foreach($comments as $comment)
        {
            $data[] = [
                'id' => $comment->id,
                'content' => $comment->content,
                'parent_id' => $comment->parent_id,
                'user_id' => $comment->user_id,
                'product_id' => $comment->product_id,
                'created_at' => $comment->created_at->format('M d, Y H:i:s'),
                'user' => $comment->user,
                'comments' => $this->list_comments($comment->comments),
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
    public function store(Request $request, $name)
    {
        try {
            $product = Product::where('slug', '=', $name)->firstOrFail();
            $product_id = $product->id;
            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->id;
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        } catch (Exception $e) {
            return Response()->json([
                trans('setting.message.fails.me.not_found'),
            ], 404);
        }

        $data = [
            'content' => $request->content,
            'product_id' => $product_id,
            'user_id' => $user_id,
            'parent_id' => $request->parent_id,
            'created_at' => Carbon::now(),
        ];

        Comment::create($data);

        return $this->index($name);
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
        try {
            $comment = Comment::findOrFail($id);
            $product = Product::findOrFail($comment->product_id);
            $user = JWTAuth::parseToken()->authenticate();
            if ($comment->user_id != $user->id) {
                return Response()->json([
                    'success' => false,
                ], 442);
            }
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return $this->index($product->slug);
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
            $comment = Comment::findOrFail($id);
            $product = Product::findOrFail($comment->product_id);
            $user = JWTAuth::parseToken()->authenticate();
            if ($comment->user_id != $user->id) {
                return Response()->json([
                    'success' => false,
                ], 442);
            }
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        $comment->delete();

        return $this->index($product->slug);
    }
}
