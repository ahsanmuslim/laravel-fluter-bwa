<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    
    public function all(Request $request) 
    {

        try {
            $id = $request->input('id');
            $limit = $request->input('limit');
            $status = $request->input('status');
    
            if($id){
                $transaction = Transaction::with(['items.product'])->find($id);
    
                if($transaction){
                    return ResponseFormatter::success($transaction, "Data transaksi berhasil diambil");
                } else {
                    return ResponseFormatter::error(null, "Data transaksi tidak ada", 404);
                }
            }
    
            $transaction = Transaction::with(['items.product'])->where('user_id', Auth::user()->id);
    
            if($status){
                $transaction->where('status', $status);
            }
    
            return ResponseFormatter::success(
                $transaction->paginate($limit),
                "Data list transaksi berhasil diambil"
            );

        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went error',
                'error' => $err
            ], 'Data not found', 404);
        }


    }

    public function checkout(Request $request)
    {

        try {
            //validasi data yang dikirimkan oleh userberupa json
            Validator::make($request->all(), [
                'items' => 'required|array',
                'items.*.id' => 'exist:products,id',
                'total_price' => 'required',
                'address' => 'required',
                'shipping_price' => 'required',
                'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED'
            ]);

            //proses input data je table transaction
            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'address' => $request->address,
                'total_price' => $request->total_price,
                'shipping_price' => $request->shipping_price,
                'status' => $request->status
            ]);

            //proses input data je table transaction item (detail)
            foreach ($request->items as $item) {
                TransactionItem::create([
                    'users_id' => Auth::user()->id,
                    'products_id' => $item['id'],
                    'transactions_id' => $transaction['id'],
                    'quantity' => $item['quantity']
                ]);
            }

            return ResponseFormatter::success([
                $transaction->load('items.product'), 
                'Transaksi berhasil'
            ]);

        } catch (Exception $err) {
            return ResponseFormatter::error('Transaksi tidak dapat diproses', $err, 500);
        }

    }

}
