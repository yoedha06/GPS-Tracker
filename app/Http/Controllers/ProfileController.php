<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('profile.admin', compact('user'));
        } else {
            return view('profile.customer', compact('user'));
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        // Jika ada file foto yang diupload, simpan file tersebut dan update path foto di database
        if ($request->hasFile('photo')) {
            
            $photo = time().'.'.$request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('photos'), $photo);

            $user->photo = $photo;
            $user->save();

        }

        // Update data pengguna
        $user->name = $validatedData['name'];
        $user->username = $validatedData['email'];
        $user->email = $validatedData['email'];

        $user->save();

        // Redirect ke halaman profil dengan pesan sukses
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui!');
        }
    }

    public function deletePhoto(Request $request)
    {
        $user = Auth::user();

        // Hapus foto profil dari penyimpanan jika ada
        if ($user->photo) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui!');
        } else {
            return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui!');
        }
    }
    
}
