<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        $data = [
            'message' => 'message',
            'bufferImage' => 'base64 image, null if message not contain image',
            'from' => '6281312634776',
            'gateway' => '6285954906329',
        ];

        Log::debug('Mengirim pesan:', ['data' => $data]);

        // Melakukan permintaan HTTP
        $response = Http::post($url, $data);

        // Menulis pesan debug setelah melakukan permintaan HTTP
        Log::debug('Respon dari permintaan:', ['response' => $response->json()]);
    }
}
