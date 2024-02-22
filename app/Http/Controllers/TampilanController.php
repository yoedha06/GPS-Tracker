<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TampilanController extends Controller
{
    public function index()
    {
        //tampilan Customer
        $user = auth()->user();
        return view('customer.index', compact('user'));
    }
    public function admin()
    {
        //tampilan Admin
        $user = auth()->user();
        return view('admin.index',compact('user'));
    }

    public function homepage()
    {
        //tampilan homepage
        return view('layouts.homepage');
    }
}
