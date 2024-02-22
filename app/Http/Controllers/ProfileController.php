<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function admin()
    {
        $user = auth()->user();
        return view('profile.admin', compact('user'));
    }
    public function customer()
    {
        $user = auth()->user();
        return view('profile.customer',compact('user'));
    }
}
