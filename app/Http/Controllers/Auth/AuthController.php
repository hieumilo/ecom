<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return Response()->json([
                    'error' => trans('setting.message.fails.login.invalid_credentials'),
                ], 401);
            }

            return Response()->json(['token' => $token], 200);
        } catch (JWTException $e) {
            return Response()->json([
                'error' => trans('setting.message.fails.login.could_not_create_token'),
            ], 500);
        }
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
            'phone' => 'required|regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
            'address' => 'required|max:255',
            'gender' => 'required',
        ]);
    }
    
    public function signup(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'repassword' => $request->repassword,
            'address' => $request->address,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_admin' => false,
            'created_at' => Carbon::now(),
        ];

        $validator = $this->validator($data);

        if ($validator->fails()) {
            return Response()->json([
                'error' => $validator->messages(),
            ], 422);
        }

        User::create($data);

        return Response()->json([
            'message' => trans('setting.message.success.user.create'),
        ], 200);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return Response()->json([
                    trans('setting.message.fails.me.not_found'),
                ], 404);
            }

            return Response()->json(['user' => $user], 200);
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
}
