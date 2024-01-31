<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|same:confirm-password|min:6',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'user_type' => 'USER',
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'facebook_url' => $request->facebook_url,
                'linked_url' => $request->linked_url,
                'bio' => $request->bio,
                'education_level' => $request->education_level,
                'address' => $request->address
            ]);

            DB::commit();

            $token = $user->createToken('MySol_User_YouCanDoSomething')->plainTextToken;

            $data = [
                'username' => $user->name,
                'access_token' => $token
            ];

            return ApiResponse::success($data, 'Successfully registered.', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e.' => Registration failed. Please try again later.', 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['access_token'] =  $user->createToken('MySol_User_YouCanDoSomething')->plainTextToken;
            $success['name'] =  $user->name;

            return ApiResponse::success($success, 'User login successfully.', 200);
        } else {
            return ApiResponse::error('Email or Password Invalid', 400);
        }
    }
}
