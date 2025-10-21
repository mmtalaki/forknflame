<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'amount_paid'=>'required|double',
            'reciept_no'=>'required|string',
            'order_id'=>'required|integer|exists:orders,id'        
        ]);

        $payment = new Payment();
        $payment->amount_paid = $request->amount_paid;
        $payment->reciept_no = $request->reciept_no;
        $payment->order_id = $request->order_id;

        try{
            $payment->save();
            return response()->json([
                'Payment'=>$payment
            ], 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'Error'=> 'Failed to save payment',
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
}
