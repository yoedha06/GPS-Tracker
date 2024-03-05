@extends('layouts.admin')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<style>
    #map { 
        height: 90%; 
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

        @foreach($devices as $device)
            @if($device->latestHistory && $device->user)
                var marker = L.marker([{{ $device->latestHistory->latitude }}, {{ $device->latestHistory->longitude }}]).addTo(map);
                var popupContent = "<b>Customer:</b> {{ $device->user->name }}<br>" +
                                   "<b>Device:</b> {{ $device->name }}<br>" +
                                   "<b>Latitude:</b> {{ $device->latestHistory->latitude . ',' . $device->latestHistory->longitude   }}<br>" +
                                   "<b>Plat Nomor:</b> {{ $device->plat_nomor }}<br>" +
                                   "<b>Date Time:</b> {{ $device->latestHistory->date_time }}<br>";
                                   
                marker.bindPopup(popupContent);
            @endif
        @endforeach

        var bounds = L.latLngBounds([
            @foreach($devices as $device)
            [{{ $device->latestHistory->latitude }}, {{ $device->latestHistory->longitude }}],
            @endforeach
        ]);
        map.fitBounds(bounds);
    </script>
</div>
@endsection
