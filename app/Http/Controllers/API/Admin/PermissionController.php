<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Services\ApiResponse;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return ApiResponse::success($permissions, 'Success', 200);
    }
}
