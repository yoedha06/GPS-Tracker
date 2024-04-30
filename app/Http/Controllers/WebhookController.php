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

        // Mendapatkan data histori terbaru dari perangkat
        $device = Device::find($request->device);

        if (!$device) {
            return redirect()->route('customer.notification.index')->with('error', 'Perangkat tidak ditemukan');
        }

        $histories = History::with('device')
            ->where('device_id', $request->device) // Filter berdasarkan device yang dipilih
            ->orderByDesc('date_time') // Urutkan histori berdasarkan waktu descending
            ->take(1) // Ambil hanya 1 data histori terbaru
            ->get();

        if ($histories->isNotEmpty()) {
            $history = $histories->first();

            $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);
            $photoUrl = asset('storage/' . $device->photo); // URL foto perangkat

            $message = "Data terbaru dari perangkat: {$device->name}\n";
            $message .= "Alamat: {$address}\n";
            $message .= "LatLong: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
            $message .= "Plat Nomor: {$device->plat_nomor}\n";
            $message .= "Waktu: {$history->date_time}\n";

            $data = [
                'gateway' => '6285954906329',
                'number' => $request->phone,
                'type' => 'media',
                'message' => $message,
                'media_file' => $photoUrl
            ];

            // Melakukan permintaan HTTP untuk mengirim pesan
            $response = Http::timeout(60)
                ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, $data);

            // Menulis pesan debug setelah melakukan permintaan HTTP
            Log::debug('Respon dari permintaan:', ['response' => $response->getBody()->getContents()]);
        } else {
            Log::error('Tidak ada histori ditemukan.');
        }
    }
}
