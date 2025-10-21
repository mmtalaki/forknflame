<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|min:4',
            'price'=>'required|double',
            'description'=>'required|max:2000',
            'food_code'=>'required|string|max:10',
            'category_id'=>'required|integer|exists:categories,id',
            'restaurant_id'=>'required|integer|exists:restaurants,id'
        ]);

        $food = new Food();
        $food->name = $request->name;
        $food->price = $request->price;
        $food->description = $request->description;
        $food->food_code = $request->food_code;
        $food->category_id = $request->category_id;
        $food->restaurant_id = $request->restaurant_id;


        try{
            $food->save();
            return response()->json([
                'Food'=>$food
            ], 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'Error'=> 'Failed to save food',
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $food = Food::all();
            if ($food) {
                return response()->json([
                    'Food' => $food
                ], 200);
            } else {
                return "No food was found.";
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Food',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $food = Food::findOrFail($id);
            return response()->json([
                'Food' => $food
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Food',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name' => 'required|string|min:4',
            'price' => 'required',
            'description' => 'required|max:2000',
            'food_code' => 'required|string|max:10',
            'category_id' => 'required|integer|exists:categories,id',
            'restaurant_id' => 'required|integer|exists:restaurants,id'
        ]);

        $food->name = $request->name;
        $food->price = $request->price;
        $food->description = $request->description;
        $food->food_code = $request->food_code;
        $food->category_id = $request->category_id;
        $food->restaurant_id = $request->restaurant_id;

        try {
            $food->save();
            return response()->json([
                'Food' => $food
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update Food',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        $food = Food::findOrFail($id);
        if ($food) {
            try {
                $food->delete();
                return response()->json([
                    'Food Deleted Successsfully!'
                ], 200);
            } catch (\Exception $exception) {
                return response()->json([
                    'error' => $exception->getMessage(),
                    'message' => 'Failed to delete'
                ], 500);
            }
        } else {
            return "Food was not found";
        }
    }
}