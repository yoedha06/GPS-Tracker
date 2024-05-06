@extends('layouts.customer')

<title>GEEX - LastLocation</title>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    #map {
        width: 100%;
        height: 70%;
        border-radius: 7px;
    }

    @media (max-width: 767px) {
        #map {
            height: 70%;
            border-radius: 7px;
            z-index: 1;
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

    /*
    .logo img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .name {
        max-width: 100px;
        
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .name h6 {
        font-weight: normal;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    } */
</style>

@section('content')
    <div id="main" style="padding-top: 4px; padding-right: 10px; padding-left: 10px;">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end"
                            style="padding-left: 3px;
                        margin-top: 1px;
                        margin-bottom: -45px;">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/customer"> <i class="fas fa-user"></i></i>
                                        Customer</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-geo-alt-fill"></i>
                                    Last
                                    Location</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6" style="padding-left: 7px; padding-right: 7px;">
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
                <div class="col-md-6" style="padding-left: 7px; padding-right: 7px;">
                    <div class="d-grid gap-2 mb-3" style="margin-top:-5px;">
                        <button type="submit" id="refreshButton" class="btn btn-primary"><i
                                class="bi bi-hdd-fill"></i>&nbsp;Lihat Semua Device</button>
                    </div>
                    <div class="d-grid gap-2 mb-3" style="margin-top:-5px;">
                        <button type="submit" id="myLocationButton" class="btn btn-success"><i
                                class="bi bi-compass-fill"></i>&nbsp;Lihat lokasi saya</button>
                    </div>
                    {{-- <div class="d-grid gap-2" style="margin-top:-5px;">
                        <button type="submit" id="updateLocationButton" class="btn btn-info"><i class="bi bi-arrow-clockwise"></i>&nbsp;Perbarui Posisi</button>
                    </div> --}}
                    <div id="alertMessage" class="alert alert-success alert-dismissible fade show mt-1" role="alert"
                        style="display: none;">
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

                // fungsi untuk mendapatkankan nama jalan
                function getStreetName(lat, lng, callback) {
                    var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.address) {
                                var address = data.address;
                                var streetDetails = '';

                                // Cek untuk elemen alamat yang relevan
                                if (address.road) {
                                    streetDetails += address.road;
                                }
                                if (address.suburb) {
                                    streetDetails += ',' + address.suburb;
                                }
                                if (address.city) {
                                    streetDetails += ',' + address.city;
                                }
                                if (address.county) {
                                    streetDetails += ',' + address.county;
                                }
                                if (address.state) {
                                    streetDetails += ',' + address.state;
                                }
                                if (address.country) {
                                    streetDetails += ',' + address.postcode;
                                }

                                callback(streetDetails);
                            } else {
                                callback("Tidak ada nama jalan yang ditemukan");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                @foreach ($latestHistories as $history)
                    @if ($history)
                        (function() {
                            var latitude = {{ $history->latitude }};
                            var longitude = {{ $history->longitude }};
                            var marker = L.marker([latitude, longitude], {
                                icon: customIcon
                            }).addTo(map);

                            // Mendapatkan nama jalan
                            getStreetName(latitude, longitude, function(streetName) {
                                var popupContent =
                                `<div style="max-width: 200px;">` +
                                    `<center><b style="font-size:16px;">{{ $history->device->name }}</b></center><br>` +
                                    `<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>${latitude},${longitude}<br>` +
                                    `<i class="fas fa-road" style="margin-right: 2px;"></i> ${streetName} <br>` +
                                    `<i class="fas fa-id-card" style="margin-right: 5px;"></i> {{ $history->device->plat_nomor }}<br>` +
                                    `<i class="fas fa-clock" style="margin-right: 5px;"></i> {{ $history->date_time }}<br>` +
                                    @if ($history->device->photo)
                                        `<img src="{{ asset('storage/' . $history->device->photo) }}" style="height: 127px;">`
                                    @else
                                        `<p>No Image Here</p>`
                                    @endif +
                                `</div>`;

                                marker.bindPopup(popupContent);
                            });

                            markers.push(marker); // Tambahkan marker ke dalam array markers
                        })();

                        // Sesuaikan batas peta berdasarkan marker yang sudah ada
                        var bounds = L.latLngBounds([
                            @foreach ($latestHistories as $history)
                                @if ($history)
                                    [{{ $history->latitude }}, {{ $history->longitude }}],
                                @endif
                            @endforeach
                        ]);
                        map.fitBounds(bounds);
                        map.setZoom(13);
                    @endif
                @endforeach

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

                            lastLocation = data;

                            // Hapus marker last location sebelumnya (jika ada)
                            if (lastLocationMarker) {
                                map.removeLayer(lastLocationMarker);
                            }

                            // Tambahkan marker untuk last location
                            lastLocationMarker = L.marker([data.latitude, data.longitude], {
                                icon: customIcon
                            }).addTo(map);

                            // Mendapatkan nama jalan
                            getStreetName(data.latitude, data.longitude, function(streetName) {
                                var popupContent =
                                    `<div style="max-width: 200px;">` +
                                        `<center><b>Last location</b></center><br>` +
                                        `<i class="fas fa-tablet" style="margin-right: 5px;"></i> ${data.name}<br>` +
                                        `<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> ${data.latitude}, ${data.longitude}<br>` +
                                        `<i class="fas fa-road" style="margin-right: 2px;"></i> ${streetName}<br>` +
                                        `<i class="fas fa-id-card" style="margin-right: 5px;"></i> ${data.plate_number}<br>` +
                                        `<i class="fas fa-clock" style="margin-right: 5px;"></i> ${data.date_time}<br>` +
                                    `</div>`;

                                // Periksa apakah ada foto yang tersedia
                                if (data.photo) {
                                    // Jika ada foto, tambahkan tag img
                                    popupContent +=
                                        `<img src="{{ asset('storage') }}/${data.photo}" style="width: 199px; height: 127px;">`;
                                } else {
                                    // Jika tidak ada foto, tampilkan teks "No Photo Here"
                                    popupContent += `<p>No Photo Here</p>`;
                                }

                                lastLocationMarker.bindPopup(popupContent).openPopup();
                                markers.push(lastLocationMarker);

                                map.setView([data.latitude, data.longitude], 20);
                            });
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

                                if (data.date_time !== lastLocation.date_time) {

                                    lastTimestamp = data.date_time;

                                    var lastLocationCoordinates = [lastLocation.latitude, lastLocation
                                        .longitude
                                    ];
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
                                        latestLocationCoordinates, {
                                            icon: yellowIcon
                                        }
                                    ).addTo(map);

                                    getStreetName(data.latitude, data.longitude, function(streetName) {
                                        var popupContent =
                                            `<div style="max-width: 199px;">` +
                                                `<center><b style="color: yellow; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;" >Latest location</b></center><br>` +
                                                `<i class="fas fa-tablet" style="margin-right: 5px;"></i> ${data.name} <br>` +
                                                `<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> ${data.latitude}, ${data.longitude}<br>` +
                                                `<i class="fas fa-road" style="margin-right: 2px;"></i> ${streetName}<br>` +
                                                `<i class="fas fa-id-card" style="margin-right: 5px;"></i> ${data.plate_number}<br>` +
                                                `<i class="fas fa-clock" style="margin-right: 5px;"></i> ${data.date_time}<br>`;
                                            `</div>`;
                                        if (data.photo) {
                                            // Jika ada foto, tambahkan tag img
                                            popupContent +=
                                                `<img src="{{ asset('storage') }}/${data.photo}" style="width: 199px; height: 127px;">`;
                                        } else {
                                            // Jika tidak ada foto, tampilkan teks "No Image Here"
                                            popupContent += `<p>No Image Here</p>`;
                                        }

                                        latestLocationMarker.bindPopup(popupContent).openPopup();
                                    });

                                    updatePolyline(); // Memperbarui polylane dengan menambahkan koordinat latest location
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
                }
                var intervalId = setInterval(function() {
                    updateLocation();
                }, 5000);

                $('#refreshButton').click(function() {
                    location.reload();
                });

                $('#myLocationButton').click(function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            console.log('Current Location:', latitude, longitude);

                            // Mendapatkan nama jalan
                            getStreetName(latitude, longitude, function(streetName) {
                                var customIcon = L.divIcon({
                                    className: 'custom-div-icon',
                                    html: "<i class='fas fa-map-marker-alt' style='color: green; font-size: 40px;'></i>",
                                    iconSize: [42, 49],
                                    iconAnchor: [20, 44],
                                    popupAnchor: [-5, -41]
                                });

                                var popupContent =
                                    `<center><b>Lokasi Anda</b></center><br>` +
                                    `<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i> ${latitude},${longitude}<br>` +
                                    `<i class="fas fa-road" style="margin-right: 2px;"></i> ${streetName}`;

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
                            });
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
@endsection
