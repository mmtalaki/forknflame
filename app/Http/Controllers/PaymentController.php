<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'amount_paid'=>'required|numeric|min:1',
            'reciept_no'=>'required|string',
            'order_id'=>'required|integer|exists:orders,id'        
        ]);

        $payment = new Payment();
        $payment->amount_paid = $request->amount_paid;
        $payment->reciept_no = $request->reciept_no || null;
        $payment->order_id = $request->order_id;

        try{
            $payment->save();
            // generate receipt based on id and date, e.g. RCPT-20251031-000123
            if (empty($payment->receipt_no)) {
                $prefix = 'RCPT-' . now()->format('Ymd') . '-';
                $payment->receipt_no = $prefix . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
                $payment->save(); // persist receipt_no
            }
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
            // $payment = Payment::all();
            $payment = Payment::join('orders', 'payments.order_id', '=', 'orders.id')
                ->select('payments.*', 'orders.order_code as order_code')
                ->get();
            if ($payment) {
                return response()->json([
                    'Payment' => $payment
                ], 200);
            } 
            else {
                return "No payment was found.";
            }
        } 
        catch (\Exception $exception) {
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
        } 
        catch (\Exception $exception) {
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
            'amount_paid' => 'required|numeric|min:1',
            'receipt_no' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        $payment->amount_paid = $request->amount_paid;
        $payment->receipt_no = $request->receipt_no || null;
        $payment->order_id = $request->order_id;

        try {
            $payment->save();
            // generate receipt based on id and date, e.g. RCPT-20251031-000123
            if (empty($payment->receipt_no)) {
                $prefix = 'RCPT-' . now()->format('Ymd') . '-';
                $payment->receipt_no = $prefix . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
                $payment->save();
            }
            return response()->json([
                'Payment' => $payment
            ], 200);
        } 
        catch (\Exception $exception) {
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
            } 
            catch (\Exception $exception) {
                return response()->json([
                    'error' => $exception->getMessage(),
                    'message' => 'Failed to delete'
                ], 500);
            }
        } 
        else {
            return "Payment was not found";
        }
    }
}
