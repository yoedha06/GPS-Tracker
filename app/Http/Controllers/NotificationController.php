<?php

namespace App\Http\Controllers;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function index()
    {
        return view('customer.notification.index');
    }

    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        $selectedStartDateTime = $request->scheduled_time;
        $selectedEndDateTime = $request->scheduled_end_time;

        $startDateTime = Carbon::parse($selectedStartDateTime);
        $endDateTime = Carbon::parse($selectedEndDateTime);

        $histories = History::with('device')
            ->whereBetween('date_time', [$startDateTime, $endDateTime])
            ->get();

        // Periksa data dalam range
        if ($histories->isNotEmpty()) {
            $message = "";
            foreach ($histories as $history) {
                $message .= "Data terbaru dari perangkat: {$histories[0]->device->name}\n" ;
                $message .= "Latitude: " . $history->latitude . "\n";
                $message .= "Longitude: " . $history->longitude . "\n";
                $message .= "Plat Nomor: " . $history->device->plat_nomor . "\n";
                $message .= "Waktu: " . $history->date_time . "\n\n";
            }

            $data = [
                'gateway' => '6285954906329',
                'number' => $request->phone,
                'type' => 'text',
                'message' => $message,
            ];

            $response = Http::timeout(60)->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->post($url, $data);

            if ($response->successful()) {
                return to_route('customer.notification.index')->with('success', 'Pesan terkirim');
            } else {
                return to_route('customer.notification.index')->with('error', 'Gagal mengirim pesan');
            }
        } else {
            return to_route('customer.notification.index')->with('error', 'Tidak ada data dalam rentang waktu yang dipilih');
        }
    }
}
