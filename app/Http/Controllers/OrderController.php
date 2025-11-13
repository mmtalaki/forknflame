<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'quantity'=>'required|numeric|min:0.1',
            'order_amount' => 'required|numeric',
            'status'=>'required|string',
            'user_id'=>'required|integer|exists:users,id',
            'food_id'=>'required|integer|exists:food,id'
        ]);
        $order = new Order();
        $order->quantity = $request->quantity;
        $order->order_amount = $request->order_amount;
        $order->status = $request->status;
        $order->order_code = $order->order_code;
        $order->user_id = $request->user_id;
        $order->food_id = $request->food_id;

        try{
            $order->save();

            // ===== Generate Order Code =====
            $today = now()->toDateString(); // YYYY-MM-DD

            // Lock rows created today to avoid same sequence numbers under concurrency
            $countToday = DB::table('orders')
                ->whereDate('created_at', $today)
                ->lockForUpdate()
                ->count();

            // order number for the day (e.g., 001, 002, 003)
            $sequence = str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // Format: 001-ORD-20251031-U12-F34
            $orderCode = sprintf(
                '%s-ORD-%s-U%s-F%s',
                $sequence,
                now()->format('Ymd'),
                $request->user_id,
                $request->food_id
            );

            // Save code
            $order->order_code = $orderCode;
            $order->save();
            return response()->json([
                'Order' => $order
            ], 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'Error'=> 'Failed to save order',
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            // $order = Order::all();
            $order = Order::join('users', 'orders.user_id', '=', 'users.id')
                ->join('food', 'orders.food_id', '=', 'food.id')
                ->select('orders.*', 'users.name as user_name', 'food.name as food_name')
                ->get();
            if ($order) {
                return response()->json([
                    'Order' => $order
                ], 200);
            } 
            else {
                return "No order was found.";
            }
        } 
        catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Order',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json([
                'Order' => $order
            ], 200);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Order',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'quantity' => 'required|numeric|min:0.1',
            'order_amount' => 'required|numeric',
            'status' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'food_id' => 'required|integer|exists:food,id'
        ]);

        $order->quantity = $request->quantity;
        $order->order_amount = $request->order_amount;
        $order->status = $request->status;
        $order->order_code = $order->order_code;
        $order->user_id = $request->user_id;
        $order->food_id = $request->food_id;

        try {
            $order->save();
            return response()->json([
                'Order' => $order
            ], 200);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update Order',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        if ($order) {
            try {
                $order->delete();
                return response()->json([
                    'Order Deleted Successsfully!'
                ], 200);
            } catch (\Exception $exception) {
                return response()->json([
                    'error' => $exception->getMessage(),
                    'message' => 'Failed to delete'
                ], 500);
            }
        } else {
            return "Order was not found";
        }
    }
    public function calculateOrder(Request $request)
    {
        $request->validate([
            'food_id' => 'required|integer|exists:food,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $food = Food::findOrFail($request->food_id);
        // $food = Food::where('id', $request->food_id);
        $price = $food->price;
        $qty = (float) $request->quantity;
        $total = round($price * $qty, 2);

        return response()->json(['total' => $total]);
    }

    public function getUserBalance($userId)
    {
        $totalOrders = Order::where('user_id', $userId)->sum('order_amount');
        // $totalPayments = \App\Models\Payment::where('user_id', $userId)->sum('amount_paid');
        $totalPayments = \App\Models\Payment::join('orders', 'payments.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->sum('payments.amount_paid');
        $balance = round($totalPayments - $totalOrders, 2);

        return response()->json([
            'balance' => $balance,
            'total_orders' => round($totalOrders, 2),
            'total_payments' => round($totalPayments, 2),
        ], 200);
    }
}
