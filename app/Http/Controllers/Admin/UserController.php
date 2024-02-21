<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Mengambil semua data pengguna
        return view('admin.user', compact('users')); // Mengirimkan data pengguna ke view 'admin.user'
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
}

