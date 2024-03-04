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
    <select id="selectDevice" class="form-select" aria-label="Select Device">
        <option value="" selected>Select Device</option>
        @foreach ($devices as $device)
            <option value="{{ $device->id_device }}">{{ $device->name }}</option>
        @endforeach
    </select>
    <br>
    <div id="map"></div>
</div>

<script>
    var map = L.map('map').setView([-2.548926, 118.0148634], 5);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var locations = @json($locations);
    console.log(locations);

    locations.forEach(function(location) {
        var latlngObj = JSON.parse(location.latlng);
        L.marker([latlngObj.lat, latlngObj.lng]).addTo(map)
            .bindPopup("Device ID: " + location.device_id);
    });
</script>

@endsection
