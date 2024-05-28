<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
use App\Models\NotificationLogs;
use App\Models\Pengaturan;
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
        $history = History::latest()->limit(100)->get();
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

        // Kirim WA sesuai kriteria
        $typeNotifications = TypeNotif::all();

        foreach ($typeNotifications as $typeNotification) {
            list($hour, $minute) = explode(':', $typeNotification->time_schedule);
            $scheduledDateTime = Carbon::today()->setHour($hour)->setMinute($minute);

            $histories = History::whereHas('device', function ($query) use ($typeNotification) {
                $query->where('user_id', $typeNotification->user_id);
            })
                ->where('whatsapp_sent', 'belum terkirim')
                ->where('date_time', '>', $scheduledDateTime)
                ->orderBy('date_time')
                ->get();

            // Hitung jumlah data yang diproses
            $countProcessed = $histories->count();

            // Jika jumlah data yang diproses sama dengan atau melebihi count yang ditentukan oleh user
            if ($countProcessed >= $typeNotification->count) {
                // Tandai semua data yang memenuhi kriteria sebagai "proses"
                foreach ($histories as $history) {
                    $history->update(['whatsapp_sent' => 'proses']);
                }

                // Kirimkan pesan WhatsApp untuk setiap data yang ditandai sebagai "proses"
                foreach ($histories as $history) {
                    $message = "";
                    $address = $this->getAddress($history->latitude, $history->longitude);

                    $message .= "Device: " . $history->device->name . "\n";
                    $message .= "Nomor Plat: " . $history->device->plat_nomor . "\n";
                    $message .= "Alamat: " . $address . "\n";
                    $message .= "Lokasi: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
                    $message .= "Tanggal Waktu: " . $history->date_time;

                    // Kirim pesan WhatsApp
                    $phoneNumbers = explode(';', $typeNotification->phone_number);
                    $phoneNumbers = array_map('trim', $phoneNumbers);

                    foreach ($phoneNumbers as $phoneNumber) {
                        $this->sendWhatsapp($typeNotification->user_id, $phoneNumber, $message);
                    }

                    // Update status pengiriman WhatsApp
                    $history->update(['whatsapp_sent' => 'terkirim']);
                }
            }
        }

        return response()->json([
            'message' => true,
            'status' => 'Data has been processed and WhatsApp messages sent.',
        ], 201);
    }

    private function sendWhatsapp($userId, $phoneNumber, $message)
    {
        $url = "https://app.japati.id/api/send-message";

        $data = [
            'gateway' => '62895618632347',
            'number' => $phoneNumber,
            'type' => 'text',
            'message' => $message
        ];

        $api = $this->getApiToken();

        $response = Http::timeout(60)->withToken($api)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post($url, $data);

        if ($response->ok()) {
            logger()->info('WhatsApp message sent successfully');
        } else {
            logger()->error('Failed to send WhatsApp message');
        }
    }

    private function getApiToken()
    {
        $api = DB::table('pengaturan')->value('api_token');
        return $api;
    }

    private function getAddress($latitude, $longitude)
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
