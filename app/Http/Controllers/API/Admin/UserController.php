<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('json.response:user-list', ['only' => ['index']]);
        $this->middleware('json.response:user-create', ['only' => ['store']]);
        $this->middleware('json.response:user-edit', ['only' => ['update']]);
        $this->middleware('json.response:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $users = User::with('roles')->where('user_type', 'ADMIN')->get();
        return ApiResponse::success($users, 'Success', 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['user_type'] = 'ADMIN';

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return ApiResponse::success($user, 'User created successfully.', 201);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return ApiResponse::success($user, 'Success', 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        if(!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')
            ->where('model_id', $id)
            ->delete();

        $user->assignRole($request->input('roles'));

        return ApiResponse::success($user, 'User updated successfully.', 201);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return ApiResponse::success(null, 'User deleted successfully.', 201);
    }
}
