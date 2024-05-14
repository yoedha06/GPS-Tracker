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


    public function deletePhoto(Request $request)
    {
        $user = Auth::user();

        // Hapus foto profil dari penyimpanan jika ada
        if ($user->photo) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();

            // Mengatur pesan sukses
            $successMessage = 'Foto profil berhasil dihapus.';
        } else {
            // Mengatur pesan kesalahan
            $successMessage = 'Tidak ada foto profil yang dapat dihapus.';
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.profile')->with('success', $successMessage);
        } else {
            return redirect()->route('customer.profile')->with('success', $successMessage);
        }
    }

    
}
