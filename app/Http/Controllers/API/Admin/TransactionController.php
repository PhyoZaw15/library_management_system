<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BorrowBook;
use App\Services\ApiResponse;

class TransactionController extends Controller
{
    public function index()
    {
        $data = BorrowBook::with('user', 'book')->get();
        return ApiResponse::success($data, 'Success', 200);
    }
}
