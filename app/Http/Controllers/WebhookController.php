<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        $url = "https://app.japati.id/api/send-message";

        $data = [
            'message' => 'message',
            'bufferImage' => 'base64 image, null if message not contain image',
            'from' => '6281312634776',
            'gateway' => '6285954906329',
        ];

        Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')->post($url, $data);
    }
}
