<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function index()
    {
        $devices = Device::where('user_id', auth()->user()->id)->get();

        return view('customer.notification.index', ['devices' => $devices]);
    }

    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        // Check if the device exists
        $device = Device::find($request->device);

        if (!$device) {
            return redirect()->route('customer.notification.index')->with('error', 'Perangkat tidak ditemukan');
        }

        $selectedStartDateTime = $request->scheduled_time;
        $selectedEndDateTime = $request->scheduled_end_time;

        $startDateTime = Carbon::parse($selectedStartDateTime);
        $endDateTime = Carbon::parse($selectedEndDateTime);

        $histories = History::with('device')
            ->where('device_id', $request->device) // Filter berdasarkan device yang dipilih
            ->whereBetween('date_time', [$startDateTime, $endDateTime])
            ->get();

        // Periksa data dalam range
        if ($histories->isNotEmpty()) {
            foreach ($histories as $history) {
                $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);

                // Mendapatkan URL foto perangkat
                $photoUrl = asset('storage/' . $device->photo); // Sesuaikan dengan lokasi penyimpanan foto perangkat
                // $photoUrl = "https://files.f-g.my.id/images/dummy/buku-2.jpg";

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

                $response = Http::timeout(60)->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                    ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->post($url, $data);

                if (!$response->ok()) {
                    logger($response);

                    $errorResponse = $response->json();
                    logger($errorResponse);
                    return redirect()->route('customer.notification.index');
                }
            }

            return redirect()->route('customer.notification.index')->with('success', 'Pesan terkirim');
        } else {
            return redirect()->route('customer.notification.index')->with('error', 'Tidak ada data dalam rentang waktu yang dipilih');
        }
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
