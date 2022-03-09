<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller{
    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(['status' => 'error', 'message' => 'login failed']), 400);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->api_token = base64_encode(Str::random(40));
                $user->save();
                return response()->json(['status' => 'success', 'data' => $user], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'login failed'], 400);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'login failed'], 400);
        }

        // $credentials = $request->only('email', 'password');
        // if(!$token = auth()->attempt($credentials)){
        //     return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        // }
        // return $this->respondWithToken($token);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        if($user){
            return response()->json(['status' => 'success', 'message' => 'User created successfully'], 201);
        }else {
            return response()->json(['status' => 'success', 'message' => 'User not created'], 400);
        }
    }

    public function logout(Request $request){
        $user = User::where('api_token', $request->api_token)->first();
        if($user){
            $user->api_token = null;
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'User logged out successfully'], 200);
        }else {
            return response()->json(['status' => 'error', 'message' => 'User not logged out'], 400);
        }
    }
    
    protected function respondWithToken($token){
        return response()->json([
            'status' => 'success',
            'message' => 'Authentication successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}