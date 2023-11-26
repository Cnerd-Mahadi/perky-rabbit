<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Validator;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required'
            ]
        );
        if ($validation->fails())
            return response()->json([
                'error' => $validation->errors(),
            ], 400);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json(['token' => $token, 'user' => $request->user()], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "User creation failed",
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'

        ]);
        if ($validation->fails())
            return response()->json([
                'error' => $validation->errors(),
            ]);

        if (Auth::attempt(['email' => $request->email, 'password' => ($request->password)])) {
            $token = $request->user()->createToken($request->email);
            return response()->json(['token' => $token->plainTextToken, 'user' => $request->user()], 200);
        }
        return response()->json([
            'error' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
