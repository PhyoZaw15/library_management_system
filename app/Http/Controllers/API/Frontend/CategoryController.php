<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\ApiResponse;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return ApiResponse::success($categories, 'Success', 200);
    }
}
