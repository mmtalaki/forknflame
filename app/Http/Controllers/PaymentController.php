<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    public function index()
    {
        try {
            $payment = Payment::all();
            if ($payment) {
                return response()->json([
                    'Payment' => $payment
                ], 200);
            } else {
                return "No payment was found.";
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Payment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return response()->json([
                'Payment' => $payment
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Payment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'amount_paid' => 'required|double',
            'receipt_no' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        $payment->amount_paid = $request->amount_paid;
        $payment->receipt_no = $request->receipt_no;
        $payment->order_id = $request->order_id;

        try {
            $payment->save();
            return response()->json([
                'Payment' => $payment
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update Payment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment) {
            try {
                $payment->delete();
                return response()->json([
                    'Payment Deleted Successsfully!'
                ], 200);
            } catch (\Exception $exception) {
                return response()->json([
                    'error' => $exception->getMessage(),
                    'message' => 'Failed to delete'
                ], 500);
            }
        } else {
            return "Payment was not found";
        }
    }
}
