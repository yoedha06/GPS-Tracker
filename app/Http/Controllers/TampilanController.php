<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TampilanController extends Controller
{
    public function index()
    {
        //tampilan Customer
        return view('customer.index');
    }
    public function admin()
    {
        //tampilan Admin
        return view('admin.index');
    }
}
