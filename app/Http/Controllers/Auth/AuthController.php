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

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'enabled' => true,
        ])) {
            return back()
                ->withErrors([
                    'email' => __('Невірний email або пароль'),
                ])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended('/card/info');
    }


    public function postLogout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}