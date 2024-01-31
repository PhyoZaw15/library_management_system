<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\BorrowBook;
use App\Services\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BorrowBookController extends Controller
{
    public function borrowBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $user = Auth::user();
        $currentDate = now();
        $data = $request->all();

        $data['user_id'] = $user->id;
        $data['order_code'] = $this->generateOrderCode();
        $data['type'] = 'borrow';
        $data['status'] = 'pending';

        $borrowBook_data = BorrowBook::create($data);

        return ApiResponse::success($borrowBook_data, 'Success', 201);
    }

    public function returnBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $user = Auth::user();
        $currentDate = now();
        $data = BorrowBook::where('order_code', $request->order_code)->where('user_id', $user->id)->first();
        if (!$data) {
            return ApiResponse::error("Your data not found!", 404);
        }

        $data->update([
            'type' => 'return',
            'status' => 'pending'
        ]);

        return ApiResponse::success($data, 'Success', 201);

    }

    function generateOrderCode()
    {
        $prefix = 'MYSOL';
        $timestampComponent = now()->format('YmdHis');
        $randomComponent = Str::random(4);
        $orderCode = $prefix . $timestampComponent . $randomComponent;
        return $orderCode;
    }
}
