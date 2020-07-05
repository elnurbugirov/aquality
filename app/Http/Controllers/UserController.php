<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\System\UsefulMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $credentials = $request->only('email', 'password');

        $user = User::where([
            'email' => $request->get('email'),
        ])->first();

        try {
            if(!$user){
                return UsefulMethods::createResponse(3, trans('messages.not_found'), [], 401);
            }
            if(!Hash::check($request->get('password'), $user->password))
                return UsefulMethods::createResponse(3, trans('messages.invalid_credentials'), [], 400);

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

        } catch (JWTException $e) {
            return UsefulMethods::createResponse(3, trans('messages.could_not_create_token'), [], 500);
        }
        return response()->json($token);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'region_id' =>'int'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'regions_id' => $region_id=$request->get('region_id'),
            'password' => Hash::make($request->get('password'))
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ],200);
    }

    public function getAuthenticatedUser()
    {
        return response()->json(auth()->user());
    }
}
