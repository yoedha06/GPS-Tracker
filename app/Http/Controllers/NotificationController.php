<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        $request->validate([
            'number_phone' => 'required|string',
            'scheduled_time' => 'required|date',
            'scheduled_end_time' => 'required|date|after_or_equal:scheduled_time',
        ]);

        $url = "https://app.japati.id/api/send-message";

        $phoneNumbers = explode(';', $request->number_phone);
        $startDateTime = Carbon::parse($request->scheduled_time);
        $endDateTime = Carbon::parse($request->scheduled_end_time);

        session([
            'number_phone' => $request->number_phone,
            'device' => $request->device,
            'scheduled_time' => $request->scheduled_time,
            'scheduled_end_time' => $request->scheduled_end_time
        ]);

        $histories = History::with('device')
            ->where('device_id', $request->device)
            ->whereBetween('date_time', [$startDateTime, $endDateTime])
            ->get();

        if ($histories->isEmpty()) {
            return redirect()->route('customer.notification.index')->with('error', 'There is no data in the selected time range');
        }

        $message = $this->sendWhattsapp($histories);

        $errors = [];

        foreach ($phoneNumbers as $phoneNumber) {
            $data = [
                'gateway' => '62895618632347',
                'number' => trim($phoneNumber),
                'type' => 'text',
                'message' => $message
            ];

            $response = Http::timeout(60)
                ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, $data);

            if (!$response->ok()) {
                logger()->error('Failed to send WhatsApp message to ' . $phoneNumber);
                $errors[] = 'Failed to send data to ' . $phoneNumber;
            }
        }

        if (empty($errors)) {
            logger()->info('WhatsApp messages sent successfully');
            return redirect()->route('customer.notification.index')->with('success', 'Send successfully');
        } else {
            return redirect()->route('customer.notification.index')->with('error', implode(', ', $errors));
        }
    }

    private function sendWhattsapp($histories)
    {
        $message = "";

        foreach ($histories as $history) {
            $address = $this->getAddressFromCoordinates($history->latitude, $history->longitude);

            $message .= "Device: {$history->device->name}\n";
            $message .= "Address: " . $address . "\n";
            $message .= "Location: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
            $message .= "Plat Nomor: " . $history->device->plat_nomor . "\n";
            $message .= "Waktu: " . $history->date_time . "\n\n";
        }

        return $message;
    }


    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?lat={$latitude}&lon={$longitude}&format=json";
        $response = Http::get($url);
        $data = $response->json();

        return $data['display_name'] ?? "Alamat tidak ditemukan";
    }
}

    // public function notificationtype(Request $request)
    // {
    //     $url = "https://app.japati.id/api/send-message";

    //     $user = auth()->user();

    //     if ($request->notificationType === 'lt8' && Carbon::now()->format('H') > 8) {
            
    //         $sendData = History::where('date_time', '>', Carbon::now()->startOfDay()->addHours(8))
    //             ->where('date_time', '<', Carbon::now())
    //             ->orderBy('date_time', 'asc')
    //             ->first();

    //         if ($sendData) {
    //             $notificationType = TypeNotif::where('user_id', $user->id)
    //                 ->where('notification_type', 2)
    //                 ->first();

    //             if ($notificationType) {
    //                 $message = "New data received:\n";
    //                 $message .= "Date Time: " . $sendData->date_time . "\n";

    //                 $phoneNumber = $user->phone;

    //                 $data = [
    //                     'gateway' => '62895618632347',
    //                     'number' => $phoneNumber,
    //                     'type' => 'text',
    //                     'message' => $message
    //                 ];

    //                 $response = Http::timeout(60)
    //                     ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
    //                     ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
    //                     ->post($url, $data);

    //                 if ($response->ok()) {
    //                     $sendData->update(['whatsapp_sent' => 'terkirim']);
    //                     Log::info($response);

    //                 return response()->json([
    //                     'success' => true,
    //                     'msg' => 'Data successfully sent',
    //                 ]);
    //             }
    //         }
    //     } 
    //     elseif ($request->notificationType === '5d') {
    //         $hours = [7, 10, 13, 16, 19];
        
    //         foreach ($hours as $hour) {
    //             $time = Carbon::now()->startOfDay()->addHours($hour);
    //             $sendData = History::where('date_time', '>=', $time)->first();
        
    //             if ($sendData) {
    //                 $notificationType = TypeNotif::where('user_id', $user->id)
    //                     ->where('notification_type', 1)
    //                     ->first();
        
    //                 if ($notificationType) {
    //                     $message = "New data received:\n";
    //                     $message .= "Date Time: " . $sendData->date_time . "\n";
        
    //                     $phoneNumber = $user->phone;
    //                     $postData = [
    //                         'gateway' => '6285954906329',
    //                         'number' => $phoneNumber,
    //                         'type' => 'text',
    //                         'message' => $message
    //                     ];
        
    //                     $response = Http::timeout(60)
    //                         ->withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
    //                         ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
    //                         ->post($url, $postData);
        
    //                     if ($response->ok()) {
    //                         $sendData->update(['whatsapp_sent' => 'terkirim']);
    //                         Log::info($response);
    //                     }
    //                 }
    //             } else {
    //                 continue;
    //             }
    //         }
        
    //         return response()->json([
    //             'success' => true,
    //             'msg' => 'Data successfully sent',
    //         ]);
    //     } 
    //     // elseif( $request->notificationType === 'itv') {

    //     //     $user = auth()->user();

    //     //     $typeNotification = TypeNotif::where('notification_type', 3)->first();

    //     //     if ($typeNotification && $typeNotification->custom_interval_hours == '1jam') 
    //     //     {
    //     //         $startTime = History::whereDate('date_time', Carbon::today())->min('date_time');

    //     //         if ($startTime) {
            
    //     //             $interval = CarbonInterval::hour();
            
    //     //             $currentTime = Carbon::parse($startTime);
            
    //     //             while ($currentTime <= Carbon::now()) {
    //     //                 $data = History::where('date_time', '>=', $currentTime)
    //     //                                ->where('date_time', '<', $currentTime->copy()->add($interval))
    //     //                                ->get();
    //     //             }
    //     //         }

    //     //     }
    //     // }
    // }