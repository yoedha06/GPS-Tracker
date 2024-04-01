@extends('layouts.customer')

<title>GEEX - Maps</title>
<!-- Load Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet Routing Machine CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<!-- Load Bootstrap Datepicker CSS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<!-- Load Select2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

<!-- Load Select2 CSS (version 4.1.0-rc.0) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Load Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Load Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
        float: left;
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

    #date_range {
        width: 350px;
        /* Sesuaikan lebarnya sesuai kebutuhan Anda */
        text-align: left;
    }

    #map {
        width: 100%;
        height: 70%;
        border-radius: 7px;
        z-index: 1;
    }

    @media (max-width: 767px) {
        #map {
            height: 70%;
            border-radius: 7px;
            z-index: 1;
        }
    }

    .custom-div-icon {
        width: 32px;
        height: 32px;
    }

    .custom-div-icon i {
        color: green;
        /* Mengatur warna ikon menjadi merah */
    }

    .notification-container {
        position: fixed;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        z-index: 9999;
        display: flex;
        align-items: center;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .notification {
        padding: 10px;
        background-color: #f30e21;
        color: #ffffff;
        margin-left: 10px;
        border-radius: 5px;
        animation: slideInRight 0.5s forwards;
    }

    @keyframes slideInRight {
        0% {
            transform: translateX(100%);
            opacity: 0;
        }

        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }

    #filter-options {
        display: none;
        /* Sembunyikan container filter */
    }
</style>



