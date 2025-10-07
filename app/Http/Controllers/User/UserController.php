<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Str;

class UserController extends Controller
{
    public function index(){
        $users = User::all();

        return view('user.users', compact('users'));
    }

    public function createUser(){
        return view('user.create-user');
    }

    public function createAdmin(){
        return view('user.create-admin');
    }

    public function storeAdmin(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);

        User::create([
            'id' => Str::uuid(),
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'roles' => 'admin',
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.index')->with('success', 'Administrator created');
    }

    public function storeUser(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'permissions' => 'nullable|array'
        ]);

        User::create([
            'id' => Str::uuid(),
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'roles' => 'user',
            'access_list' => $validated['permissions'] ?? [],
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.index')->with('success', 'User created');
    }

    public function toggle(Request $request, $id){
        $user = User::findOrFail($id);

        $user->update([
            'enabled' => !$user->enabled
        ]);

        return redirect()->back()->with('success', 'Success user toggle!');
    }
}