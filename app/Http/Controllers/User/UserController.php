<?php

namespace App\Http\Controllers\User;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        $users = User::where('enabled', 1)->get();

        return view('user.users', compact('users'));
    }

    public function createUser(){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }
        
        return view('user.create-user');
    }

    public function createAdmin(){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        return view('user.create-admin');
    }

    public function storeAdmin(Request $request){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);

        User::create([
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'role' => UserRole::ADMIN,
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.index')->with('success', 'Administrator created');
    }

    public function storeUser(Request $request){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'permissions' => 'nullable|array'
        ]);

        User::create([
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'role' => UserRole::USER,
            'access_list' => $validated['permissions'] ?? [],
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('user.index')->with('success', 'User created');
    }

    public function toggle(Request $request, $id){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }
        
        $user = User::findOrFail($id);

        $user->update([
            'enabled' => !$user->enabled
        ]);

        return redirect()->back()->with('success', 'Success user toggle!');
    }
}