<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function admin()
    {
        $title = 'Profile';
        return view('profile.admin', compact('title'));
    }
    public function customer()
    {
        $title = 'Profile';
        return view('profile.customer',compact('title'));
    }
}
