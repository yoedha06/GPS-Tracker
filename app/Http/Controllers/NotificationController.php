<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use App\Models\NotificationLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

                $message .= "New Data from Device: {$histories[0]->device->name}\n";
                $message .= "Address: " . $address . "\n";
                $message .= "Location: https://www.google.com/maps?q={$history->latitude},{$history->longitude}\n";
                $message .= "Plat Nomor: " . $history->device->plat_nomor . "\n";
                $message .= "Waktu: " . $history->date_time . "\n\n";

                $data = [
                    'gateway' => '6285954906329',
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

    public function NotificationAuto(Request $request)
    {
        $request->validate([
            'notification_type' => 'required|in:1,2,3',
        ]);

        if ($request->notification_type == 1) {
            return("belum disetting");
        }

        if ($request->notification_type == 2) {
            $currentTime = Carbon::now();

            session()->put('notification_selected_time', $currentTime);

            if ($currentTime->hour < 8) {
                return redirect()->back()->with('info', 'Please wait until after 8 AM for data to be sent.');
            } else {

                $this->sendDataAfter8AM();

                return redirect()->back()->with('successs', 'Data sent successfully');
            }
        }

        if ($request->notification_type == 3) {
            return("belum disetting");
        }
    }

    public function sendDataAfter8AM()
    {
        $url = "https://app.japati.id/api/send-message";

        $user = auth()->user();

        $sendData = History::where('date_time', '>', Carbon::now()->startOfDay()->addHours(8))->orderby('date_time', 'asc')->first();

        if ($sendData) {
            $message = "New data received:\n";
            $message .= "Date Time: " . $sendData->date_time . "\n";

            $phoneNumber = $user->phone;

            $data = [
                'gateway' => '6285954906329',
                'number' => $phoneNumber,
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
    }
}
