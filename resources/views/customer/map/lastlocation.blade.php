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

<div id="main">
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        @foreach($histories as $history)
            var marker = L.marker([{{ $history->latitude }}, {{ $history->longitude }}]).addTo(map);
            marker.bindPopup("<b>{{ $history->title }}</b><br>{{ $history->description }}").openPopup();
        @endforeach

        map.fitBounds(markers.getBounds());

    </script>

</div>
@endsection
