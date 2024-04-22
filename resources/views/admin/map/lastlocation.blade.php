@extends('layouts.admin')

<title>GEEX - Last Location</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

<style>
    #map {
        width: 100%;
        height: 70%;
        border-radius: 7px;
    }
</style>

@section('content')
    <div id="main" style="margin-top:-20px;">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-end float-lg-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-map-marker-alt"></i> LastLocation
                </li>
            </ol>
        </nav>
        <div class="form-group mb-3">
            <select id="user_device" class="form-select input">
                <option value="" disabled selected>Select Device User</option>
                @foreach ($users as $user)
                    @foreach ($user->devices as $device)
                        @if ($device->latestHistory && $device->user)
                            <option value="{{ $device->id_device }}" data-device-id="{{ $device->id_device }}">
                                {{ $user->name }} - {{ $device->name }}
                            </option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <button id="refreshButton" class="btn btn-primary"><i class="bi bi-people"></i> See All </button>
        <button type="submit" id="myLocationButton" class="btn btn-success"><i class="bi bi-compass-fill"></i>&nbsp;Lihat
            lokasi saya</button>
        <div id="alertMessage" class="alert alert-success alert-dismissible fade show mt-1" role="alert"
            style="display: none;">
            <span id="alertText">Ini adalah pesan alert.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <div id="map" style="margin-top:10px;"></div>

        <!-- Include Leaflet and Select2 JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                var map = L.map('map').setView([0, 0], 2);
                var userMarker;
                var lastLocation = null;
                var lastLocationMarker = null;
                var latestLocationMarker = null;
                var pathLocations = [];
                var polyline = null;
                var markers = [];

                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<i class='fas fa-map-marker-alt' style='color: #25A5E2; font-size: 40px;'></i>",
                    iconSize: [42, 49],
                    iconAnchor: [20, 44],
                    popupAnchor: [-5, -41]
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                $('#user_device').select2();
                $('#user_device').on('select2:select', function(e) {
                    var selectedDeviceId = e.params.data.id; // Mendapatkan ID perangkat yang dipilih
                    console.log('Device ID:', selectedDeviceId);
                    loadLastLocation(selectedDeviceId);
                });

                function loadLastLocation(selectedDeviceId) {
                    markers.forEach(function(marker) {
                        map.removeLayer(marker);
                    });
                    markers = [];

                    $.ajax({
                        url: '/lastlocation/' + selectedDeviceId,
                        method: 'GET',
                        success: function(data) {
                            $(".pulse").removeClass("pulse");
                            console.log('lokasi terakhir:', data);

                            lastLocation = data;

                            // Hapus marker last location sebelumnya (jika ada)
                            if (lastLocationMarker) {
                                map.removeLayer(lastLocationMarker);
                            }

                            // Tambahkan marker untuk last location
                            lastLocationMarker = L.marker([data.latitude, data.longitude], {
                                icon: customIcon
                            }).addTo(map);

                            var popupContent = `<center><b>Last location</b></center><br>` +
                                `<b>Device: ${data.name}</b><br>` +
                                `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                `<b>Date Time:</b> ${data.date_time}<br>` +
                                `<img src="{{ asset('storage') }}/${data.photo}" style="width: 199px; height: 127px;">`;

                            lastLocationMarker.bindPopup(popupContent).openPopup();
                            markers.push(lastLocationMarker);

                            map.setView([data.latitude, data.longitude], 20);
                        },
                        error: function(error) {
                            var alertText = 'Device yang dipilih tidak memiliki history';
                            $('#alertText').text(alertText);
                            var alertMessage = $('#alertMessage');
                            alertMessage.removeClass('alert-danger alert-success').addClass('alert-danger');
                            alertMessage.show();
                        }
                    });
                }

                function addPolyline() {
                    if (polyline !== null) {
                        map.removeLayer(polyline);
                    }
                    polyline = L.polyline(pathLocations, {
                        color: 'blue'
                    }).addTo(map);
                }

                var intervalId = setInterval(function() {
                    loadLatestLocation(); // Panggil loadLatestLocation secara periodik
                }, 5000); // Set interval sesuai kebutuhan

                function loadLatestLocation() {
                    var selectedDeviceId = $('#user_device').val();

                    $.ajax({
                        url: '/admin/latestlocation/' + selectedDeviceId,
                        method: 'GET',
                        success: function(data) {
                            console.log('lokasi baru:', data);

                            if (lastLocation && data.date_time !== lastLocation.date_time) {
                                lastTimestamp = data.date_time;

                                var lastLocationCoordinates = [lastLocation.latitude, lastLocation
                                    .longitude];
                                var latestLocationCoordinates = [data.latitude, data.longitude];

                                // Menambahkan koordinat last location ke pathLocations jika belum ada
                                if (pathLocations.length === 0) {
                                    pathLocations.push(lastLocationCoordinates);
                                }

                                // Menambahkan koordinat latest location ke pathLocations
                                pathLocations.push(latestLocationCoordinates);

                                lastLocation = data;

                                // Menghapus marker latest location yang sebelumnya (jika ada)
                                if (latestLocationMarker) {
                                    map.removeLayer(latestLocationMarker);
                                }

                                // Tambahkan marker baru untuk latest location dengan warna kuning
                                var yellowIcon = L.divIcon({
                                    className: 'custom-div-icon',
                                    html: "<i class='fas fa-map-marker-alt' style='color:yellow; font-size: 40px;'></i>",
                                    iconSize: [42, 49],
                                    iconAnchor: [16, 45],
                                    popupAnchor: [-5, -41]
                                });

                                latestLocationMarker = L.marker(
                                    latestLocationCoordinates, {
                                        icon: yellowIcon
                                    }
                                ).addTo(map);

                                var popupContent =
                                    `<center><b style="color: yellow; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;" >Latest location</b></center><br>` +
                                    `<b>Device: ${data.name}</b><br>` +
                                    `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                    `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                    `<b>Date Time:</b> ${data.date_time}<br>` +
                                    `<img src="{{ asset('storage') }}/${data.photo}" style="width: 199px; height: 127px;">`;

                                latestLocationMarker.bindPopup(popupContent).openPopup();

                                addPolyline(); // Menambahkan polyline dari lastLocation ke latestLocation

                                map.setView(latestLocationCoordinates, 25, {
                                    maxZoom: 18
                                });
                            } else {
                                var alertText = 'Tidak ada data terbaru.';
                                $('#alertText').text(alertText);
                                var alertMessage = $('#alertMessage');
                                alertMessage.removeClass('alert-danger alert-success').addClass(
                                    'alert-danger');
                                alertMessage.show();
                            }
                        },
                        error: function(error) {
                            console.error('Error fetching latest location:', error);
                        }
                    });
                }

                $('#refreshButton').on('click', function() {
                    location.reload();
                });

                $('#myLocationButton').click(function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            console.log('Current Location:', latitude, longitude);

                            var customIcon = L.divIcon({
                                className: 'custom-div-icon',
                                html: "<i class='fas fa-map-marker-alt' style='color: green; font-size: 40px;'></i>",
                                iconSize: [42, 49],
                                iconAnchor: [20, 44],
                                popupAnchor: [-5, -41]
                            });

                            var popupContent = `<center><b> Lokasi Anda </b></center><br>` +
                                `${latitude},${longitude}`;

                            if (userMarker) {
                                map.removeLayer(userMarker);
                            }

                            userMarker = L.marker([latitude, longitude], {
                                icon: customIcon
                            }).addTo(map);
                            userMarker.bindPopup(popupContent).openPopup();
                            map.setView([latitude, longitude], 17);

                            var alertText = 'Lokasi Anda berhasil ditampilkan pada peta.';
                            $('#alertText').text(alertText);
                            var alertMessage = $('#alertMessage');
                            alertMessage.removeClass('alert-danger alert-primary').addClass(
                                'alert-success');
                            alertMessage.show();
                        }, function(error) {
                            console.error('Error getting user location:', error);
                            var alertMessage = $('#alertMessage');
                            alertMessage.html('Tidak dapat menampilkan lokasi Anda pada peta.');
                            alertMessage.removeClass('alert-success').addClass('alert-danger');
                            alertMessage.show();
                        });
                    } else {
                        console.error('Geolocation is not supported by this browser.');
                        var alertMessage = $('#alertMessage');
                        alertMessage.html('Geolocation tidak didukung oleh browser ini.');
                        alertMessage.removeClass('alert-success').addClass('alert-danger');
                        alertMessage.show();
                    }
                });

                @foreach ($devices as $device)
                    @if (
                        $device->latestHistory &&
                            $device->latestHistory->latitude !== null &&
                            $device->latestHistory->longitude !== null &&
                            $device->user)
                        var marker = L.marker([{{ $device->latestHistory->latitude }},
                                {{ $device->latestHistory->longitude }}
                            ], {
                                icon: customIcon
                            })
                            .addTo(map);
                        var popupContent =
                            "<center><b style='margin-top: 5px;'>Device:</b> {{ $device->name }}</center>" +
                            "<b>Name cust:</b> {{ $device->user->name }}<br>" +
                            "<b>Latlng:</b> {{ $device->latestHistory->latitude . ',' . $device->latestHistory->longitude }}<br>" +
                            "<b>PlatNo:</b> {{ $device->plat_nomor }}<br>" +
                            "<b>Date Time:</b> {{ $device->latestHistory->date_time }}<br>" +
                            "<img src='{{ asset('storage/' . $device->photo) }}' style='width: 199px; height: 127px;' >";

                        marker.bindPopup(popupContent);
                    @endif
                @endforeach

                var bounds = L.latLngBounds([
                    @foreach ($devices as $device)
                        @if ($device->latestHistory)
                            [{{ $device->latestHistory->latitude }},
                                {{ $device->latestHistory->longitude }}
                            ],
                        @endif
                    @endforeach
                ]);
                map.fitBounds(bounds);
            });
        </script>
        
    </div>
@endsection
