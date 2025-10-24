<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'quantity'=>'required|double|min:4',
            'status'=>'required|string',
            'user_id'=>'required|integer|exists:users,id',
            'food_id'=>'required|integer|exists:food,id'
        ]);

        $order = new Order();
        $order->quantity = $request->quantity;
        $order->status = $request->status;
        $order->user_id = $request->user_id;
        $order->food_id = $request->food_id;

        try{
            $order->save();
            return response()->json([
                'Order'=>$order
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
            $order = Order::all();
            if ($order) {
                return response()->json([
                    'Order' => $order
                ], 200);
            } else {
                return "No order was found.";
            }
        } catch (\Exception $exception) {
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
        } catch (\Exception $exception) {
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
            'quantity' => 'required|double|min:4',
            'status' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'food_id' => 'required|integer|exists:food,id'
        ]);

        $order->quantity = $request->quantity;
        $order->status = $request->status;
        $order->user_id = $request->user_id;
        $order->food_id = $request->food_id;

        try {
            $order->save();
            return response()->json([
                'Order' => $order
            ], 200);
        } catch (\Exception $exception) {
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
}
