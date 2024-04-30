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

    //     // Mencari data history terakhir
    //     $latestHistory = History::with('device')
    //         ->where('device_id', $request->device) // Filter berdasarkan device yang dipilih
    //         ->orderByDesc('date_time')
    //         ->take(1)
    //         ->get();

    //     // Memeriksa apakah data history terakhir ada
    //     if ($latestHistory->isNotEmpty()) {
    //         $latestHistory = $latestHistory->first();
    //         // Mendapatkan data perangkat terkait
    //         $device = $latestHistory->device;

    //         // Membangun pesan
    //         $message = "Data histori terbaru dari perangkat {$device->name}:\n";
    //         $message .= "Waktu: {$latestHistory->created_at}\n";
    //         // tambahkan informasi lain yang Anda inginkan dari data histori dan perangkat

    //         // Membangun data yang akan dikirim ke endpoint
    //         $data = [
    //             "gateway" => $requestData['gateway'],
    //             "number" => $requestData['from'], // Menggunakan nomor pengirim sebagai nomor penerima
    //             "type" => "text",
    //             "message" => $message,
    //         ];

    //         // Melakukan permintaan HTTP
    //         $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
    //             ->post($url, $data);

    //         // Menulis pesan debug setelah melakukan permintaan HTTP
    //         Log::info('Pesan terkirim dengan sukses:', ['response' => $response->getBody()->getContents()]);
    //     } else {
    //         Log::error('Tidak ada data histori yang ditemukan.');
    //     }
    // }

    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        // Check if the device exists
        $device = Device::find($request->device);

        if (!$device) {
            return redirect()->route('customer.notification.index')->with('error', 'Perangkat tidak ditemukan');
        }

        // Mencari data history terakhir
        $latestHistory = History::with('device')
            ->where('device_id', $request->device) // Filter berdasarkan device yang dipilih
            ->orderByDesc('date_time')
            ->take(1)
            ->get();

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
                return redirect()->route('customer.notification.index')->with('success', 'Pesan terkirim');
            } else {
                logger($response);
                $errorResponse = $response->json();
                logger($errorResponse);
                return redirect()->route('customer.notification.index')->with('error', 'Gagal mengirim pesan');
            }
        } else {
            return redirect()->route('customer.notification.index')->with('error', 'Tidak ada data histori yang ditemukan');
        }
    }
}
