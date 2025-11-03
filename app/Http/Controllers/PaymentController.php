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
    public function calculateUserBalance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = $request->user_id;

        // orders_sum: sum of (food.price * orders.quantity) for this user
        $ordersSum = DB::table('orders')
            ->join('food', 'orders.food_id', '=', 'food.id')
            ->where('orders.user_id', $userId)
            ->select(DB::raw('COALESCE(SUM(food.price * orders.quantity),0) as orders_sum'))
            ->value('orders_sum');

        // payments_sum: sum payments for orders that belong to this user
        $paymentsSum = DB::table('payments')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->select(DB::raw('COALESCE(SUM(payments.amount_paid),0) as payments_sum'))
            ->value('payments_sum');

        $balanceDue = round((float)$ordersSum - (float)$paymentsSum, 2);

        return response()->json([
            'orders_sum'   => (float)$ordersSum,
            'payments_sum' => (float)$paymentsSum,
            'balance_due'  => (float)$balanceDue,
        ], 200);
    }
}
