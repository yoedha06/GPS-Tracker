@extends('layouts.customer')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" defer></script>


<style>
    #map {
        height: 90%;
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

    /* Lingkaran biru */
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
</style>

@section('content')
    <header>
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div id="main" style="padding-top:5px;">
        <div class="container">
            <form class="mt-2 row g-3">
                <div class="col-md-6 mb-3">
                    <select id="selectDevice" class="form-select" aria-label="Select Device" style="width: 202%">
                        <option value="" disabled selected>Select Device</option>
                        @foreach ($userDevices as $device)
                            <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="container group">
                
                <button type="submit" id="refreshButton" class="btn btn-primary" style="margin-top:-20px;"><i class="bi bi-hdd-fill"></i>&nbsp;Lihat Semua Device</button>
                <button type="submit" id="myLocationButton" class="btn btn-success" style="margin-top:-20px;"><i class="bi bi-compass-fill"></i>&nbsp;Lihat lokasi saya</button>
                <button type="submit" id="updateLocationButton" class="btn btn-info" style="margin-top:-20px;"><i class="bi bi-arrow-clockwise"></i>&nbsp;Perbarui Posisi</button>
                <div id="alertMessage" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none; margin-top:7px;">
                    <span id="alertText">Ini adalah pesan alert.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        {{-- @dump($userDevices) --}}
        <div id="map" style="margin-top: 10px;"></div>

        <script>
            $(document).ready(function() {
                        var map = L.map('map').setView([0, 0], 2);
                        var polyline = null; // Inisialisasi polylane
                        var markers = [];
                        var lastLocation = null; // Informasi last location yang telah dilihat pengguna
                        var pathLocations = [];
                        var userMarker;

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                        }).addTo(map);

                        // Fungsi untuk menambahkan marker ke peta
                        function addMarker(latitude, longitude) {
                            var marker = L.marker([latitude, longitude]).addTo(map);
                            markers.push(marker);
                        }

                        // Fungsi untuk memperbarui polylane
                        function updatePolyline() {
                            // Hapus polylane lama jika ada
                            if (polyline !== null) {
                                map.removeLayer(polyline);
                            }
                            // Tambahkan polylane baru yang menghubungkan semua lokasi dalam pathLocations
                            polyline = L.polyline(pathLocations, {
                                color: 'blue'
                            }).addTo(map);
                        }

                        // Fungsi untuk memuat last location dari perangkat terpilih
                        function loadLastLocation(selectedDeviceId) {
                            $.ajax({
                                url: '/lastlocation/' + selectedDeviceId,
                                method: 'GET',
                                success: function(data) {
                                    console.log('lokasi terakhir:', data);

                                    // Simpan informasi last location
                                    lastLocation = data;

                                    // Tambahkan marker untuk last location
                                    addMarker(data.latitude, data.longitude);
                                    
                                    // Membuat konten popup dengan data yang diterima
                                    var popupContent =  `<center><b>Last location</b></center><br>` +
                                                        `<b>Device: ${data.name}</b><br>` +
                                                        `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                                        `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                                        `<b>Date Time:</b> ${data.date_time}<br>`+
                                                        `<img src="{{asset('storage')}}/${data.photo}" style="width: 199px; height: 127px;">`;

    
                                    var lastLocationMarker = L.marker([data.latitude, data.longitude]).addTo(map);
                                    lastLocationMarker.bindPopup(popupContent).openPopup(); // Tambahkan popup dengan konten yang dibuat
                                    markers.push(lastLocationMarker);

                                    pathLocations.push([data.latitude, data.longitude]);

                                    // Perbarui tampilan peta untuk memusatkan pada last location
                                    map.setView([data.latitude, data.longitude], 15);
                                },
                                error: function(error) {
                                    console.error('Error fetching last location:', error);
                                }
                            });
                        }

                        $('#selectDevice').change(function() {
                            var selectedDeviceId = $(this).val();
                            if (selectedDeviceId) {
                                // Bersihkan marker dan polylane
                                markers.forEach(function(marker) {
                                    map.removeLayer(marker);
                                });
                                markers = [];

                                if (polyline !== null) {
                                    map.removeLayer(polyline);
                                    polyline = null;
                                }

                                // Memuat last location
                                loadLastLocation(selectedDeviceId);
                                
                                // Menampilkan tombol "Update"
                                $('#updateLocationButton').show();
                            } else {
                                // Sembunyikan tombol "Update" jika tidak ada perangkat yang dipilih
                                $('#updateLocationButton').hide();
                            }
                        });

                        //last location saat halaman dimuat
                        var selectedDeviceId = $('#selectDevice').val();
                        if (selectedDeviceId) {
                            loadLastLocation(selectedDeviceId);
                        }
                        $('#updateLocationButton').click(function() {
                            var selectedDeviceId = $('#selectDevice').val();
                            if (selectedDeviceId) {
                                $.ajax({
                                    url: '/latestlocation/' + selectedDeviceId,
                                    method: 'GET',
                                    success: function(data) {
                                        console.log('lokasi baru:', data);

                                        pathLocations.push([data.latitude, data.longitude]);

                                        latestLocation = data;

                                        var customIcon = L.icon({
                                            iconUrl: '/images/mapyellow.png', 
                                            iconSize: [44, 49], 
                                            iconAnchor: [21, 44], // akurasi yang pass coy
                                            popupAnchor: [1, -39]
                                        });

                                        var latestLocationMarker = L.marker(
                                            [data.latitude, data.longitude],{icon: customIcon}
                                        ).addTo(map);

                                        var popupContent =  `<center><b style="color: yellow; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;" >Latest location</b></center><br>` +
                                                            `<b>Device: ${data.name}</b><br>` +
                                                            `<b>Plat Nomor:</b> ${data.plate_number}<br>` +
                                                            `<b>Latlng:</b> ${data.latitude}, ${data.longitude}<br>` +
                                                            `<b>Date Time:</b> ${data.date_time}<br>`+
                                                            `<img src="{{asset('storage')}}/${data.photo}" style="width: 199px; height: 127px;">`;

                                        latestLocationMarker.bindPopup(popupContent).openPopup();
                                        markers.push(latestLocationMarker);
                                        updatePolyline();
                                        map.setView([data.latitude, data.longitude], 20);
                                    },
                                    error: function(error) {
                                        console.error('Error fetching latest location:', error);
                                    }
                                });
                            }
                        });
                        $('#refreshButton').click(function() {
                            location.reload(); // Reload the current page
                        });
                        @if ($latestHistories && count($latestHistories) > 0)
                            @foreach ($latestHistories as $history)
                                @if ($history)
                                    var marker = L.marker([{{ $history->latitude }}, {{ $history->longitude }}]).addTo(map);
                                    marker.bindPopup(
                                        `<center><b>Device: {{ $history->device->name }}</b></center><br>` +
                                        `<b>Latlng:</b> {{ $history->latitude . ',' . $history->longitude }}<br>` +
                                        `<b>Plat Nomor:</b> {{ $history->device->plat_nomor }}<br>` +
                                        `<b>Date Time:</b> {{ $history->date_time }}<br>` +
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
@endsection