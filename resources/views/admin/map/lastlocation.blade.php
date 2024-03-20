@extends('layouts.admin')

<title>GEEX - Last Location</title>

@extends('layouts.navbaradmin')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

<style>
    #map {
        height: 67%;
        margin-top: 10px;
        border-radius: 7px;
    }
</style>

@section('content')
    <header>
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>


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


        <div id="map"></div>

        <!-- Include Leaflet and Select2 JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Include Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('#user_device').select2();

                function refreshPage() {
                    location.reload(true); // Menggunakan parameter true untuk melakukan pengambilan ulang dari server
                }
                $('#refreshButton').on('click', function() {
                    refreshPage();
                });
                var map = L.map('map').setView([0, 0], 2);
                var userMarker;

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Function to update the map based on selected device
                function updateMap(deviceId) {
                    console.log('Selected Device ID:', deviceId);
                    // Clear existing markers on the map
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            map.removeLayer(layer);
                        }
                    });

                    if (deviceId) {
                        $.ajax({
                            url: '/get-device-history/' + deviceId,
                            method: 'GET',
                            success: function(data) {
                                console.log('Data from AJAX:', data);
                                // Add new markers based on the fetched history data
                                data.forEach(function(history) {
                                    console.log('Processing History:', history);
                                    var device = history.device;
                                    var marker = L.marker([history.latitude, history.longitude])
                                        .addTo(map);
                                    var popupContent =
                                        "<center><b style='margin-top: 5px;'>Device:</b> " + history
                                        .device.name + "</center>" +
                                        "<b>Name cust:</b> " + history.device.user.name + "<br>" +
                                        "<b>Latlng:</b> " + history.latitude + ',' + history
                                        .longitude + "<br>" +
                                        "<b>PlatNo:</b> " + history.device.plat_nomor + "<br>" +
                                        "<b>Date Time:</b> " + history.date_time + "<br>" +
                                        "<img src='{{ asset('storage/') }}/" + history.device
                                        .photo + "' style='width: 199px; height: 127px;' >";
                                    console.log('Image Source:', history.device.photo);


                                    marker.bindPopup(popupContent)
                                        .openPopup(); // Open the pop-up by default
                                });

                                // Fit the map bounds to the new markers
                                var bounds = L.latLngBounds(data.map(function(history) {
                                    return [history.latitude, history.longitude];
                                }));

                                // Zoom animation to the center of the bounds
                                map.flyTo(bounds.getCenter(), 18, {
                                    animate: true,
                                    duration: 2, // Adjust the duration of the animation (in seconds)
                                    // easeLinearity: 16 // Adjust the easeLinearity for a smoother animation
                                });

                            },
                            error: function(error) {
                                console.error('Error fetching device history:', error);
                            }
                        });
                    }
                }

                $('#user_device').on('select2:select', function(e) {
                    var deviceId = e.params.data.id; // Mendapatkan ID perangkat yang dipilih
                    console.log('Selected Device ID:', deviceId);
                    updateMap(deviceId);
                });
                $('#myLocationButton').click(function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            console.log('Location:', latitude, longitude);

                            var customIcon = L.icon({
                                iconUrl: '/images/mapgreen.png',
                                iconSize: [42, 42],
                                iconAnchor: [20, 44],
                                popupAnchor: [1, -41]
                            });

                            var popupContent = `<center><b>Lokasi Anda</b></center><br>` +
                                `${latitude},${longitude}`;

                            // Menghapus marker sebelumnya jika ada
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
                // Initial map setup
                @foreach ($devices as $device)
                    @if (
                        $device->latestHistory &&
                            $device->latestHistory->latitude !== null &&
                            $device->latestHistory->longitude !== null &&
                            $device->user)
                        var marker = L.marker([{{ $device->latestHistory->latitude }},
                                {{ $device->latestHistory->longitude }}
                            ])
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
