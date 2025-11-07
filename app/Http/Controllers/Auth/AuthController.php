<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:30',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()){
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route('dashboard.page')->with('success', 'Login Berhasil');
        }

        return redirect()->route('login')->withInput()->with('failed', 'Login Gagal');
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }
}
