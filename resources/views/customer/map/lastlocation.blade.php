@extends('layouts.customer')

<title>GEEX - LastLocation</title>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    #map {
        width: 100%;
        height: 70%; 
        border-radius: 7px;
    }
    @media (max-width: 767px) {
        #map {
            height: 60%;
        }
    }
    @keyframes pulse {
        0% {
            transform: scale(0.9);
            opacity: 1;
        }
        70% {
            transform: scale(1);
            opacity: 0.3;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }
    .pulse {
        border-radius: 50%;
        height: 30px;
        width: 30px;
        position: absolute;
        border: 2px solid #007bff;
        animation: pulse 1s infinite;
    }
    #updateLocationButton {
        display: none; 
    }
    .navbarend {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            height: 67px;
            width: 375px;
            left: 50%;
            height: 72px; 
            padding-top: 7px; 
            padding-bottom: 7px;
            padding-right: 7px;
            padding-left: 7px;
            position: fixed;
            transform: translate(-50%);
            bottom: 0;
            box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: #000000;
            text-decoration: none;
            font-size: 17px;
            font-weight: normal;
            /* Memperbesar ukuran fontsizenya */
            flex: 1;
            /* Menyesuaikan ruang setiap item */
        }

        .nav-item span {
            margin-top: 5px;
            font-weight: normal;
            /* Memberikan margin atas pada span */
        }

        .nav-item img {
            width: 40px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            /* Menambahkan border radius */
            margin-bottom: 5px;
        }

        .logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            /* Menambahkan border radius */
        }

        .name {
            max-width: 100px;
            /* Sesuaikan dengan lebar maksimum yang Anda inginkan */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .name h6 {
            font-weight: normal;
            /* Menghilangkan efek tebal pada teks */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
</style>

@section('content')
{{-- <header>
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header> --}}
    <div id="main" style="padding-top: 4px; padding-right: 10px; padding-left: 10px;">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end" style="padding-left: -50px;
                        margin-top: 1px;
                        margin-bottom: -45px;">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/customer"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-geo-alt-fill"></i> Last
                                    Location</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <form class="mt-2">
                        <div class="mb-3" style="margin-top: -8px;">
                            <select id="selectDevice" class="form-select" aria-label="Select Device">
                                <option value="" disabled selected>Select Device</option>
                                @foreach ($userDevices as $device)
                                    <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3" style="margin-top:-5px;">
                        <button type="submit" id="refreshButton" class="btn btn-primary"><i class="bi bi-hdd-fill"></i>&nbsp;Lihat Semua Device</button>
                    </div>
                    <div class="d-grid gap-2 mb-3" style="margin-top:-5px;">
                        <button type="submit" id="myLocationButton" class="btn btn-success"><i class="bi bi-compass-fill"></i>&nbsp;Lihat lokasi saya</button>
                    </div>
                    {{-- <div class="d-grid gap-2" style="margin-top:-5px;">
                        <button type="submit" id="updateLocationButton" class="btn btn-info"><i class="bi bi-arrow-clockwise"></i>&nbsp;Perbarui Posisi</button>
                    </div> --}}
                    <div id="alertMessage" class="alert alert-success alert-dismissible fade show mt-1" role="alert" style="display: none;">
                        <span id="alertText">Ini adalah pesan alert.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="map" class="container mt-1" style="margin-top:-10px;"></div>

        <script>
                $(document).ready(function() {
                            var map = L.map('map').setView([0, 0], 2);
                            var polyline = null;
                            var lastLocationMarker = null; // Tambahkan variabel untuk marker last location
                            var latestLocationMarker = null; // Tambahkan variabel untuk marker latest location
                            var markers = [];
                            var lastLocation = null;
                            var pathLocations = [];
                            var userMarker;

                            var customIcon = L.divIcon({
                                                        className: 'custom-div-icon',
                                                        html: "<i class='fas fa-map-marker-alt' style='color: #25A5E2; font-size: 40px;'></i>",
                                                        iconSize: [42, 49],
                                                        iconAnchor: [20, 44],
                                                        popupAnchor: [-5, -41]
                                                    });

                            // Inisialisasi peta Leaflet
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);

                            // Fungsi untuk menambahkan marker ke peta
                            function addMarker(latitude, longitude, customIcon) {
                                var marker = L.marker([latitude, longitude], {
                                    icon: customIcon
                                }).addTo(map);
                                markers.push(marker);
                            }

                            // Fungsi untuk memperbarui polylane
                            function updatePolyline() {
                                if (polyline !== null) {
                                    map.removeLayer(polyline);
                                }
                                polyline = L.polyline(pathLocations, {
                                    color: 'blue'
                                }).addTo(map);
                            }

                            function loadLastLocation(selectedDeviceId) {
                                $(".pulse").remove();
                                $("#selectDevice").parent().find(".leaflet-marker-icon").addClass("pulse");

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
                                        lastLocationMarker = L.marker([data.latitude, data.longitude], { icon: customIcon }).addTo(map);

                                        var popupContent = `<center><b>Last location</b></center><br>` +
                                            `<b>Device: ${data.name}</b><br>` +
                                            `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                            `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                            `<b>Date Time:</b> ${data.date_time}<br>` +
                                            `<img src="{{asset('storage')}}/${data.photo}" style="width: 199px; height: 127px;">`;

                                        lastLocationMarker.bindPopup(popupContent).openPopup();
                                        markers.push(lastLocationMarker);

                                        map.setView([data.latitude, data.longitude], 20);
                                    },
                                    error: function(error) {
                                        $(".pulse").removeClass("pulse");
                                        var alertText = 'Device yang dipilih tidak memiliki history';
                                        $('#alertText').text(alertText);
                                        var alertMessage = $('#alertMessage');
                                        alertMessage.removeClass('alert-danger alert-success').addClass('alert-danger');
                                        alertMessage.show();
                                    }
                                });
                            }

                            // Memuat lokasi terakhir ketika perangkat dipilih
                            $('#selectDevice').change(function() {
                                var selectedDeviceId = $(this).val();
                                if (selectedDeviceId) {
                                    markers.forEach(function(marker) {
                                        map.removeLayer(marker);
                                    });
                                    markers = [];

                                    if (polyline !== null) {
                                        map.removeLayer(polyline);
                                        polyline = null;
                                    }

                                    pathLocations = [];

                                    loadLastLocation(selectedDeviceId);

                                    $('#alertMessage').hide();
                                }
                            });

                            function updateLocation(selectedDeviceId) {
                                var selectedDeviceId = $('#selectDevice').val();
                                if (selectedDeviceId) {
                                    $.ajax({
                                        url: '/latestlocation/' + selectedDeviceId,
                                        method: 'GET',
                                        success: function(data) {
                                            console.log('lokasi baru:', data);

                                            if (data.date_time !== lastLocation.date_time) {

                                                lastTimestamp = data.date_time;

                                                var lastLocationCoordinates = [lastLocation.latitude, lastLocation.longitude];
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
                                                    iconAnchor: [20, 44],
                                                    popupAnchor: [-5, -41]
                                                });

                                                latestLocationMarker = L.marker(
                                                    latestLocationCoordinates, { icon: yellowIcon }
                                                ).addTo(map);

                                                var popupContent = `<center><b style="color: yellow; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;" >Latest location</b></center><br>` +
                                                    `<b>Device: ${data.name}</b><br>` +
                                                    `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                                    `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                                    `<b>Date Time:</b> ${data.date_time}<br>` +
                                                    `<img src="{{asset('storage')}}/${data.photo}" style="width: 199px; height: 127px;">`;

                                                latestLocationMarker.bindPopup(popupContent).openPopup();

                                                updatePolyline(); // Memperbarui polylane dengan menambahkan koordinat latest location
                                                map.setView(latestLocationCoordinates, 25, { maxZoom: 18 });
                                            } else {
                                                var alertText = 'Tidak ada data terbaru.';
                                                $('#alertText').text(alertText);
                                                var alertMessage = $('#alertMessage');
                                                alertMessage.removeClass('alert-danger alert-success').addClass('alert-danger');
                                                alertMessage.show();
                                            }
                                        },
                                        error: function(error) {
                                            console.error('Error fetching latest location:', error);
                                        }
                                    });
                                }
                            }
                            var intervalId = setInterval(function() {
                                updateLocation();
                            }, 5000);
                            
                            $('#refreshButton').click(function() {
                                location.reload();
                            });

                            @if ($latestHistories && count($latestHistories) > 0)
                                @foreach ($latestHistories as $history)
                                    @if ($history)
                                        var marker = L.marker([{{ $history->latitude }}, {{ $history->longitude }}], {icon: customIcon}).addTo(map);
                                        
                                        marker.bindPopup(
                                            `<center><b>{{ $history->device->name }}</b></center><br>` +
                                            `<b>Latlng:</b> {{ $history->latitude . ',' . $history->longitude }}<br>` +
                                            `<b>Nopol:</b> {{ $history->device->plat_nomor }}<br>` +
                                            `<b>LastTime:</b> {{ $history->date_time }}<br>` +
                                            `<img src="{{ asset('storage/' . $history->device->photo) }}" style="width: 199px; height: 127px;">`
                                        );
                                        markers.push(marker); // Tambahkan marker ke dalam array markers
                                    @endif
                                @endforeach

                                // Sesuaikan batas peta berdasarkan marker yang sudah ada
                                var bounds = L.latLngBounds([
                                    @foreach ($latestHistories as $history)
                                        @if ($history)
                                            [{{ $history->latitude }}, {{ $history->longitude }}],
                                        @endif
                                    @endforeach
                                ]);
                                map.fitBounds(bounds);
                            @endif
                            $('#myLocationButton').click(function() {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        var latitude = position.coords.latitude;
                                        var longitude = position.coords.longitude;

                                        console.log('Current Location:', latitude, longitude);

                                        // var customIcon = L.icon({
                                        //     iconUrl: '/images/mapgreen.png',
                                        //     iconSize: [42, 42],
                                        //     iconAnchor: [20, 44],
                                        //     popupAnchor: [1, -41]
                                        // });

                                        var customIcon = L.divIcon({
                                            className: 'custom-div-icon',
                                            html: "<i class='fas fa-map-marker-alt' style='color: green; font-size: 40px;'></i>",
                                            iconSize: [42, 49],
                                            iconAnchor: [20, 44],
                                            popupAnchor: [-5, -41]
                                        });

                                        var popupContent = `<center><b> Lokasi Anda </b></center><br>` +
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
                                        alertMessage.removeClass('alert-danger alert-primary').addClass('alert-success');
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
                });   
        </script>
    </div>
    <div class="navbarend">
            <div class="nav-item">
                <a href="{{route('lastlocation')}}">
                    <i class="fas fa-map-marker-alt"></i><br>
                    <span>Lastloc</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{route('customer.map.index')}}">
                    <i class="bi bi-map-fill"></i><br>
                    <span>Maps</span>
                </a>
            </div>

            <div class="nav-item logo">
                <a href="{{route('index.customer')}}">
                    <img src="/images/g.png" alt="Logo">
                </a>
            </div>

            <div class="nav-item">
                <a href="{{route('customer.device.index')}}">
                    <i class="fas fa-tablet"></i><br>
                    <span>Device</span>
                </a>
            </div>

            <div class="nav-item dropdown">
                <a class="nav-link" href=/customer/profile>
                    <div class="avatar">
                        <!-- Gambar Profil -->
                        @if (Auth::user()->photo)
                            <img src="/photos/{{ Auth::user()->photo }}" alt="User Photo">
                        @else
                            <img src="{{ asset('images/default.jpg') }}" alt="Default User Photo">
                        @endif
                    </div>
                </a>
            </div>
    </div>
@endsection