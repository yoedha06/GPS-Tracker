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

        // Check if the message is a history request
        $explodedMessage = explode(" ", $request->message);
        if (str($request->message)->startsWith("history") && count($explodedMessage) == 2 && ($explodedMessage[1] ?? false)) {

            // Extract the plat number from the message
            $plat = $explodedMessage[1];

            // Find device based on the plat number
            $device = Device::where('plat_nomor', $plat)->first();
            // Retrieve history for the device
            $history = History::where('device_id', $device->id)
                ->latest()
                ->first();

            if ($device == $history) {

                if ($history) {
                    // Get address from coordinates
                    $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);

                    // Compose message with history details
                    $message = "History terbaru untuk perangkat {$device->name} (Plat Nomor: {$plat}):\n";
                    $message .= "Alamat: {$address}\n";
                    $message .= "Waktu: {$history->date_time}\n";
                    $message .= "Lokasi: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";

                    // Send message
                    $data = [
                        'gateway' => '6285954906329',
                        'number' => $request->from,
                        'type' => 'text',
                        'message' => $message,
                    ];

                    // Send HTTP request
                    $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                        ->post($url, $data);

                    // Check if the request is successful
                    if ($response->ok()) {
                        Log::info('Pesan terkirim:', ['response' => $response->getBody()->getContents()]);
                    } else {
                        Log::error('Gagal mengirim pesan:', ['error' => $response->json()]);
                    }
                } else {
                    Log::error('Tidak ada riwayat untuk perangkat dengan plat_nomor:', ['plat_nomor' => $plat, 'history' => $history]);
                }
            } else {
                Log::error('Perangkat tidak ditemukan untuk plat_nomor:', ['plat_nomor' => $plat]);
            }
        }
        return 'ok';
    }
}
