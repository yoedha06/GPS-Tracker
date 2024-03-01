@extends('layouts.customer')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    #map {
        height: 50vh;
        width: 100%;
    }
</style>
@section('content')
    <div id="main">
        <div id="map"></div>
    </div>
    <script>
        var map = L.map('map', {
            center: [-6.8955992330108895, 107.54240919668543],
            zoom: 13
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var historyData = @json($history);

        var layerGroup = L.layerGroup().addTo(map);
        var polylinePoints = [];

        for (var i = 0; i < historyData.length; i++) {
            var latlngStr = historyData[i].latlng;
            var latlngArr = latlngStr.split(", ");
            var lat = parseFloat(latlngArr[0]);
            var lng = parseFloat(latlngArr[1]);
            var speed = parseFloat(historyData[i].speeds);
            var accuracy = parseFloat(historyData[i].accuracy);

            var color;
            if (speed < 20) {
                color = 'green';
            } else if (speed >= 20 && speed <= 40) {
                color = 'yellow';
            } else {
                color = 'red';
            }

            var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m";

            var circleMarker = L.circleMarker([lat, lng], {
                radius: 10,
                stroke: false,
                color: color,
                fillOpacity: 1
            }).bindPopup(popupContent).addTo(layerGroup);

            polylinePoints.push([lat, lng]);

            // Adding a Polyline connecting the circle markers
            var polyline = L.polyline(polylinePoints, {
                color: color,
                weight: 5,
                opacity: 5
            }).addTo(layerGroup);
        }


        // Zoom ke lokasi marker pertama
        if (historyData.length > 0) {
            map.setView([historyData[0].latlng.split(',')[0], historyData[0].latlng.split(',')[1]], 15);
        }

        layerGroup.addTo(map);
    </script>
@endsection
