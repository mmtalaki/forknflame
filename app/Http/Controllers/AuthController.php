<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $role = Role::where('slug','user')->first()->id;
        $validated['role_id'] = $role;

        try {
            $user = User::create($validated);

            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id'=>$user->id, 'hash'=>sha1($user->email)]
            );

            $user->notify(new VerifyEmailNotification($signedUrl));

            return response()->json([
                'message' => 'Registration Successful! A Verification Email Has been sent to You.',
                'user' => $user,
                'statusCode' => 201
            ]);
        } 
        catch (\Exception $exception) {
            return response()->json([
                'Error' => "Registration Failed!",
                'Message' => $exception->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        try {
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ], 401);
            }

            $token = $user->createToken("auth-token")->plainTextToken;
            return response()->json([
                'message' => 'LogIn Successful!',
                'user' => $user,
                'token' => $token,
                'abilities'=>$user->abilities(),
                'statusCode' => 201
            ]);
        }

        catch (\Exception $exception) {
            return response()->json([
                'Error' => "LogIn Failed!",
                'Message' => $exception->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successful!',
            'statusCode' => 200
        ]);
    }
}
