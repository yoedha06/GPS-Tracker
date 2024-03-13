        @extends('layouts.customer')


        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        @section('content')
        <div id="main">
            <div class="form-group ml-3">
                <label for="device-select">Select Device:</label>
                <select id="device-select" class="form-control" >
                    <option value="" disabled selected>Select Device</option>
                    @foreach($devices as $device)
                    <option value="{{ $device->id_device }}">{{ $device->user->name }} || {{ $device->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Tanggal dan Waktu Mulai</label>
                <input type="text" id="start_date" class="form-control" placeholder="Start Date & Time">
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal dan Waktu Selesai</label>
                <input type="text" id="end_date" class="form-control" placeholder="End Date & Time">
            </div>
            {{-- <button id="filter_button">Filter</button> --}}
            <div id="map" style="height: 50%; width: 100%;"></div>
        </div>

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- Include Select2 JS -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://unpkg.com/leaflet.animatedmarker/src/AnimatedMarker.js"></script>
        <style>
            .date-time-input {
                display: flex;
                justify-content: flex-end;
                margin-top: 10px;
            }
            .date-label {
                position: relative;
                display: inline-block;
                margin-right: 10px;
            }
            .date-label input[type="date"] {
                padding-right: 30px;
            }
            .date-label i.fas.fa-calendar {
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
                pointer-events: none;
            }
        </style>
        <script>
$(document).ready(function () {
    // Inisialisasi select2 untuk elemen "device-select"
    $('#device-select').select2();

    // Inisialisasi flatpickr untuk elemen "start_date" dengan konfigurasi default
    var startDatePicker = flatpickr("#start_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        defaultDate: new Date().setHours(0, 0, 0, 0) // Set default date to today at 00:00
    });

    // Inisialisasi flatpickr untuk elemen "end_date" dengan konfigurasi default
    var endDatePicker = flatpickr("#end_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        defaultDate: new Date().setHours(23, 0, 0, 0) // Set default date to today at 23:00
    });

    // Inisialisasi peta Leaflet
    var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Mendapatkan data riwayat, nama perangkat, dan nomor seri
    var historyData = @json($history);
    var deviceName = {!! $devices->pluck('name') !!};
    var serialNumber = {!! $devices->pluck('serial_number') !!};

    // Membuat layer group untuk menampung marker dan polyline
    var layerGroup = L.layerGroup();
    var polylinePoints = [];

    // Fungsi untuk memfilter dan menampilkan data pada peta
    function filterMap() {
        var startDate = startDatePicker.selectedDates[0];
        var endDate = endDatePicker.selectedDates[0];
        var selectedDevice = $('#device-select').val();

        map.removeLayer(layerGroup);
        layerGroup.clearLayers();
        polylinePoints = [];

        var polylineWeight;
        for (var i = 0; i < historyData.length; i++) {
            var date_time = new Date(historyData[i].date_time);

            if (date_time >= startDate && date_time <= endDate && (!selectedDevice || historyData[i].device_id == selectedDevice)) {
                var lat = parseFloat(historyData[i].latitude);
                var lng = parseFloat(historyData[i].longitude);
                var speed = parseFloat(historyData[i].speeds);
                var accuracy = parseFloat(historyData[i].accuracy);

                // Penentuan warna polyline dan beratnya berdasarkan kecepatan
                var color, opacity;
                if (speed < 20) {
                    color = 'green';
                    polylineWeight = 10;
                } else if (speed >= 20 && speed <= 40) {
                    color = 'yellow';
                    polylineWeight = 5;
                } else {
                    color = 'red';
                    polylineWeight = 2;
                }

                // Penentuan opasitas berdasarkan akurasi
                if (accuracy <= 10) {
                    opacity = 1.0;
                } else if (accuracy > 10 && accuracy <= 20) {
                    opacity = 0.75;
                } else {
                    opacity = 0.5;
                }

                // Menambahkan circle marker
                var circleMarker = L.circleMarker([lat, lng], {
                    radius: 0,
                    color: color,
                    stroke: false,
                });
                layerGroup.addLayer(circleMarker);
                polylinePoints.push([lat, lng]);

                // Menambahkan polyline jika telah ada lebih dari satu titik
                if (polylinePoints.length > 1) {
                    var polyline = L.polyline(polylinePoints.slice(-2), {
                        color: color,
                        weight: polylineWeight,
                        opacity: opacity,
                    }).addTo(map);
                    var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m";
                    polyline.bindPopup(popupContent);
                }

                // Menambahkan marker dengan informasi detail
                var marker = L.marker([lat, lng]).addTo(map);
                var popupContent =
                    "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis;'>" +
                    "<div style='font-size: 12px;'>" +
                    "Device Name: " + deviceName +
                    "<br>Serial Number: " + serialNumber +
                    "<br>Latitude: " + lat.toFixed(6) +
                    "<br>Longitude: " + lng.toFixed(6) +
                    "<br>Date & Time: " + date_time.toISOString().split('T')[0] + ' ' + date_time.toLocaleTimeString();
                    "</div>" +
                    "</div>";
                var popupOptions = {
                    maxWidth: 200
                };
                marker.bindPopup(popupContent, popupOptions);
                polylinePoints.push([lat, lng]);
            }
        }

        // Menyesuaikan tampilan peta dengan semua titik
        var allLatLngs = polylinePoints.map(function(latlng) {
            return L.latLng(latlng[0], latlng[1]);
        });
        layerGroup.addTo(map);
        map.fitBounds(L.latLngBounds(allLatLngs));
    }

    // Menambahkan event listener untuk pemanggilan filterMap() saat ada perubahan pada tanggal akhir
    endDatePicker.config.onChange.push(filterMap);

    // Menambahkan event listener untuk pemanggilan filterMap() saat ada perubahan pada pemilihan perangkat
    $('#device-select').change(function() {
        filterMap();
    });

    // Set timeout untuk mengatur fokus ke elemen "start_date" setelah render flatpickr
    setTimeout(function() {
        $('#device-select').focus();
    }, 100);

    setTimeout(function() {
        $('#start_date').focus();
    }, 100);

    // Set timeout untuk mengatur fokus ke elemen "end_date" setelah render flatpickr
    setTimeout(function() {
        $('#end_date').focus();
    }, 100);

    // Filtering awal saat halaman dimuat
    filterMap();
});

        </script>
        @endsection

