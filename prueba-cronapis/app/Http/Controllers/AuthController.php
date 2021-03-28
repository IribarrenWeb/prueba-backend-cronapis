<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|max:20|min:6'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response($user,200);
    }

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        if(!\Auth::attempt($credentials)){
            $return = [
                'message' => 'Las credenciales no son correctas',
                'code' => 422
            ];
        }else{
            $user = User::where('email',$request->email)->first();
            $token = $user->createToken('auth-token')->plainTextToken;

            $return = [
                'message' => 'Login exitoso',
                'token'   => $token,
                'code'    => 200  
            ];
        }
        

        return response()->json($return, $return['code']);
    }
}