@section('content')
    <!-- Notifikasi -->
    <div class="notification-container" id="notification-container">
        <div class="notification" id="notification">
            <i class="fas fa-exclamation-circle"></i> Tidak ada data yang tersedia untuk perangkat dan rentang tanggal yang
            dipilih.
        </div>
    </div>

    <div id="main" style="padding-top: 4px; padding-right: 10px; padding-left: 10px;">
        <div class="form-group ml-3" style="display: flex; flex-direction: column; width: 100%;">
            <label for="device-select">Select Devicee:</label>
            <div class="d-flex">
                <select id="device-select" class="form-select input" style="width: 100%;">
                    <option value="" disabled selected>Select Device</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id_device }}">{{ $device->user->name }} - {{ $device->name }}</option>
                    @endforeach
                </select>
                <button id="reset-btn" class="btn btn-danger btn-sm" style="margin-left: auto;">Reset</button>
            </div>
        </div>

        <div class="form-group ml-3 date-time-input" style="display: flex; flex-direction: column; width: 100%;">
            <label for="date_range" style="margin-bottom: 5px;">Date range:</label>
            <div class="date-label" style="position: relative; left: 0;">
                <input type="text" id="date_range" class="form-control" placeholder="Start Date & Time - End Date & Time"
                    style="width: 100%; padding-right: 30px;">
                <i class="fas fa-calendar"
                    style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);"></i>
            </div>
        </div>


        <!-- Di dalam bagian content -->
        <div id="filter-options">
            <label for="speed-checkbox">
                <input type="checkbox" id="speed-checkbox" class="filter-checkbox"> Speed
            </label>
            <label for="accuracy-checkbox">
                <input type="checkbox" id="accuracy-checkbox" class="filter-checkbox"> Accuracy
            </label>
        </div>

        <div>
            <div id="device-names" data-device-names="{{ json_encode($deviceNames) }}" style="display: none;"></div>
        </div>

        <div id="map" class="container mt-1" style="margin-top:-10px;"></div>
    </div>

    

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-routing-machine/3.2.12/leaflet-routing-machine.min.js">
    </script>

    <!-- Then load Bootstrap's JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.animatedmarker/src/AnimatedMarker.js"></script>


    <script>
        // Tentukan koordinat titik awal dan akhir rute
        var start = L.latLng(historyData[0].latitude, historyData[0]
            .longitude); // Mengambil koordinat titik awal dari data pertama dalam historyData
        var end = L.latLng(historyData[historyData.length - 1].latitude, historyData[historyData.length - 1]
            .longitude); // Mengambil koordinat titik akhir dari data terakhir dalam historyData

        // Tentukan opsi rute
        var routingOptions = {
            waypoints: [
                start,
                end
            ],
            routeWhileDragging: true // Opsi ini akan menampilkan rute saat Anda menarik marker
        };

        // Buat objek rute dengan menggunakan routingOptions
        var routingControl = L.Routing.control(routingOptions).addTo(map);
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk dropdown perangkat
            $('#device-select').select2({
                sorter: function(data) {
                    return data.sort(function(a, b) {
                        return a.text.localeCompare(b.text);
                    });
                }
            });

            // Nonaktifkan checkbox "Speed" dan "Accuracy" secara default
            $('#speed-checkbox').prop('disabled', true);
            $('#accuracy-checkbox').prop('disabled', true);

            // Mengatur opsi checkbox "Speed" dan "Accuracy" menjadi tidak terlihat saat halaman dimuat
            $('#filter-options').css('display', 'none');

            // Menambahkan event listener untuk perubahan pada dropdown #device-select
            $('#device-select').on('change', function() {
                // Memeriksa apakah sebuah perangkat telah dipilih
                var selectedDevice = $(this).val();
                if (selectedDevice) {
                    // Jika perangkat telah dipilih, tampilkan opsi checkbox "Speed" dan "Accuracy"
                    $('#filter-options').css('display', 'block');
                } else {
                    // Jika tidak ada perangkat yang dipilih, sembunyikan opsi checkbox "Speed" dan "Accuracy"
                    $('#filter-options').css('display', 'none');
                }
            });



            $('#reset-btn').on('click', function() {
                // // Mereset nilai Select2 ke null dan memicu perubahan
                // $('#device-select').val(null).trigger('change');

                // // Hapus semua marker dari peta
                // map.eachLayer(function (layer) {
                //     if (layer instanceof L.Marker) {
                //         map.removeLayer(layer);
                //     }
                // });

                // // Hapus semua garis dari peta
                // map.eachLayer(function (layer) {
                //     if (layer instanceof L.Polyline) {
                //         map.removeLayer(layer);
                //     }
                // });

                // Refresh halaman dengan memuat ulang URL
                location.href = location.href + '?rand=' + Math.random();
            });

            var deviceNames = {!! json_encode($deviceNames) !!};
            var historyData = {!! $history->toJson() !!};
            var defaultStartDate = new Date();
            defaultStartDate.setHours(0, 0, 0, 0);

            // Set default end date to today at 23:00
            var defaultEndDate = new Date();
            defaultEndDate.setHours(23, 0, 0, 0);

            var dateRangePicker = flatpickr("#date_range", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: [defaultStartDate, defaultEndDate],
                mode: "range",
                onChange: function(selectedDates, dateStr, instance) {
                    filterMap();
                }
            });

            var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = [];
            var devicePolylines = {};

            function filterMap() {
                var startDate = dateRangePicker.selectedDates[0];
                var endDate = dateRangePicker.selectedDates[1];
                var selectedDevice = $('#device-select').val();
                var showSpeeds = $('#speed-checkbox').is(':checked');
                var showAccuracy = $('#accuracy-checkbox').is(':checked');


                var filteredData = historyData.filter(function(item) {
                    var currentDate = new Date(item.date_time);
                    var isWithinDateRange = (!startDate || currentDate >= startDate) && (!endDate ||
                        currentDate <= endDate);
                    var isDeviceSelected = !selectedDevice || selectedDevice.includes(item.device_id);
                    var isSpeedValid = showSpeeds ? parseFloat(item.speeds) > 0 : true;
                    var isAccuracyValid = showAccuracy ? parseFloat(item.accuracy) > 0 : true;
                    return isWithinDateRange && isDeviceSelected && isSpeedValid && isAccuracyValid;
                });

                if (filteredData.length === 0) {
                    // Tampilkan notifikasi
                    $('#notification-container').css('opacity', '1'); // Menampilkan notifikasi

                    // Atur timeout untuk menyembunyikan notifikasi setelah 5 detik (misalnya)
                    setTimeout(function() {
                        $('#notification-container').css('opacity',
                            '0'); // Menyembunyikan notifikasi setelah 5 detik
                    }, 4000); // Waktu dalam milidetik (5000 milidetik = 5 detik)
                } else {
                    // Sembunyikan notifikasi jika ada data yang cocok
                    $('#notification-container').css('opacity', '0');
                }



                // Menginisialisasi objek untuk melacak "Start" dan "End" untuk masing-masing perangkat
                var startMarkers = {};
                var endMarkers = {};

                // Hapus semua marker dan polyline sebelum memproses data baru
                markers.forEach(marker => {
                    map.removeLayer(marker);
                });
                Object.values(devicePolylines).forEach(polyline => {
                    map.removeLayer(polyline);
                });


                markers = [];
                devicePolylines = {};

                // Calculate average speed
                var totalSpeed = 0;
                for (var i = 0; i < historyData.length; i++) {
                    totalSpeed += parseFloat(historyData[i].speeds);
                }
                var averageSpeed = historyData.length > 0 ? totalSpeed / historyData.length : 0;


                var startIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<i class='fas fa-map-marker-alt' style='font-size: 40px; color: green;'></i>",
                    iconSize: [42, 49],
                    iconAnchor: [20, 44],
                    popupAnchor: [-5, -41]
                });

                var endIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<i class='fas fa-map-marker-alt' style='color: red; font-size: 40px;'></i>",
                    iconSize: [42, 49],
                    iconAnchor: [20, 44],
                    popupAnchor: [-5, -41]
                });

                // Fungsi untuk menghapus semua marker dari peta
                function clearMarkers() {
                    for (var i = 0; i < markers.length; i++) {
                        map.removeLayer(markers[i]);
                    }
                    markers = []; // Kosongkan array markers
                }

                // Memanggil fungsi untuk menghapus semua marker sebelum menambahkan yang baru
                clearMarkers();

                // Loop melalui data histori
                for (var i = 0; i < historyData.length; i++) {
                    var historyItem = historyData[i];
                    var lat = parseFloat(historyItem.latitude);
                    var lng = parseFloat(historyItem.longitude);
                    var deviceId = historyItem.device_id;
                    var currentDate = new Date(historyItem.date_time);

                    var isDeviceSelected = !selectedDevice || selectedDevice.includes(deviceId);
                    var isWithinDateRange = (!startDate || currentDate >= startDate) && (!endDate || currentDate <=
                        endDate);

                    if (isDeviceSelected && isWithinDateRange) {
                        // Check if it's start or end marker
                        var isEnd = !endMarkers[deviceId]; // Reversed logic to check for end marker
                        var isStart = !isEnd; // Determine if it's a start marker

                        if (isStart) {
                            // Check if start marker already exists for this device
                            if (startMarkers[deviceId]) {
                                // Remove previous start marker
                                map.removeLayer(startMarkers[deviceId]);
                            }
                            var markerIcon = startIcon;
                            var popupContent = `<div>
                                <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">Start</div>
                                Device: ${deviceNames[deviceId]}<br>
                                Latitude: ${lat.toFixed(6)}<br>
                                Longitude: ${lng.toFixed(6)}<br>
                                Date & Time: ${historyItem.date_time}<br>
                            </div>`;
                            startMarkers[deviceId] = L.marker([lat, lng], {
                                icon: markerIcon
                            }).bindPopup(popupContent).addTo(map);
                        } else if (isEnd) { // If it's an end marker
                            var markerIcon = endIcon;
                            var popupContent = `<div>
                                <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">End</div>
                                Device: ${deviceNames[deviceId]}<br>
                                Latitude: ${lat.toFixed(6)}<br>
                                Longitude: ${lng.toFixed(6)}<br>
                                Date & Time: ${historyItem.date_time}<br>
                            </div>`;
                            endMarkers[deviceId] = L.marker([lat, lng], {
                                icon: markerIcon
                            }).bindPopup(popupContent).addTo(map);
                        }



                        var marker = L.marker([lat, lng]); // Create a marker (not using icons for other markers)
                        markers.push(marker);

                        if (!devicePolylines[deviceId]) {
                            devicePolylines[deviceId] = L.polyline([], {}).addTo(map);
                        }

                        devicePolylines[deviceId].addLatLng([lat, lng]);
                    }


                }

                // Update popup content for start and end markers
                Object.keys(startMarkers).forEach(deviceId => {
                    var startMarker = startMarkers[deviceId];
                    var startPopupContent = startMarker.getPopup().getContent();
                    startMarker.getPopup().setContent(startPopupContent + "");
                });

                Object.keys(endMarkers).forEach(deviceId => {
                    var endMarker = endMarkers[deviceId];
                    var endPopupContent = endMarker.getPopup().getContent();
                    endMarker.getPopup().setContent(endPopupContent + "");
                });

                // Update polyline styles
                Object.values(devicePolylines).forEach(polyline => {
                    var color = 'blue'; // Default color
                    var weight = 3;
                    var opacity = 1.0;

                    // Change color for end marker polyline to red
                    if (polyline.getLatLngs().length > 0) {
                        var lastLatLng = polyline.getLatLngs()[polyline.getLatLngs().length - 1];
                        var lastDeviceId = historyData.find(item => item.latitude == lastLatLng.lat && item
                            .longitude == lastLatLng.lng).device_id;
                        if (endMarkers[lastDeviceId]) {
                            color = 'red';
                        }
                    }

                    polyline.setStyle({
                        color: color,
                        weight: weight,
                        opacity: opacity
                    });
                });

                // Fit bounds to markers
                var allMarkers = markers;
                var bounds = L.featureGroup(allMarkers).getBounds();
                map.fitBounds(bounds);
                // Fly to the new bounds with animation
                map.flyToBounds(bounds, {
                    animate: true,
                    duration: 1.5, // durasi animasi dalam detik
                    easeLinearity: 0.5 // pengaturan animasi
                });
                Object.values(devicePolylines).forEach(polyline => {
                    var color;
                    var weight;
                    var opacity;

                    var accuracy; // Deklarasikan variabel accuracy di sini

                    polyline.getLatLngs().forEach(point => {
                        var data = historyData.find(data => {
                            // Membandingkan koordinat dengan toleransi 0.0001 (sekitar 11 meter)
                            return Math.abs(parseFloat(data.latitude) - point.lat) <
                                0.0001 &&
                                Math.abs(parseFloat(data.longitude) - point.lng) < 0.0001;
                        });

                        if (data && data.speeds !== null && data.speeds !== undefined) {
                            var speed = parseFloat(data.speeds);
                            accuracy = parseFloat(data.accuracy); // Set nilai accuracy di sini

                            // Tentukan warna dan ketebalan garis berdasarkan checkbox "Speed"
                            if ($('#speed-checkbox').is(':checked')) {
                                if (speed <= 20) {
                                    color =
                                        'green'; // Jika kecepatan kurang dari atau sama dengan 20 km/h, warna adalah hijau
                                    weight = 10; // Set ketebalan garis menjadi tebal
                                } else if (speed <= 40) {
                                    color =
                                        'yellow'; // Jika kecepatan di antara 21 dan 40 km/h, warna adalah kuning
                                    weight = 7; // Set ketebalan garis menjadi sedang
                                } else {
                                    color =
                                        'red'; // Jika kecepatan lebih dari 40 km/h, warna adalah merah
                                    weight = 3; // Set ketebalan garis menjadi tipis
                                }
                            } else {
                                // Jika checkbox "Speed" tidak dicentang, atur warna dan ketebalan garis default
                                color = 'blue';
                                weight = 3;
                            }
                            popupContent = "Speed: " + speed.toFixed(2) + " km/h<br>Accuracy: " +
                                accuracy.toFixed(2) + " meters";
                            // Hentikan perulangan setelah menemukan data kecepatan pertama
                            return;
                        }
                    });

                    if ($('#accuracy-checkbox').is(':checked')) {
                        if (accuracy <= 10) {
                            opacity =
                                1.0; // Set opasitas ke 1.0 jika akurasi kurang dari atau sama dengan 10 (tidak transparan)
                            color = 'green'; // Jika akurasi <= 10, warna adalah hijau
                            weight = 10; // Ketebalan 10
                        } else if (accuracy <= 20) {
                            opacity =
                                0.6; // Set opasitas ke 0.6 jika akurasi di antara 11 dan 20 (sedang transparan)
                            color = 'yellow'; // Jika akurasi <= 20, warna adalah kuning
                            weight = 7; // Ketebalan 7
                        } else {
                            opacity = 0.3; // Set opasitas ke 0.3 jika akurasi di atas 20 (transparan)
                            color = 'red'; // Jika akurasi > 20, warna adalah merah
                            weight = 3; // Ketebalan 3
                        }
                    } else {
                        // Jika checkbox "Accuracy" tidak dicentang, atur opasitas default
                        opacity = 1.0;
                    }



                    // Atur gaya polyline
                    polyline.setStyle({
                        color: color,
                        weight: weight,
                        opacity: opacity
                    });



                    // Ikatan popup ke polyline
                    polyline.bindPopup(popupContent);
                });


                // Fit bounds to markers
                var allMarkers = markers;
                var bounds = L.featureGroup(allMarkers).getBounds();
                map.fitBounds(bounds);
            }

            $('#device-select').change(function() {
                // Dapatkan ID perangkat yang dipilih
                var selectedDevice = $(this).val();

                // Aktifkan checkbox "Speed" dan "Accuracy"
                $('#speed-checkbox').prop('disabled', false);
                $('#accuracy-checkbox').prop('disabled', false);

                // Hapus semua marker dari peta
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });

                // Hapus semua garis dari peta
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline) {
                        map.removeLayer(layer);
                    }
                });

                // Memfilter peta berdasarkan perangkat yang dipilih
                filterMap(selectedDevice);
            });

            // Tambahkan event handler untuk checkbox
            $('#speed-checkbox, #accuracy-checkbox').change(function() {
                filterMap();
            });


            dateRangePicker.config.onChange.push(function(selectedDates, dateStr, instance) {
                filterMap();
            });
        });
    </script>
@endsection