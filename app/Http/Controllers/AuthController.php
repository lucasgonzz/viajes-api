<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    function login(Request $request) {
        $login = false;
        $user = null;
        $property = env('ATTEMPT_PROPERTY');
        if (Auth::attempt([$property => $request->{$property}, 
                           'password' => $request->password], $request->remember)) {
            $login = true;
            $user = Auth()->user();
        } 
        return response()->json([
            'login' => $login,
            'user'  => $user,
        ], 200);
    }

    public function logout(Request $request) {
        Auth::logout();
        return response(null, 200);
    }

}
