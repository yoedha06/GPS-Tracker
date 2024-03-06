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
</style>

@section('content')
    <header>
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div id="main">
        <div class="container">
            <form class="mt-2 row g-3">
                <div class="col-md-6 mb-3">
                    <label for="selectDevice" class="form-label">Select Device</label>
                    <select id="selectDevice" class="form-select" aria-label="Select Device" style="width: 202%">
                        <option value="" disabled selected>Select Device</option>
                        @foreach ($userDevices as $device)
                            <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <button id="refreshButton" class="btn btn-primary">Lihat Semua</button>
        </div>
        
        <div id="alertMessage" class="alert alert-success" style="display: none;">
        </div>
        {{-- @dump($userDevices) --}}
        <div id="map"></div>
        <script>
            $(document).ready(function() {
                $('#selectDevice').change(function() {
                    console.log('Select Device Changed');
                    var selectedDeviceId = $(this).val();
                    console.log('Selected Device ID:', selectedDeviceId);
                    loadDeviceOnMap(selectedDeviceId);
                });

                var map = L.map('map').setView([0, 0], 2);
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

                // Tambahkan marker untuk setiap history yang sudah ada
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
            });
        </script>
    </div>
@endsection
