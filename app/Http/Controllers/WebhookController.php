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

        // Check if the device exists
        $device = Device::find($request->id_device);

        // Mencari data history terbaru menggunakan relasi
        $latestHistory = History::with('device')
            ->where('device_id', $request->id_device)
            ->orderByDesc('date_time')
            ->first(); // Menggunakan first() untuk mendapatkan satu hasil saja


        // Periksa apakah ada history terbaru
        if ($latestHistory->isNotEmpty()) {
            $latestHistory = $latestHistory->first();
            $address = $this->getAddressFromCoordinates($latestHistory->latitude, $latestHistory->longitude);

            // Mendapatkan URL foto perangkat
            $photoUrl = asset('storage/' . $device->photo); // Sesuaikan dengan lokasi penyimpanan foto perangkat

            // Membangun pesan
            $message = "Data terbaru dari perangkat: {$device->name}\n";
            $message .= "Alamat: {$address}\n";
            $message .= "LatLong: https://www.google.com/maps?q={$latestHistory->latitude},{$latestHistory->longitude}\n";
            $message .= "Plat Nomor: {$device->plat_nomor}\n";
            $message .= "Waktu: {$latestHistory->date_time}\n";

            // Membangun data yang akan dikirim ke endpoint
            $data = [
                'gateway' => '6285954906329',
                'number' => $request->phone,
                'type' => 'media',
                'message' => $message,
                'media_file' => $photoUrl
            ];

            // Melakukan permintaan HTTP
            $response = Http::timeout(60)->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, $data);

            // Memeriksa apakah permintaan berhasil
            if ($response->ok()) {
                Log::info('Pesan terkirim:', ['response' => $response->getBody()->getContents()]);
            } else {
                Log::error('Gagal mengirim pesan:', ['error' => $response->json()]);
            }
        } else {
            Log::error('Tidak ada data histori yang ditemukan.');
        }
    }
}
