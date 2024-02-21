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
        return view('admin.index');
    }

    public function homepage()
    {
        //tampilan homepage
        return view('layouts.homepage');
    }
}
