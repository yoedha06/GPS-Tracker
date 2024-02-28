<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\KirimEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KirimEmailController extends Controller
{
    public function index()
    {
        // Ganti path sesuai dengan path yang benar
        $attachmentPath = public_path('images/logo.png');

        // Pastikan file ada sebelum dilampirkan
        if (file_exists($attachmentPath)) {
            Mail::to("baktiryan182@gmail.com")->send(new KirimEmail($attachmentPath));
            return '<h2>Sukses Mengirim Email</h2>';
        } else {
            return '<h2>File lampiran tidak ditemukan.</h2>';
        }
    }
}
