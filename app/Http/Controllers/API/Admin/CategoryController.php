<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['store']]);
        $this->middleware('permission:category-edit', ['only' => ['update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $categories = Category::all();
        return ApiResponse::success($categories, 'Success', 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $data = $request->all();

        $category = Category::create($data);

        return ApiResponse::success($category, 'Category created successfully.', 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return ApiResponse::success($category, 'Success', 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $category->update($request->all());

        return ApiResponse::success( $category, 'Category updated successfully.', 404);
    }

    public function destroy($id)
    {
        $cat = Category::find($id);

        if (!$cat) {
            return ApiResponse::error(null, 'Category not found.', 404);
        }

        $book_in_cat = Book::where('category_id', $id)->exists();
        if ($book_in_cat) {
            return ApiResponse::error('Category has associated book. Cannot delete.', 422);
        }

        $cat->delete();
        return ApiResponse::success(null, 'Category deleted successfully.', 201);
    }
}
