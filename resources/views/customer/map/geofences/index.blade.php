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

        <!-- Include Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            // select 2
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 on the 'geofence' element
                $('#geofence').select2({
                    placeholder: 'Select a name',
                    sorter: function(data) {
                        return data.sort(function(a, b) {
                            return a.text.localeCompare(b.text);
                        });
                    }
                });
            });
            // Map
            document.addEventListener('DOMContentLoaded', function() {

                var map = L.map('map').setView([-6.919876979472255, 107.58628507903803], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var marker; // Variabel global untuk menyimpan marker
                var drawControl; // Variabel global untuk menyimpan kontrol gambar

                map.on('click', function(e) {
                    var selectedType = typeDropdown.value;

                    // Periksa apakah tipe yang dipilih adalah "polygon"
                    if (selectedType === 'polygon') {
                        var latlng = e.latlng;
                        coordinatesInput.value += (coordinatesInput.value ? '; ' : '') + latlng.lat + ', ' +
                            latlng.lng;
                    } else if (selectedType === 'circle') {
                        var latlng = e.latlng;
                        if (marker) {
                            map.removeLayer(marker); // Hapus marker sebelum menambahkan yang baru
                        }
                        marker = L.marker(latlng).addTo(map); // Tambahkan marker baru
                        coordinatesInput.value = latlng.lat + ', ' + latlng.lng;
                    }
                });


                var typeDropdown = document.getElementById('type');
                var radiusInput = document.getElementById('radius');
                var coordinatesInput = document.getElementById('coordinates');

                typeDropdown.addEventListener('change', function() {
                    var selectedType = typeDropdown.value;

                    if (selectedType === 'circle') {
                        radiusInput.required = true;
                        coordinatesInput.readOnly = true;
                        if (marker) {
                            map.removeLayer(marker); // Hapus marker jika tipe diubah menjadi "circle"
                            marker = null; // Atur marker menjadi null
                        }
                        if (drawControl) {
                            map.removeControl(drawControl); // Hapus kontrol gambar jika ada
                            drawControl = null;
                        }
                    } else if (selectedType === 'polygon') {
                        radiusInput.required = false;
                        coordinatesInput.readOnly = true;
                        if (!drawControl) {
                            drawControl = new L.Control.Draw({
                                edit: {
                                    featureGroup: drawnItems,
                                    poly: {
                                        allowIntersection: false
                                    }
                                },
                                draw: {
                                    polygon: {
                                        allowIntersection: false,
                                        showArea: true // Menampilkan area saat polygon digambar
                                    },
                                    polyline: false,
                                    rectangle: false,
                                    circlemarker: false,
                                    circle: false,
                                    marker: true
                                }
                            });
                            map.addControl(drawControl);
                        }
                    }
                });

                var drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);

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

                map.on(L.Draw.Event.CREATED, function(event) {
                    var layer = event.layer;
                    drawnItems.addLayer(layer);
                    layer.enableEdit(); // Aktifkan edit mode pada layer yang baru dibuat
                });

                map.on('draw:created', function(event) {
                    var layer = event.layer;
                    drawnItems.addLayer(layer);

                    // Jika yang digambar adalah poligon, tampilkan area
                    if (layer instanceof L.Polygon) {
                        var area = L.GeometryUtil.geodesicArea(layer.getLatLngs()[
                            0]); // Hitung luas poligon dalam meter persegi
                        var formattedArea = L.GeometryUtil.readableArea(area,
                            true); // Format luas untuk kenyamanan pembacaan
                        // Tampilkan area, misalnya dalam sebuah pop-up
                        layer.bindPopup("Area: " + formattedArea).openPopup();
                    }
                });

                map.on('editable:created', function(event) {
                    var layer = event.layer;
                    layer.enableEdit(); // Aktifkan edit mode pada layer yang baru dibuat
                });

                typeDropdown.dispatchEvent(new Event('change')); // Trigger initial state
            });
        </script>
    @endsection
