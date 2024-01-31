<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Services\ApiResponse;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category', 'author')->get();
        return ApiResponse::success($books, 'Success', 200);
    }
}
