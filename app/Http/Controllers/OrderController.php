<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'quantity'=>'required|double',
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
}
