<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function index()
    {
        return view('auth.validation');
    }

    public function indexPhone()
    {
        return view('auth.validation-phone');
    }
}
