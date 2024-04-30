<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
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

        $device = Device::find($request->device);

        // Mencari data history terakhir
        $latestHistory = History::with('device')
            ->where('device_id', $request->device) // Filter berdasarkan device yang dipilih
            ->orderByDesc('date_time')
            ->get();;

        // Memeriksa apakah data history terakhir ada
        if ($latestHistory) {
            // Mendapatkan data perangkat terkait
            $device = $latestHistory->device;

            // Membangun pesan
            $message = "Data histori terbaru dari perangkat {$device->name}:\n";
            $message .= "Waktu: {$latestHistory->created_at}\n";
            // tambahkan informasi lain yang Anda inginkan dari data histori dan perangkat

            // Membangun data yang akan dikirim ke endpoint
            $data = [
                "gateway" => $requestData['gateway'],
                "number" => $requestData['from'], // Menggunakan nomor pengirim sebagai nomor penerima
                "type" => "text",
                "message" => $message,
            ];

            // Melakukan permintaan HTTP
            $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->post($url, $data);

            // Menulis pesan debug setelah melakukan permintaan HTTP
            // Log::debug('Respon dari permintaan:', ['response' => $response->getBody()->getContents()]);
        } else {
            Log::error('Tidak ada data histori yang ditemukan.');
        }
    }
}
