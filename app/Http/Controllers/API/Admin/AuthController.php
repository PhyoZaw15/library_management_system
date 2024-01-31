<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MySol_Admin_YouCanDoSomething')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return ApiResponse::success($success, 'User login successfully.', 200);
        } else { 
            return ApiResponse::error('Email or Password Invalid', 400);
        }
    }
}
