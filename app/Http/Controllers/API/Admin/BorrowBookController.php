<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BorrowBook;
use App\Services\ApiResponse;

class BorrowBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:approve', ['only' => ['approveByAdmin']]);
    }

    public function approveByAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error($validator->errors()->all(), 400);
        }

        $currentDate = now();
        $borrow_book = BorrowBook::where('order_code', $request->order_code)->first();

        if ($borrow_book->type == 'borrow') { // to approve borrow request from user
            $borrow_book->update([
                'status' => 'approved',
                'borrowed_at' => $currentDate->format('Y-m-d')
            ]);
        } else { // to approve return request from user
            $borrow_book->update([
                'status' => 'completed',
                'returned_at' => $currentDate->format('Y-m-d')
            ]);
        }        

        return ApiResponse::success($borrow_book, 'Success', 201);
    }
}
