@extends('layouts.customer')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<style>
    #map { 
        height: 500px; 
    }
</style>

@section('content')
<header>
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>


<div id="main">
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([0, 0], 2);
    
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
    
        // Tambahkan marker untuk setiap posisi terbaru dari setiap perangkat
        @foreach($latestHistories as $history)
            var marker = L.marker([{{ $history->latitude }}, {{ $history->longitude }}]).addTo(map);
            marker.bindPopup('<b>Nama Device:</b> {{ $history->device->name }}<br><b>Latitude:</b> {{ $history->latitude }}<br><b>Longitude:</b> {{ $history->longitude }}<br><b>Plat Nomor:</b> {{ $history->device->plat_nomor }}<br><b>Date Time:</b> {{ $history->date_time }}');
        @endforeach
    
        // Sesuaikan tampilan peta agar mencakup semua marker
        var bounds = L.latLngBounds([
            @foreach($latestHistories as $history)
                [{{ $history->latitude }}, {{ $history->longitude }}],
            @endforeach
        ]);
        map.fitBounds(bounds);
    </script>
    
</div>
@endsection
