<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\TestSendEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function index()
    {
    $users = User::all();
      Mail::to('baktiryan182@gmail.com')->send(new TestSendEmail($users));
    }
}
