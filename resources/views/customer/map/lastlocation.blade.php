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
    display: none; /* Tambahkan ini */
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
                $('#selectDevice').change(function() {
                    console.log('Select Device Changed');
                    var selectedDeviceId = $(this).val();
                    console.log('Selected Device ID:', selectedDeviceId);
                    loadDeviceOnMap(selectedDeviceId);
                });

                var map = L.map('map').setView([0, 0], 2);
                var polyline = L.polyline([], { color: 'blue' }).addTo(map);
                var markers = [];

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                function loadDeviceOnMap(id_device) {
                    console.log('loadDeviceOnMap called with ID:', id_device);
                    // Hapus semua marker sebelum memuat marker baru
                    for (var i = 0; i < markers.length; i++) {
                        map.removeLayer(markers[i]);
                    }
                    markers = []; // Reset array markers

                    // Periksa apakah pengguna memilih perangkat
                    if (id_device) {
                        console.log('Making AJAX request for device information...');

                        // Lakukan permintaan AJAX untuk mendapatkan informasi perangkat
                        $.ajax({
                            url: '/deviceuser/' + id_device,
                            method: 'GET',
                            success: function(data) {
                                console.log('AJAX Success - Data:', data);

                                // Tambahkan marker untuk perangkat yang dipilih
                                var marker = L.marker([data.latitude, data.longitude]).addTo(map);
                                marker.bindPopup(
                                    `<center><b>Device: ${data.name}</b></center><br>` +
                                    `<b>Latlng:</b> ${data.latitude},${data.longitude}<br>` +
                                    `<b>Plat Nomor:</b> ${data.plat_nomor}<br>` +
                                    `<b>Date Time:</b> ${data.date_time}<br>` +
                                    `<img src="${data.photo}" style="width: 199px; height: 115px;">`
                                );

                                markers.push(marker); // Tambahkan marker ke dalam array markers

                                // Perbarui tampilan peta untuk memusatkan pada marker baru
                                map.setView([data.latitude, data.longitude], 15);
                                var alertMessage = $('#alertMessage');
                                alertMessage.html('Data berhasil ditemukan!');
                                alertMessage.removeClass('alert-danger').addClass('alert-success');
                                alertMessage.show();

                                map.flyTo([data.latitude, data.longitude], 18, {
                                    animate: true,
                                    duration: 2 // Adjust the duration of the animation (in seconds)
                                });
                            },
                            error: function(error) {
                                console.error('Error fetching device information:', error);

                                // Tampilkan pesan alert untuk kesalahan
                                var alertMessage = $('#alertMessage');
                                alertMessage.html('Perangkat Tidak Ditemukan.');
                                alertMessage.removeClass('alert-success').addClass('alert-danger');
                                alertMessage.show();
                            }

                        });
                    }
                }

                //marker untuk history yang ada
                @if ($latestHistories && count($latestHistories) > 0)
                    @foreach ($latestHistories as $history)
                        @if ($history)
                            var marker = L.marker([{{ $history->latitude }}, {{ $history->longitude }}]).addTo(map);
                            marker.bindPopup(
                                `<center><b>Device: {{ $history->device->name }}</b></center><br>` +
                                `<b>Latlng:</b> {{ $history->latitude . ',' . $history->longitude }}<br>` +
                                `<b>Plat Nomor:</b> {{ $history->device->plat_nomor }}<br>` +
                                `<b>Date Time:</b> {{ $history->date_time }}<br>` +
                                `<img src="{{ asset('storage/' . $history->device->photo) }}" style="width: 199px; height: 115px;">`
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
                $('#refreshButton').click(function() {
                    location.reload(); // Reload the current page
                });
                // Event listener untuk tombol "Lihat lokasi saya"
                $('#myLocationButton').click(function() {
                    // Periksa apakah browser mendukung Geolocation API
                    if (navigator.geolocation) {
                        // Dapatkan lokasi terkini pengguna
                        navigator.geolocation.getCurrentPosition(function(position) {
                            // Dapatkan koordinat latitude dan longitude
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            // Tambahkan marker untuk lokasi pengguna
                            var userMarker = L.marker([latitude, longitude]).addTo(map);
                            userMarker.bindPopup('Lokasi Anda');
                            
                            // Perbarui tampilan peta untuk memusatkan pada lokasi pengguna
                            map.setView([latitude, longitude], 17);

                            $('#updateLocationButton').show();

                            var alertText = 'Lokasi Anda berhasil ditampilkan pada peta.';
                            $('#alertText').text(alertText);
                            var alertMessage = $('#alertMessage');
                            alertMessage.removeClass('alert-danger alert-primary').addClass('alert-success');
                            alertMessage.show();
                        }, function(error) {
                            // Tangani kesalahan jika pengguna tidak memberikan izin atau terjadi kesalahan lain
                            console.error('Error getting user location:', error);

                            // Tampilkan pesan alert untuk kesalahan
                            var alertMessage = $('#alertMessage');
                            alertMessage.html('Tidak dapat menampilkan lokasi Anda pada peta.');
                            alertMessage.removeClass('alert-success').addClass('alert-danger');
                            alertMessage.show();
                        });
                    } else {
                        // Tangani jika Geolocation API tidak didukung
                        console.error('Geolocation is not supported by this browser.');

                        // Tampilkan pesan alert
                        var alertMessage = $('#alertMessage');
                        alertMessage.html('Geolocation tidak didukung oleh browser ini.');
                        alertMessage.removeClass('alert-success').addClass('alert-danger');
                        alertMessage.show();
                    }
                });
                $('#updateLocationButton').click(function() {
                    // Periksa apakah browser mendukung Geolocation API
                    if (navigator.geolocation) {
                        // Dapatkan lokasi terkini pengguna
                        navigator.geolocation.getCurrentPosition(function(position) {
                            // Dapatkan koordinat latitude dan longitude
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            // Hapus marker pengguna yang sudah ada dari peta
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.Marker && layer.getPopup().getContent() === 'Lokasi Anda') {
                                    map.removeLayer(layer);
                                }
                            });

                            // Tambahkan marker baru untuk lokasi pengguna
                            var userMarker = L.marker([latitude, longitude]).addTo(map);
                            userMarker.bindPopup('Lokasi Anda');

                            // Perbarui tampilan peta untuk memusatkan pada lokasi pengguna
                            map.setView([latitude, longitude], 17);

                            // Tambahkan posisi terbaru ke polyline
                            var latlng = [latitude, longitude];
                            polyline.addLatLng(latlng);

                            var alertText = 'Posisi Anda berhasil diperbarui pada peta.';
                            $('#alertText').text(alertText);
                            var alertMessage = $('#alertMessage');
                            alertMessage.removeClass('alert-danger alert-success').addClass('alert-primary');
                            alertMessage.show();
                        }, function(error) {
                            // Tangani kesalahan jika pengguna tidak memberikan izin atau terjadi kesalahan lain
                            console.error('Error getting user location:', error);

                            // Tampilkan pesan alert untuk kesalahan
                            var alertMessage = $('#alertMessage');
                            alertMessage.html('Tidak dapat memperbarui posisi Anda pada peta.');
                            alertMessage.removeClass('alert-success').addClass('alert-danger');
                            alertMessage.show();
                        });
                    } else {
                        // Tangani jika Geolocation API tidak didukung
                        console.error('Geolocation is not supported by this browser.');

                        // Tampilkan pesan alert
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
