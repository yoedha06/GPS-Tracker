<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
use App\Models\NotificationLogs;
use App\Models\TypeNotif;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $history = History::latest()->limit(10000)->get();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $history
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        // Validasi tambahan untuk memeriksa nilai "none"
        if ($requestData['latitude'] == 'none' || $requestData['longitude'] == 'none' || $requestData['altitude'] == 'none' || $requestData['speeds'] == 'none') {
            return response()->json(['error' => 'Nilai latitude, longitude, dan altitude tidak boleh bernilai none'], 401);
        } elseif ($requestData['latitude'] == null || $requestData['longitude'] == null || $requestData['altitude'] == null || $requestData['speeds'] == null) {
            return response()->json(['error' => 'Nilai latitude, longitude, dan altitude tidak boleh bernilai null'], 402);
        }

        // Validasi input}
        $request->validate([
            'serial_number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'altitude' => 'required',
            'speeds' => 'required',
            'date_time' => 'required|date',
        ]);

        // Setelah validasi, Anda bisa melanjutkan dengan logika asli Anda
        $device = Device::where('serial_number', $request->serial_number)->first();

        if ($device == null) {
            return response()->json([
                'message' => 'Serial number tidak ditemukan',
            ], 404);
        }

        $existingHistory = History::where('device_id', $device->id_device)
            ->where('date_time', $request->date_time)
            ->first();

        if ($existingHistory) {
            if ($existingHistory->device_id == $device->id_device) {
                return response()->json([
                    'message' => 'Data dengan tanggal waktu yang sama sudah ada untuk perangkat yang sama',
                ], 403);
            }
        }

        $date_time = Carbon::parse($request->date_time)->addHours($device->timezone)->format('Y-m-d H:i:s');

        $accuracy = floatval($request->accuracy);
        if ($accuracy <= 999) {
            $accuracy  /= 100;
        }
        $accuracy = number_format($accuracy, 2, '.', '');

        $history = History::create([
            'device_id' => $device->id_device,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            // 'bounds' => $request->bounds,
            'accuracy' => $accuracy,
            'altitude' => floatval($request->altitude),
            'altitude_acuracy' => $request->altitude_acuracy,
            'heading' => floatval($request->heading),
            'speeds' => floatval($request->speeds),
            'date_time' => $date_time,
            'original' => json_encode($request->all())
        ]);

        // kirim ke wa
        $typeNotifications = TypeNotif::all();

        foreach ($typeNotifications as $typeNotification) {
            list($hour, $minute) = explode(':', $typeNotification->time_schedule);
            $scheduledDateTime = Carbon::today()->setHour($hour)->setMinute($minute);

            if ($typeNotification->count > 0) {
                $histories = History::whereHas('device', function ($query) use ($typeNotification) {
                    $query->where('user_id', $typeNotification->user_id);
                })
                    ->where('whatsapp_sent', 'belum terkirim')
                    ->where('date_time', '>', $scheduledDateTime)
                    ->orderBy('date_time')
                    ->limit($typeNotification->count)
                    ->get();

                foreach ($histories as $history) {
                    $message = "";
                    $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);

                    $message .= "Device: " . $history->device->name . "\n";
                    $message .= "Number Plat: " . $history->device->plat_nomor . "\n";
                    $message .= "Address: " . $address . "\n";
                    $message .= "Location: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
                    $message .= "Date Time: " . $history->date_time;

                    $phoneNumber = $history->device->user->phone;

                    $this->sendWhatsapp($typeNotification->user_id, $phoneNumber, $message);

                    $history->update(['whatsapp_sent' => 'terkirim']);

                    $typeNotification->count -= 1;
                    $typeNotification->save();

                    if ($typeNotification->count == 0) {
                        $typeNotification->delete();
                    }
                }
            }
        }
        return response()->json([
            'message' => true,
            'status' => $history,
        ], 201);
    }

    private function sendWhatsapp($userId, $phoneNumber, $message)
    {
        $url = "https://app.japati.id/api/send-message";

        $data = [
            'gateway' => '62895618632347',
            'number' => $phoneNumber, // Menggunakan parameter phone number langsung
            'type' => 'text',
            'message' => $message
        ];

        $response = Http::timeout(60)->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post($url, $data);

        if ($response->ok()) {
            logger($response);
            $errorResponse = $response->json();
            logger($errorResponse);
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
