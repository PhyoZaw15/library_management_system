<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:author-list', ['only' => ['index']]);
        $this->middleware('permission:author-create', ['only' => ['store']]);
        $this->middleware('permission:author-edit', ['only' => ['update']]);
        $this->middleware('permission:author-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $authors = Author::all();
        return ApiResponse::success($authors, 'Success', 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $data = $request->all();

        $author = Author::create($data);

        return ApiResponse::success($author, 'Author created successfully.', 201);
    }

    public function show($id)
    {
        $author = Author::findOrFail($id);
        return ApiResponse::success($author, 'Success', 200);
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);
        if (!$author) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $author->update($request->all());

        return ApiResponse::success( $author, 'Author updated successfully.', 404);
    }

    public function destroy($id)
    {
        $aut = Author::find($id);

        if (!$aut) {
            return ApiResponse::error(null, 'Author not found.', 404);
        }

        $book_in_aut = Book::where('author_id', $id)->exists();
        if ($book_in_aut) {
            return ApiResponse::error('Author has associated book. Cannot delete.', 422);
        }

        $aut->delete();
        return ApiResponse::success(null, 'Author deleted successfully.', 201);
    }
}
