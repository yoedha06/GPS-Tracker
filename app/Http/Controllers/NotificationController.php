<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use App\Models\NotificationLogs;
use App\Models\TypeNotif;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        $devices = Device::where('user_id', auth()->user()->id)->get();
        $today = Carbon::today();
        $logs = DB::table('history')
            ->where('user_id', auth()->user()->id)
            ->join('device', 'history.device_id', '=', 'device.id_device')
            ->select('history.*', 'device.name as device_name')
            ->orderby('date_time', 'desc')
            ->whereDate('date_time', $today)
            ->get();

        return view('customer.notification.index', ['devices' => $devices, 'logs' => $logs]);
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

        if ($histories->isNotEmpty()) {
            $message = "";
            foreach ($histories as $history) {
                $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);
                // // Mendapatkan URL foto perangkat
                // $photoUrl = asset('storage/' . $device->photo); // Sesuaikan dengan lokasi penyimpanan foto perangkat
                // // $photoUrl = "https://files.f-g.my.id/images/dummy/buku-2.jpg";

                $message .= "Device: {$histories[0]->device->name}\n";
                $message .= "Address: " . $address . "\n";
                $message .= "Location: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
                $message .= "Plat Nomor: " . $history->device->plat_nomor . "\n";
                $message .= "Waktu: " . $history->date_time . "\n\n";

                $data = [
                    'gateway' => '62895618632347',
                    'number' => $request->phone,
                    'type' => 'text',
                    'message' => $message
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

    public function notificationtype(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        $user = auth()->user();

        if ($request->notificationType === 'lt8' && Carbon::now()->format('H') > 8) {
            
            $sendData = History::where('date_time', '>', Carbon::now()->startOfDay()->addHours(8))
                ->where('date_time', '<', Carbon::now())
                ->orderBy('date_time', 'asc')
                ->first();

            if ($sendData) {
                $notificationType = TypeNotif::where('user_id', $user->id)
                    ->where('notification_type', 2)
                    ->first();

                if ($notificationType) {
                    $message = "New data received:\n";
                    $message .= "Date Time: " . $sendData->date_time . "\n";

                    $phoneNumber = $user->phone;

                    $data = [
                        'gateway' => '62895618632347',
                        'number' => $phoneNumber,
                        'type' => 'text',
                        'message' => $message
                    ];

                    $response = Http::timeout(60)
                        ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                        ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                        ->post($url, $data);

                    if ($response->ok()) {
                        $sendData->update(['whatsapp_sent' => 'terkirim']);
                        Log::info($response);
                    }
<<<<<<< HEAD

                    return response()->json([
                        'success' => true,
                        'msg' => 'Data successfully sent',
                    ]);
=======
>>>>>>> 43daf75b650997dd58619bd59df9db74d7d3989b
                }
            }
        } 
        elseif ($request->notificationType === '5d') {
            $hours = [7, 10, 13, 16, 19];
        
            foreach ($hours as $hour) {
                $time = Carbon::now()->startOfDay()->addHours($hour);
                $sendData = History::where('date_time', '>=', $time)->first();
        
                if ($sendData) {
                    $notificationType = TypeNotif::where('user_id', $user->id)
                        ->where('notification_type', 1)
                        ->first();
        
                    if ($notificationType) {
                        $message = "New data received:\n";
                        $message .= "Date Time: " . $sendData->date_time . "\n";
        
                        $phoneNumber = $user->phone;
                        $postData = [
                            'gateway' => '6285954906329',
                            'number' => $phoneNumber,
                            'type' => 'text',
                            'message' => $message
                        ];
        
                        $response = Http::timeout(60)
                            ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                            ->post($url, $postData);
        
                        if ($response->ok()) {
                            $sendData->update(['whatsapp_sent' => 'terkirim']);
                            Log::info($response);
                        }
                    }
                } else {
                    continue;
                }
            }
        
            return response()->json([
                'success' => true,
                'msg' => 'Data successfully sent',
            ]);
        } 
        // elseif( $request->notificationType === 'itv') {

        //     $user = auth()->user();

        //     $typeNotification = TypeNotif::where('notification_type', 3)->first();

        //     if ($typeNotification && $typeNotification->custom_interval_hours == '1jam') 
        //     {
        //         $startTime = History::whereDate('date_time', Carbon::today())->min('date_time');

        //         if ($startTime) {
            
        //             $interval = CarbonInterval::hour();
            
        //             $currentTime = Carbon::parse($startTime);
            
        //             while ($currentTime <= Carbon::now()) {
        //                 $data = History::where('date_time', '>=', $currentTime)
        //                                ->where('date_time', '<', $currentTime->copy()->add($interval))
        //                                ->get();
        //             }
        //         }

        //     }
        // }
    }
}