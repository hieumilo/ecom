<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Models\Social;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return Response()->json([
            'success' => true,
            'users' => $users,
        ], 200);
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
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        return Response()->json([
            'success' => true,
            'user' => $user,
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
        try {
            $user = User::findOrFail($id);
            $social = Social::where('user_id', '=', $id)->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return Response()->json([$ex], 404);
        }

        DB::beginTransaction();
        try {
            $user->delete();
            $social->delete();

            DB::commit();

            return Response()->json([
                'success' => true,
                'message' => trans('setting.message.success.user.delete'),
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return Response()->json([
                'success' => false,
                'error' => trans('setting.message.fails.user.delete'),
                'data' => $e->getMessage(),
            ], 422);
        }
    }
}
