<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|min:4',
            'description'=>'nullable|max:1000'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;

        try{
            $category->save();
            return response()->json([
                'Category'=>$category
            ], 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'Error'=> 'Failed to save category',
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        try {
            $category = Category::all();
            if($category){
                return response()->json([
                    'Category' => $category,
                ], 200);
            }
            else{
                return "No category was found."; 
            }
        } 
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Fetch Categorys",
            ], 500);
        }
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        try {
            return response()->json([
                'Category' => $category
            ], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Fetch Category",
        ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string|min:4',
            'description'=>'nullable|max:1000'
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;        

        try {
            $category->save();
            return response()->json([
                'restauarant' => $category,
            ], 200);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Failed to Update Category",
                'message'=>$exception->getMessage()
            ], 500);
        }
    }
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if ($category)
            try {
                $category->delete();
                return response()->json([
                    'message'=>"Category Deleted Successfully!"
            ], 500);
            }
            catch (\Exception $exception) {
                return response()->json([
                    'Error' =>$exception->getMessage(),
                    'message'=>"Failed to Delete Category"
            ], 500);
        }
    }
}

