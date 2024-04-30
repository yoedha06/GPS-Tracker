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

        // Mengambil data dari request yang diterima oleh webhook
        $requestData = $request->all();

        // Membangun data yang akan dikirim ke endpoint
        $data = [
            "gateway" => $requestData['gateway'],
            "number" => $requestData['from'], // Menggunakan nomor pengirim sebagai nomor penerima
            "type" => "text",
            "message" => $requestData['message'],
        ];

        Log::debug('Mengirim pesan:', ['data' => $data]);

        // Melakukan permintaan HTTP
        $response = Http::post($url, $data);

        // Menulis pesan debug setelah melakukan permintaan HTTP
        Log::debug('Respon dari permintaan:', ['response' => $response->getBody()->getContents()]);
    }
}
