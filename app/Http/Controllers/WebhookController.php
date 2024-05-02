<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    // public function store(Request $request)
    // {
    //     $url = "https://app.japati.id/api/send-message";

    //     // Mengambil data dari request yang diterima oleh webhook
    //     $requestData = $request->all();

    //     // Membangun data yang akan dikirim ke endpoint
    //     $data = [
    //         "gateway" => $requestData['gateway'],
    //         "number" => $requestData['from'], // Menggunakan nomor pengirim sebagai nomor penerima
    //         "type" => "text",
    //         "message" => $requestData['message'],
    //     ];

    //     // Log::debug('Mengirim pesan:', ['data' => $data]);

    //     // Melakukan permintaan HTTP
    //     $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
    //         ->post($url, $data);

    //     // Menulis pesan debug setelah melakukan permintaan HTTP
    //     // Log::debug('Respon dari permintaan:', ['response' => $response->getBody()->getContents()]);
    // }




    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        // "history YT4567UZ"
        $explodedMessage = explode(" ", $request->message);
        if (str($request->message)->startsWith("history") && count($explodedMessage) == 2 && ($explodedMessage[1] ?? false)) {

            $plat = $explodedMessage[1];

            $data = [
                'gateway' => '6285954906329',
                'number' => $request->from,
                'type' => 'text',
                'message' => 'anda mengambil history ' . $plat,
                // 'media_file' => $photoUrl
            ];

            // Melakukan permintaan HTTP
            $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, $data);
        }

        // Memeriksa apakah permintaan berhasil
        if ($response->ok()) {
            Log::info('Pesan terkirim:', ['response' => $response->getBody()->getContents()]);
        } else {
            Log::error('Gagal mengirim pesan:', ['error' => $response->json()]);
        }
        return 'ok';
    }
}
