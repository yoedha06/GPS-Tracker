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

        .form-group.ml-3.date-time-input {
            display: flex;
            flex-direction: column;
            width: 422px;
            padding-left: 2px;
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
</style>



@section('content')
    <!-- Notifikasi -->
    <div class="notification-container" id="notification-container">
        <div class="notification" id="notification">
            <i class="fas fa-exclamation-circle"></i>
        </div>
    </div>

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
                                    Maps
                                    History</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <form id="filter-form" action="/your-action-endpoint" method="GET">
            <div class="form-group ml-3"
                style="display: flex; flex-direction: column; width: 100%; margin-top: 37px; margin-bottom: 0px;">
                <div class="d-flex" style="gap:5px;">
                    <select id="device-select" class="form-select input" style="width: 100%;">
                        <option value="" disabled>Select Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id_device }}" @if ($device->id_device == $latestDevice->id_device) selected @endif>
                                {{ $device->user->name }} - {{ $device->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="form-group ml-3 date-time-input"
                style="display: flex; flex-direction: column; width: 100%; margin-top: 5px;">
                <label for="date_range" style="padding-left:2px;">Date range:</label>
                <div class="date-label" style="position: relative; left: 0; margin-right: 0px;">
                    <input type="text" id="date_range" class="form-control"
                        placeholder="Start Date & Time - End Date & Time" style="width: 100%; padding-right: 30px;">
                    <i class="fas fa-calendar"
                        style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);"></i>
                </div>
            </div>


        </form>

        <div id="filter-options" style="display: none;"> <!-- Menyembunyikan seluruh filter-options -->
            <label for="speed-checkbox">
                <input type="checkbox" id="speed-checkbox" class="filter-checkbox"> Speed
            </label>
            <label for="accuracy-checkbox">
                <input type="checkbox" id="accuracy-checkbox" class="filter-checkbox"> Accuracy
            </label>
        </div>

    </div>
    </div>

    <div>
        <div id="device-names" data-device-names="{{ json_encode($deviceNames) }}" style="display: none;"></div>
    </div>

    </div>

    <div id="map-container">
        <div id="map"></div>
        <div id="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
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
            z-index: 0;
            width: 100%;
            height: 300px;
            margin-bottom: 50px;
            /* Menambahkan margin bawah 20px */
        }

        /* Atur lebar kontainer form */
        @media (max-width: 768px) {
            #map {
                height: 400px;
                /* Sesuaikan tinggi peta untuk layar mobile */
                margin-bottom: 50px;
                /* Menambahkan margin bawah 20px */
            }

            #main {
                width: 100%;
                /* Lebar kontainer form menjadi 100% */
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

        #speed-checkbox,
        #accuracy-checkbox {
            display: none;
        }

        #map-container {
            position: relative;
        }

        #loading-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>




    <script>
        $(document).ready(function() {
            if ($('#device-select').val()) {
                $('#filter-options').show();
                $('#speed-checkbox, #accuracy-checkbox').show();
            }
            //         setInterval(function() {
            //     // Mendapatkan data terbaru dan memperbarui peta
            //     filterHistory(selectedDevice, startDate, endDate);
            // }, 1000); // Waktu dalam milidetik (1 detik = 1000 milidetik)
            let queryParam = new URLSearchParams(window.location.search);
            let queryDevice = queryParam.get('device');
            let queryStart = queryParam.get('start');
            let queryEnd = queryParam.get('end');

            // Mendapatkan nilai perangkat terbaru dari backend data
            let latestDevice =
                "{{ $latestDevice ? $latestDevice->id_device : null }}"; // Jika $latestDevice null, gunakan null

            // Setel nilai default untuk start dan end date jika tidak ada dalam URL
            if (!queryStart || !queryEnd) {
                let defaultStart = new Date(new Date().getTime() - 3 * 60 * 60 * 1000); // 3 jam yang lalu
                let defaultEnd = new Date();
                queryStart = defaultStart.toISOString().slice(0, 16).replace('T', ' ');
                queryEnd = defaultEnd.toISOString().slice(0, 16).replace('T', ' ');
            }

            // Setel nilai input date range
            $('#date_range').val(queryStart + ' - ' + queryEnd);

            // Setel perangkat yang dipilih di dropdown jika latestDevice tidak null
            if (latestDevice !== null) {
                $('#device-select').val(latestDevice).trigger('change');
            }

            // Perbarui URL dengan parameter yang benar
            let newQueryString = `?start=${queryStart}&end=${queryEnd}`;
            if (latestDevice !== null) {
                newQueryString += `&device=${latestDevice}`;
            }
            window.history.replaceState({}, '', newQueryString);

            // Filter history dengan parameter yang diperbarui
            filterHistory(queryDevice, queryStart, queryEnd);



            $('#device-select').select2({
                sorter: function(data) {
                    return data.sort(function(a, b) {
                        return a.text.localeCompare(b.text);
                    });
                    if (historyData.length === 0) {
                        $('#notification-container').css('opacity', '1');
                        setTimeout(function() {
                            $('#notification-container').css('opacity', '0');
                        }, 5000);
                    }
                }
            });

            var startDate;
            var endDate;
            var selectedDevice;
            var selectedDates;


            // Mendapatkan waktu sekarang
            var now = new Date();

            // Mengatur waktu mulai 3 jam sebelum waktu sekarang
            var start = new Date(now.getTime() - 3 * 60 * 60 * 1000); // Mengurangi 3 jam dalam milidetik

            flatpickr("#date_range", {
                mode: "range",
                dateFormat: "Y-m-d H:i",
                enableTime: true,
                defaultDate: [
                    start, // Waktu mulai 3 jam sebelum waktu sekarang
                    now // Waktu akhir adalah waktu sekarang
                ],
                onClose: function(selectedDates) {
                    startDate = selectedDates[0];
                    endDate = selectedDates[1];
                    selectedDevice = $('#device-select').val();
                    var formattedStartDate = formatDateForUrl(startDate);
                    var formattedEndDate = formatDateForUrl(endDate);
                    var queryString = `?start=${formattedStartDate}&end=${formattedEndDate}`;
                    window.history.pushState({}, '', window.location.pathname + queryString);
                    $(this.element).trigger('change');
                    filterHistory(selectedDevice, startDate, endDate, selectedDates);
                }
            });


            function formatDateForUrl(date) {
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var hours = String(date.getHours()).padStart(2, '0');
                var minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}`;
            }

            var map = L.map('map').setView([0, 0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = [];
            var devicePolylines = {};

            function filterMap(historyData, showSpeed, showAccuracy) {
                if (!Array.isArray(historyData) || historyData.length === 0) {
                    // Hapus semua marker dan garis pada peta
                    markers.forEach(marker => map.removeLayer(marker));
                    Object.values(devicePolylines).forEach(polyline => map.removeLayer(polyline));
                    // Kosongkan arrays
                    markers = [];
                    devicePolylines = {};
                    return; // Keluar dari fungsi
                }
                if (!Array.isArray(historyData)) {
                    console.error("Data is not an array.");
                    return;
                }

                if (historyData.length === 0) {
                    $('#notification-container').css('opacity', '1');
                    setTimeout(function() {
                        $('#notification-container').css('opacity', '0');
                    }, 5000);
                }

                markers.forEach(marker => {
                    map.removeLayer(marker);
                });
                Object.values(devicePolylines).forEach(polyline => {
                    map.removeLayer(polyline);
                });

                markers = [];
                devicePolylines = {};

                historyData.forEach(historyItem => {
                    var deviceId = historyItem.device_id;
                    var deviceHistory = historyData.filter(item => item.device_id === deviceId);
                    var startHistoryItem = deviceHistory[0];
                    var endHistoryItem = deviceHistory[deviceHistory.length - 1];

                    var startIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: "<i class='fas fa-map-marker-alt' style='font-size: 40px; color: lightgreen;'></i>",
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

                    var startMarker = L.marker([parseFloat(startHistoryItem.latitude), parseFloat(
                        startHistoryItem.longitude)], {
                        icon: startIcon
                    }).addTo(map);
                    markers.push(startMarker);

                    var endMarker = L.marker([parseFloat(endHistoryItem.latitude), parseFloat(endHistoryItem
                        .longitude)], {
                        icon: endIcon
                    }).addTo(map);
                    markers.push(endMarker);

                    var startPopupContent = '<div style="text-align: center;"><b>Start</b></div>' +
                        '<i class="fas fa-car"></i> <b>Nama Device:</b> ' + startHistoryItem.device.name +
                        '<br><i class="fas fa-map-marker-alt"></i> <b>Latlng:</b> ' + startHistoryItem
                        .latitude + ', ' + startHistoryItem.longitude +
                        '<br><i class="fas fa-calendar-alt"></i> <b>Date Time:</b> ' + startHistoryItem
                        .date_time;
                    startMarker.bindPopup(startPopupContent);

                    var endPopupContent = '<div style="text-align: center;"><b>End</b></div>' +
                        '<i class="fas fa-car"></i> <b>Nama Device:</b> ' + endHistoryItem.device.name +
                        '<br><i class="fas fa-map-marker-alt"></i> <b>Latlng:</b> ' + endHistoryItem
                        .latitude + ', ' + endHistoryItem.longitude +
                        '<br><i class="fas fa-calendar-alt"></i> <b>Date Time:</b> ' + endHistoryItem
                        .date_time;
                    endMarker.bindPopup(endPopupContent);

                    var polylinePoints = [];
                    var color = 'blue'; // Default color
                    var weight = 1; // Default weight
                    var opacity = 1; // Default opacity

                    deviceHistory.forEach(historyItem => {
                        var lat = parseFloat(historyItem.latitude);
                        var lng = parseFloat(historyItem.longitude);
                        var speed = parseFloat(historyItem.speeds); // Get speed value
                        var accuracy = parseFloat(historyItem.accuracy); // Get accuracy value

                        // Log the accuracy value to debug
                        console.log('Accuracy:', accuracy);

                        if (showSpeed && !isNaN(speed)) {
                            // Set color based on speed value
                            if (speed >= 0 && speed < 20) {
                                color = 'green';
                                weight = 5;
                            } else if (speed >= 20 && speed < 40) {
                                color = 'yellow';
                                weight = 3;
                            } else {
                                color = 'red';
                                weight = 1;
                            }
                        }

                        if (showAccuracy && !isNaN(accuracy)) {
                            // Set opacity based on accuracy value
                            if (accuracy >= 0 && accuracy <= 10) {
                                opacity = 1.0;
                            } else if (accuracy > 10 && accuracy <= 20) {
                                opacity = 0.7;
                            } else if (accuracy > 20 && accuracy <=
                                100) { // Adjusted to consider values between 20 and 100
                                opacity = 0.3;
                            } else {
                                opacity = 0.1; // For values greater than 100
                            }
                        }

                        polylinePoints.push([lat, lng]);
                    });

                    if (!devicePolylines[deviceId]) {
                        devicePolylines[deviceId] = L.polyline(polylinePoints, {
                            color: color,
                            weight: weight,
                            opacity: opacity
                        }).addTo(map);
                    } else {
                        devicePolylines[deviceId].setLatLngs(polylinePoints);
                    }

                    var polylinePopupContent = '<b>Speed:</b> ' + (deviceHistory[0].speeds || 'N/A') +
                        '<br><b>Accuracy:</b> ' + (deviceHistory[0].accuracy || 'N/A');
                    devicePolylines[deviceId].bindPopup(polylinePopupContent);
                });

                var allPolylines = Object.values(devicePolylines);
                var bounds = L.featureGroup(allPolylines).getBounds();
                map.fitBounds(bounds);
            }


            function showNotification(message) {
                const notificationContainer = $('#notification-container');
                const notification = $('#notification');
                notification.html('<i class="fas fa-exclamation-circle"></i> ' + message);
                notificationContainer.css('opacity', '1');
                setTimeout(function() {
                    notificationContainer.css('opacity', '0');
                }, 5000);
            }

            function filterHistory(selectedDevice, startDate, endDate) {
                if (startDate && endDate) {
                    $('#loading-overlay').show();
                    var start = new Date(startDate);
                    var end = new Date(endDate);
                    var formattedStartDate = start.getFullYear() + '-' + ('0' + (start.getMonth() + 1)).slice(-2) +
                        '-' + ('0' + start.getDate()).slice(-2) + ' ' + ('0' + start.getHours()).slice(-2) + ':' +
                        ('0' + start.getMinutes()).slice(-2);
                    var formattedEndDate = end.getFullYear() + '-' + ('0' + (end.getMonth() + 1)).slice(-2) + '-' +
                        ('0' + end.getDate()).slice(-2) + ' ' + ('0' + end.getHours()).slice(-2) + ':' +
                        ('0' + end.getMinutes()).slice(-2);
                    var queryString =
                        `?start=${formattedStartDate}&end=${formattedEndDate}&device=${selectedDevice}`;
                    window.history.pushState({}, '', window.location.pathname + queryString);

                    $.ajax({
                        url: "{{ route('filter.history') }}",
                        type: "POST",
                        data: {
                            selectedDevice: selectedDevice,
                            startDate: formattedStartDate,
                            endDate: formattedEndDate,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response && response.length > 0) {
                                historyData = response; // Save the response to historyData
                                var showSpeed = $('#speed-checkbox').is(":checked");
                                var showAccuracy = $('#accuracy-checkbox').is(":checked");
                                filterMap(historyData, showSpeed, showAccuracy);
                            } else {
                                // Tidak ada data riwayat yang ditemukan, hapus marker dan garis pada peta
                                markers.forEach(marker => map.removeLayer(marker));
                                Object.values(devicePolylines).forEach(polyline => map.removeLayer(
                                    polyline));
                                showNotification(
                                    "Tidak ada data yang ditemukan untuk rentang yang dipilih.");
                            }
                            $('#loading-overlay').hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('#loading-overlay').hide();
                        }
                    });
                }
            }

            $('#speed-checkbox').on('change', function() {
                var showSpeed = $(this).is(":checked");
                filterMap(historyData, showSpeed, false); // Only update speed
            });

            $('#accuracy-checkbox').on('change', function() {
                var showAccuracy = $(this).is(":checked");
                filterMap(historyData, false, showAccuracy); // Only update accuracy
            });

            $('#device-select').on('change', function() {
                var selectedDevice = $(this).val();
                var dateRange = $('#date_range').val();
                var startDate = dateRange.split(" to ")[0];
                var endDate = dateRange.split(" to ")[1];
                var queryString =
                    `?start=${startDate}&end=${endDate}&device=${selectedDevice}`;
                window.history.pushState({}, '', window.location.pathname + queryString);
                filterHistory(selectedDevice, startDate, endDate);

                // Tampilkan checkbox setelah memilih perangkat
                $('#speed-checkbox, #accuracy-checkbox').show();
                // Tampilkan filter-options setelah memilih perangkat
                $('#filter-options').show();


            });

            // Initialize the map with the default values or URL parameters
            var selectedDevice = $('#device-select').val();
            var dateRange = $('#date_range').val();
            var startDate = dateRange.split(" to ")[0];
            var endDate = dateRange.split(" to ")[1];
            filterHistory(selectedDevice, startDate, endDate);

        });
    </script>
@endsection
