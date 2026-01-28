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
        $authUser = auth()->user();

        if (!$authUser || !$authUser->isAdmin()) {
            return redirect('card/info');
        }

        if ($authUser->id == $id) {
            return redirect()->back()->with('error', 'Ви не можете видалити власний акаунт');
        }

        $user = User::findOrFail($id);

        $user->update([
            'enabled' => !$user->enabled
        ]);

        return redirect()->back()->with('success', 'Статус користувача змінено');
    }


    public function edit($id){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        $editUser = User::findOrFail($id);

        return view('user.edit-user', compact('editUser'));
    }

    public function update(Request $request, $id){
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            return redirect('card/info');
        }

        $editUser = User::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $editUser->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'permissions' => 'nullable|array'
        ]);

        $data = [
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($editUser->role === UserRole::USER) {
            $data['access_list'] = $validated['permissions'] ?? [];
        }

        $editUser->update($data);

        return redirect()->route('user.index')->with('success', 'Користувача оновлено');
    }
}