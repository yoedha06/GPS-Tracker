@extends('layouts.customer')

@section('content')

    <head>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <title>Geofence Index</title>
    </head>

    <style>
        #map {
            width: 80%;
            height: 70%;
            border-radius: 7px;
            z-index: 1;
            margin-left: 20%
        }

        @media (max-width: 767px) {
            #map {
                height: 70%;
                border-radius: 7px;
                z-index: 1;
            }
        }
    </style>

    <body>

        <!-- Form for creating a new entry -->
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="padding-left: 18%">
                    <h3 class="mt-4">Geofences</h3>
                    <div class="mt-4">
                        <div class="form-group">
                            <label for="geofence">Data:</label>
                            <select name="geofence" id="geofence" class="geofence form-control" style="width: 100%;">
                                <option value="" disabled selected>Select</option>
                                @foreach ($geofences as $geofence)
                                    <option value="{{ $geofence->name }}">{{ $geofence->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('customer.geofences.create') }}" class="btn btn-primary">Create</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- index --}}
        <div class="map-container" style="margin-top:10px;">
            <div id="validationMessage" style="display: none; color: red; margin-top: 10px;"></div>
            <div id="map" style="height: 630px; border-radius:15px;"></div>
        </div>

        {{-- link for leaflet --}}
        <script src="https://unpkg.com/leaflet/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
        <script src="https://unpkg.com/leaflet.animatedmarker/src/AnimatedMarker.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-editable/1.2.0/Leaflet.Editable.min.js"></script>

        <script>
            // Map
            document.addEventListener('DOMContentLoaded', function() {

                var map = L.map('map').setView([-6.919876979472255, 107.58628507903803], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var geofencesData = {!! json_encode($geofences) !!};
                geofencesData.forEach(function(geofence) {
                    if (geofence.type === 'circle') {
                        var coordinates = geofence.coordinates.split(',').map(function(coord) {
                            return parseFloat(coord.trim());
                        });
                        var circle = L.circle(coordinates, {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                            radius: parseFloat(geofence.radius)
                        }).addTo(map);
                    } else if (geofence.type === 'polygon') {
                        var polygonCoordinates = geofence.coordinates.split(';').map(function(coordPair) {
                            var coords = coordPair.split(',').map(function(coord) {
                                return parseFloat(coord.trim());
                            });
                            return coords;
                        });
                        var polygon = L.polygon(polygonCoordinates, {
                            color: 'blue',
                            fillColor: '#00f',
                            fillOpacity: 0.5
                        }).addTo(map);
                    }
                });
            });
        </script>
    @endsection
