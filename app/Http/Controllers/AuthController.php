<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){
        // $this->middleware("auth")->except("");
        // $this->middleware("auth",["except" => ["login","me"]]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //checking the user in DB for authentication
    public function login(){
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public static function loginFromWebLogin(Request $request){
        $credentials = request(['email', 'password']);
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 200);
    }

    public static function refreshFromWeb($token){
        return response()->json([
            'access_token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 200);
    }

    //To get the user details
    public function me(){
        return response() -> json(auth('api')->user());
    }
    //logout the user
    public function logout(){
        Auth::logout();;
        return response() -> json('message','Successfully logged out');
    }
    //To get new token
    public function refresh(){
        return $this->respondWithToken(auth('api')->refresh());
    }
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    //Register a new user
    protected function register(Request $request){
        
        // $validate_response = $this->validate($request, [
        //     "name" => "required",
        //     'email'=> 'required',
        //     'password'=> 'required|min:8'
        // ]);


        // creating a new validator instance to define validation criterias
        $validator_response = Validator::make($request->all(), [
            'name'=> 'required',
            'email'=> 'required',
            'password'=> 'required|min:8'
            ]);
        if($validator_response ->fails()){
            return response()->json([
                'error'=> $validator_response->errors()
            ], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['message' => 'User Created successfully'], 200);
    }
}
