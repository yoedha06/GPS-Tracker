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
                                <li class="breadcrumb-item"><a href="/customer"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-geo-alt-fill"></i>
                                    Maps
                                    History</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group ml-3"
            style="display: flex; flex-direction: column; width: 100%; margin-top: 37px; margin-bottom: 0px;">
            <div class="d-flex" style="gap:5px;">
                <select id="device-select" class="form-select input" style="width: 100%;">
                    <option value="" disabled selected>Select Devicee</option>
                    @foreach ($devices as $device)
                        <option value="{{ $device->id_device }}">{{ $device->user->name }} - {{ $device->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group ml-3 date-time-input"
            style="display: flex; flex-direction: column; width: 100%; margin-top: 5px;">
            <label for="date_range" style="padding-left:2px;">Date range:</label>
            <div class="date-label" style="position: relative; left: 0; margin-right: 0px;">
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
    </div>




    <div id="map" style="height: 420px; width: 100%;">
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

        #filter-options {
            display: none;
            /* Sembunyikan container filter */
        }
    </style>

    <script>
        var dateRangePicker;

        $(document).ready(function() {
            // Inisialisasi tanggal sebelum digunakan
            var defaultStartDate = new Date();
            defaultStartDate.setHours(0, 0, 0, 0);

            // Set default end date to today at 23:59
            var defaultEndDate = new Date();
            defaultEndDate.setHours(23, 59, 59, 999);

            var dateRangePicker = flatpickr("#date_range", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: [defaultStartDate, defaultEndDate],
                mode: "range",
                onChange: function(selectedDates, dateStr, instance) {
                    filterMap();
                }
            });

            // Nonaktifkan checkbox "Speed" dan "Accuracy" secara default
            $('#speed-checkbox').prop('disabled', true);
            $('#accuracy-checkbox').prop('disabled', true);

            // Mengatur opsi checkbox "Speed" dan "Accuracy" menjadi tidak terlihat saat halaman dimuat
            $('#filter-options').css('display', 'none');

            // Dapatkan URL saat ini
            var currentURL = new URL(window.location.href);

            // Dapatkan tanggal awal dan akhir dari URL
            var urlStartDate = currentURL.searchParams.get("start");
            var urlEndDate = currentURL.searchParams.get("end");

            // Tentukan elemen formulir untuk rentang tanggal
            var dateRangeInput = document.querySelector("#date_range");

            // Atur nilai awal formulir dengan tanggal dari URL jika tersedia, atau gunakan default jika tidak
            var defaultStartDate = new Date();
            defaultStartDate.setHours(0, 0, 0, 0);

            // Set default end date to today at 23:59
            var defaultEndDate = new Date();
            defaultEndDate.setHours(23, 59, 59, 999);

            if (urlStartDate && urlEndDate) {
                var startDate = new Date(urlStartDate);
                var endDate = new Date(urlEndDate);
                dateRangeInput.value = formatDate(startDate) + " to " + formatDate(endDate);
            } else {
                // Atur nilai awal formulir dengan tanggal default jika URL tidak berisi tanggal
                dateRangeInput.value = formatDate(defaultStartDate) + " to " + formatDate(defaultEndDate);
            }

            // Menambahkan event listener untuk perubahan pada dropdown #device-select
            $('#device-select').on('change', function() {
                // Memeriksa apakah sebuah perangkat telah dipilih
                selectedDevice = $(this).val();
                if (selectedDevice) {
                    // Jika perangkat telah dipilih, tampilkan opsi checkbox "Speed" dan "Accuracy"
                    $('#filter-options').css('display', 'block');
                } else {
                    // Jika tidak ada perangkat yang dipilih, sembunyikan opsi checkbox "Speed" dan "Accuracy"
                    $('#filter-options').css('display', 'none');
                }
            });

            var deviceNames = {!! json_encode($deviceNames) !!};
            var historyData = {!! $history->toJson() !!};

            var defaultStartDate = new Date();
            defaultStartDate.setHours(0, 0, 0, 0);

            // Set default end date to today at 23:00
            var defaultEndDate = new Date();
            defaultEndDate.setHours(23, 0, 0, 0);

            if (!urlStartDate || !urlEndDate) {
                dateRangePicker.setDate([defaultStartDate, defaultEndDate]);
                var startFormatted = formatDate(defaultStartDate);
                var endFormatted = formatDate(defaultEndDate);
                var origin = window.location.origin;
                var newURL = '/customer/map?start=' + startFormatted + '&end=' + endFormatted;

                // Atur URL pada history browser
                window.history.replaceState(null, null, newURL);
            }

            // Menetapkan rentang tanggal ke hari ini saat reload
            dateRangePicker.setDate([defaultStartDate, defaultEndDate]);
        });

        // Menghapus pilihan perangkat secara otomatis saat reload
        $('#device-select').val(null).trigger('change');

        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var markers = [];
        var devicePolylines = {};

        // Fungsi untuk memformat tanggal ke format yang diinginkan (YYYY-MM-DD HH:MM)
        function formatDate(date) {
            if (!date) return '';
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Tambahkan 1 karena bulan dimulai dari 0
            var day = ('0' + date.getDate()).slice(-2);
            var hours = ('0' + date.getHours()).slice(-2);
            var minutes = ('0' + date.getMinutes()).slice(-2);

            return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes;
        }

        function filterMap() {
            var startDate, endDate, startFormatted, endFormatted, selectedDevice;

            // Ambil nilai rentang tanggal jika tersedia
            if ($('#date_range').val()) {
                startDate = dateRangePicker.selectedDates[0];
                endDate = dateRangePicker.selectedDates[1];
                startFormatted = formatDate(startDate);
                endFormatted = formatDate(endDate);
            }

            // Ambil nilai perangkat yang dipilih
            selectedDevice = $('#device-select').val();

            var ajaxURL = 'http://127.0.0.1:8000/customer/map/filter';
            var requestData = {
                start: startFormatted,
                end: endFormatted,
                deviceId: selectedDevice
            };

            $.ajax({
                url: ajaxURL,
                type: 'GET',
                data: requestData,
                success: function(response) {
                    // ...
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Fungsi untuk memperbarui peta dengan data yang difilter
        function updateMap(filteredData) {
            clearMarkers(); // Hapus marker yang ada pada peta

            filteredData.forEach(data => {
                var lat = parseFloat(data.latitude);
                var lng = parseFloat(data.longitude);
                var deviceId = data.device_id;

                // Tambahkan marker ke peta
                var marker = L.marker([lat, lng]).addTo(map);

                // Tambahkan polyline jika belum ada
                if (!devicePolylines[deviceId]) {
                    devicePolylines[deviceId] = L.polyline([
                        [lat, lng]
                    ], {
                        color: 'red'
                    }).addTo(map);
                } else {
                    // Tambahkan koordinat baru ke polyline yang ada
                    var coordinates = devicePolylines[deviceId].getLatLngs();
                    coordinates.push([lat, lng]);
                    devicePolylines[deviceId].setLatLngs(coordinates);
                }

                markers.push(marker);
            });

            // Zoom peta untuk menyesuaikan dengan semua marker
            var group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds());
        }
    </script>
@endsection
