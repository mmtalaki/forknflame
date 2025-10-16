<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|min:4',
            'address'=>'required|string',
            'description'=>'nullable|max:1000'
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->address = $request->address;
        $restaurant->description = $request->description;

        try{
            $restaurant->save();
            return response()->json([
                'Restaurant'=>$restaurant
            ], 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'Error'=> 'Failed to save restaurant',
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $restaurant = Restaurant::all();
            if($restaurant){
                return response()->json([
                    'Restaurant' => $restaurant,
                ], 200);
            }
            else{
                return "No restaurant was found."; 
            }
        } 
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Fetch Restaurants",
            ], 500);
        }
    }
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        try {
            return response()->json([
                'Restaurant' => $restaurant
            ], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Fetch Restaurant",
        ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string|min:4',
            'address'=>'required|string',
            'description'=>'nullable|max:1000'
        ]);

        $restaurant = Restaurant::findOrFail($id);
        $restaurant->name = $request->name;
        $restaurant->address = $request->address;
        $restaurant->description = $request->description;        

        try {
            $restaurant->save();
            return response()->json([
                'restauarant' => $restaurant,
            ], 200);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Update Restaurant",
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function delete($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant)
            try {
                $restaurant->delete();
                return response()->json([
                    'message'=>"Restaurant Deleted Successfully!"
            ], 500);
            }
            catch (\Exception $exception) {
                return response()->json([
                    'Error' =>$exception->getMessage(),
                    'message'=>"Failed to Delete Restaurant"
            ], 500);
        }
    }
}
