<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:book-list', ['only' => ['index']]);
        $this->middleware('permission:book-create', ['only' => ['store']]);
        $this->middleware('permission:book-edit', ['only' => ['update']]);
        $this->middleware('permission:book-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $books = Book::with('category', 'author')->get();
        return ApiResponse::success($books, 'Success', 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author_id' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $data = $request->all();

        $book = Book::create($data);

        return ApiResponse::success($book, 'Book created successfully.', 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return ApiResponse::success($book, 'Success', 200);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $book->update($request->all());

        return ApiResponse::success( $book, 'Book updated successfully.', 404);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return ApiResponse::error(null, 'Book not found.', 404);
        }

        // $book_in_transactions = Transaction::where('book_id', $id)->exists();
        // if ($book_in_transactions) {
        //     return ApiResponse::error('Book has associated transaction. Cannot delete.', 422);
        // }

        $book->delete();
        return ApiResponse::success(null, 'Book deleted successfully.', 201);
    }
}
