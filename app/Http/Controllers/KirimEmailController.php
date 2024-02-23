<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\KirimEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KirimEmailController extends Controller
{
    public function index(){
        Mail::to("baktiryan182@gmail.com")->send(new KirimEmail());
        return '<h2>Sukses Mengirim Email</h2>';
    }
}
