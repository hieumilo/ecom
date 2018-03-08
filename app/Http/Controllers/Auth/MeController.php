<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Carbon\Carbon;
use App\Helper\Helper;

class MeController extends Controller
{
    public function getProfile()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return Response()->json([
                    trans('setting.message.fails.me.not_found'),
                ], 404);
            }
            
            $profile = User::with('orders')->find($user->id);

            return Response()->json(['user' => $profile], 200);
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return Response()->json([
                trans('setting.message.fails.me.token_expired'),
            ], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return Response()->json([
                trans('setting.message.fails.me.token_invalid'),
            ], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return Response()->json([
                trans('setting.message.fails.me.token_absent'),
            ], $e->getStatusCode());
        }
    }

    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = [];
        $rules = [];
        if ($request->old_password) {
            $data = [
                'old_password' => $request->old_password,
                'new_password' => $request->new_password,
                're_password' => $request->re_password,
                'password' =>  $request->new_password,
            ];
            $rules = [
                'old_password' => 'required',
                'new_password' => 'required',
                're_password' => 'required|same:new_password',
            ];
        }

        $data = array_merge($data, [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender == 'male',
        ]);
        $rules = array_merge($rules, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
            'address' => 'required|max:255',
            'gender' => 'required',
        ]);
        if ($request->file) {
            $data['avatar'] = Helper::upload($request->file, 'avatar');
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return Response()->json([
                'error' => $validator->messages(),
            ], 422);
        }

        $profile = User::find($user->id);
        $profile->update($data);

        return Response()->json([
            'success' => true,
            'message' => trans('setting.message.success.user.update'),
            'user' => $profile,
        ], 200);
    }
}
