<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        if(Auth::check()) {
            return redirect('/card/info');
        }

        return view('auth.login');
    }

    public function postLogin(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'enabled' => true])){
            $request->session()->regenerate();

            return redirect()->intended('/card/info');
        }

        return back()->withErrors([
             'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    public function postLogout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}