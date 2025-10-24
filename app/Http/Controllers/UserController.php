<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|string',
            'password' => 'nullable|min:8'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        try {
            $user->save();
            return response()->json([
                'User' => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to save User',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $user = user::all();
            if ($user) {
                return response()->json([
                    'User' => $user
                ], 200);
            } else {
                return "No User was found.";
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch User',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $User = user::findOrFail($id);
            return response()->json([
                'User' => $User
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch User',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|min:4',
            'address' => 'required|string',
            'description' => 'nullable|max:1000'
        ]);
        
        $user->name = $request->name;
        $user->address = $request->address;
        $user->description = $request->description;

        try {
            $user->save();
            return response()->json([
                'User' => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update User',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function delete($id){
        $user = User::findOrFail($id);
        if($user){
            try{
                $user->delete();
                return response()->json([
                    'User Deleted Successsfully!'
                ], 200);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=>$exception->getMessage(),
                    'message'=>'Failed to delete'
                ], 500);
            }
        }
        else{
            return "User was not found";
        }
    }
}
