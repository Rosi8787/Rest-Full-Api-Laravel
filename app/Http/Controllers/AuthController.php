<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       try{
         $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successfull',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
        ], 200);

       }catch(\Exception $e){
        return response()->json([
            'status'=>'error',
            'message'=>'Terjadi error pada server',
            'error'=> $e->GetMessage()
        ],500);
       }
    }

    public function login(Request $request)
    {
       try {
         $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!$token = JWTAuth::classattempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'invalid email or password'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successfull',
            'user' => auth('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
       }catch(\Exception $e){
        return response()->json([
            'status'=>'error',
            'message'=>'terjadi kesalahan pada server',
            'error'=> $e->getMessage()
        ],500);
       }
    }

    public function me()
    {
       try{
         return response()->json([
            'success' => true,
            'user' => auth('api')->user()
        ]);
       }catch(\Exception $e){
        return response()->json([
            'status'=>'error',
            'message'=>'terjadi error pasa server',
            'error' => $e->getMessage()
        ],500);

       }
    }

    public function logout()
    {

        // JWTAuth::logout();

        try {
            JWTAuth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Success loged out'

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        $token = JWTAuth::refresh();

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
}
