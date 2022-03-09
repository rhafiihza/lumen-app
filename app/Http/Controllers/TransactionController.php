<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 400);
        }
        
        if(!$user = User::find($request->get('user_id'))){
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        if(!Product::find($request->get('product_id'))) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        $order_code = 'TR' . date('Ymd');
        
        if($find_transaction = Transaction::find($order_code)) {
            $num = count($find_transaction) + 1;
            $order_code = 'TR' . date('Ymd') . $num;
        }else {
            $order_code = 'TR' . date('Ymd') . 0;
        }
        // print_r($find_transaction);
        // print_r("    sadsds ");
        // print_r($order_code);
        // die();
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'Mid-server-iOkGRKl5tcJk7u3f5XYYF3u4';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->get('amount') * $request->get('price'),
            ),
            'customer_details' => array(
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1],
                'email' => $user->email,
                // 'phone' => '08111222333',
            ),
        );
        
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $transaction = Transaction::create([
            'user_id' => $request->get('user_id'),
            'order_code' => $order_code,
            'status' => 2,
            'amount' => $request->get('amount'),
            'price' => $request->get('price'),
            'total_price' => $request->get('amount') * $request->get('price'),
        ]);
        if($transaction){
            return response()->json(['status' => 'success', 'message' => 'Transaction created successfully'], 201);
        }else {
            return response()->json(['status' => 'success', 'message' => 'Transaction not created'], 400);
        }
    }
}