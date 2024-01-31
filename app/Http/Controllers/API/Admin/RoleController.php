<?php

namespace App\Http\Controllers\API\Admin;
use App\Services\ApiResponse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('json.response:role-list', ['only' => ['index']]);
        $this->middleware('json.response:role-create', ['only' => ['store']]);
        $this->middleware('json.response:role-edit', ['only' => ['update']]);
        $this->middleware('json.response:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        return ApiResponse::success($roles, 'Success', 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
                        'name' => 'required|unique:roles,name',
                        'permissions' => 'required|array',
                    ]);
                    
        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'api'
            ]);

            $role->syncPermissions($request->permissions);
            // foreach ($request->input('permission_ids') as $permission_id) {
            //     $permission = Permission::find($permission_id);
            //     $role->givePermissionTo($permission);
            // }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500);
        }

        return ApiResponse::success($role, 'Success', 201);
        
    }

    public function show(Request $request, $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                        ->where("role_has_permissions.role_id",$id)
                        ->select('permissions.id as id', 'permissions.name as name')
                        ->get();
        $data = [];
        $data['role'] = $role->name;
        $data['permissions'] = $rolePermissions;

        return ApiResponse::success($data, 'Success', 200);
    }

    public function update(Request $request, $id)
    {
    
        $role = Role::find($id);

        if ($role) {
            $role->update([ 'name' => $request->name ]);
            $role->syncPermissions($request->input('permissions'));
            // foreach ($request->input('permission_ids') as $permission_id) {
            //     $permission = Permission::find($permission_id);
            //     $role->givePermissionTo($permission);
            // }
        
            return ApiResponse::success($role, 'Successfully updated', 201);
        } else {
            return ApiResponse::error('Role not found', 404);
        }

    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return ApiResponse::success(null, 'Role deleted successfully', 201);
        } else {
            return ApiResponse::error('Role not found', 404);
        }
    }
}
