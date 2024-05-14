<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => 'Webhook endpoint',
            'status' => true
        ]);
    }
    //webhook untuk mengirim data history
    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        // Check if the message is a history request
        $explodedMessage = explode(" ", $request->message);
        if (str($request->message)->startsWith("lokasi") && count($explodedMessage) == 2 && ($explodedMessage[1] ?? false)) {

            // Extract the plat number from the message and convert it to uppercase
            $plat = strtoupper($explodedMessage[1]);

            // Find device based on the plat number
            $device = Device::where('plat_nomor', $plat)->first();

            if ($device) {
                // Retrieve history for the device
                $history = History::where('device_id', $device->id_device)
                    ->latest()
                    ->first();

                if ($history) {
                    // mengambil di function getAddressFromCoordinates
                    $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);

                    // Compose message with history details
                    $message = "Lokasi Terakhir *{$plat}*:\n"; //ubah pesan
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
                    Log::error('Tidak ada riwayat untuk perangkat dengan plat_nomor:', ['plat_nomor' => $plat]);
                }
            } else {
                Log::error('Perangkat tidak ditemukan untuk plat_nomor:', ['plat_nomor' => $plat]);
            }
        }
        return 'ok';
    }
    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json";
        $response = Http::get($url);
        $data = $response->json();
        if (isset($data['display_name'])) {
            return $data['display_name'];
        } else {
            return "Alamat tidak ditemukan";
        }
    }
}
