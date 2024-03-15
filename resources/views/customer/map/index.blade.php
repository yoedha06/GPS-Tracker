        @extends('layouts.customer')
        @extends('layouts.navbarcustomer')


        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        @section('content')
        <div id="main">
            <div class="form-group ml-3">
                <label for="device-select">Select Device:</label>
                <div class="d-flex">
                    <select id="device-select" class="form-select input">
                        <option value="" disabled selected>Select Device</option>
                        @foreach($devices as $device)
                        <option value="{{ $device->id_device }}">{{ $device->user->name }} || {{ $device->name }}</option>
                        @endforeach
                    </select>

                    <button id="reset-btn" class="btn btn-danger btn-sm">Reset</button>
                </div>
            </div>



            <div class="form-group">
                <label for="start_date">Tanggal dan Waktu Mulai</label>
                <input type="date" id="start_date" class="form-control" placeholder="Start Date & Time">
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal dan Waktu Selesai</label>
                <input type="date" id="end_date" class="form-control" placeholder="End Date & Time">
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
    // Inisialisasi Select2
    $('#device-select').select2();

    // Menangani klik pada tombol "See All History"
    $('#see-all-history-btn').on('click', function () {
        // Lakukan sesuatu ketika tombol "See All History" diklik
        console.log('Melihat semua riwayat');
        filterMap(); // Memanggil fungsi filterMap()
    });

    // Menangani klik pada tombol "Reset"
    $('#reset-btn').on('click', function () {
        // Mereset atau menghapus semua opsi yang dipilih pada select device
        $('#device-select').val(null).trigger('change');

        // Mengatur ulang picker tanggal mulai (start date) dan tanggal selesai (end date)
        var defaultStartDate = new Date();
        defaultStartDate.setHours(0, 0, 0, 0);

        var defaultEndDate = new Date();
        defaultEndDate.setHours(23, 0, 0, 0);

        startDatePicker.setDate(defaultStartDate);
        endDatePicker.setDate(defaultEndDate);

        filterMap(); // Memanggil fungsi filterMap() setelah mereset
    });

    // Variable untuk data history, defaultStartDate, defaultEndDate, dsb...
    // ...

    // Fungsi filterMap() dan kode lainnya...
});

var deviceName = {!! json_encode($devices->pluck('name')) !!};
var historyData = @json($history);
var defaultStartDate = new Date();
defaultStartDate.setHours(0, 0, 0, 0);

// Set default end date to today at 23:00
var defaultEndDate = new Date();
defaultEndDate.setHours(23, 0, 0, 0);

var startDatePicker = flatpickr("#start_date", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    defaultDate: defaultStartDate
});

var endDatePicker = flatpickr("#end_date", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    defaultDate: defaultEndDate
});

// Menangani perubahan pada picker tanggal akhir
endDatePicker.config.onChange.push(function(selectedDates, dateStr, instance) {
    filterMap(); // Memanggil fungsi filterMap() setelah perubahan pada tanggal akhir
});


var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var deviceNames = {!! json_encode($devices->pluck('name')) !!};
var layerGroup = L.layerGroup();
var polylinePoints = [];

function filterMap() {
    var startDate = startDatePicker.selectedDates[0];
    var endDate = endDatePicker.selectedDates[0];
    var selectedDevice = $('#device-select').val();

    map.eachLayer(function (layer) {
        if (layer instanceof L.Marker || layer instanceof L.Polyline) {
            map.removeLayer(layer);
        }
    });

    var newestIndex = -1;
    var oldestIndex = -1;

    // Hapus semua titik pada polylinePoints sebelum memproses data baru
    polylinePoints = [];

    // Cari indeks titik awal (Start) dan titik akhir (End)
    for (var i = 0; i < historyData.length; i++) {
        var historyItem = historyData[i];
        if ((!selectedDevice || historyItem.device_id == selectedDevice) &&
            (!startDate || new Date(historyItem.date_time.replace(" ", "T")) >= startDate) &&
            (!endDate || new Date(historyItem.date_time.replace(" ", "T")) <= endDate)) {
            if (newestIndex === -1 || new Date(historyItem.date_time.replace(" ", "T")) > new Date(historyData[newestIndex].date_time.replace(" ", "T"))) {
                newestIndex = i;
            }
            if (oldestIndex === -1 || new Date(historyItem.date_time.replace(" ", "T")) < new Date(historyData[oldestIndex].date_time.replace(" ", "T"))) {
                oldestIndex = i;
            }
        }
    }

    // Tentukan indeks titik awal (Start) dan titik akhir (End)
    var startIndex = oldestIndex;
    var endIndex = newestIndex;

    for (var i = 0; i < historyData.length; i++) {
        var historyItem = historyData[i];
        if ((!selectedDevice || historyItem.device_id == selectedDevice) &&
            (!startDate || new Date(historyItem.date_time.replace(" ", "T")) >= startDate) &&
            (!endDate || new Date(historyItem.date_time.replace(" ", "T")) <= endDate)) {

            var lat = parseFloat(historyItem.latitude);
            var lng = parseFloat(historyItem.longitude);
            var speed = parseFloat(historyItem.speeds);
            var accuracy = parseFloat(historyItem.accuracy);

            var markerColor = i === startIndex ? 'green' : i === endIndex ? 'red' : '';
            var markerIcon = i === startIndex ? 'green.jpeg' : i === endIndex ? 'red end.jpeg' : '';

            var marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30],
                    html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%;">
                            <img src="/images/${markerIcon}" alt="Marker Icon" style="width: 100%; height: 100%;">
                        </div>`
                })
            }).addTo(map);

            var popupContent =
    `<div>
        <img src="/images/${markerIcon}" alt="Marker Icon" style="width: 50px; height: 50px;">
        <br>
        Device: ${deviceName}<br>
        Latitude: ${lat.toFixed(6)}<br>
        Longitude: ${lng.toFixed(6)}<br>
        Date & Time: ${historyItem.date_time}
    </div>`;

if (i === startIndex) {
    popupContent = "<b>Start</b><br>" + popupContent;
}

if (i === endIndex) {
    popupContent = "<b>End</b><br>" + popupContent;
}

marker.bindPopup(popupContent, {
    autoPan: true,
    autoPanPadding: [50, 50] // Atur padding agar popup tetap berada di tengah
});

if (i === startIndex || i === endIndex) {
    marker.openPopup(); // Buka popup untuk "Start" dan "End"
}


            polylinePoints.push([lat, lng]);

            if (polylinePoints.length > 1) {
                var polyline = L.polyline(polylinePoints.slice(-2), {
                    color: speed < 20 ? "green" : speed >= 20 && speed <= 40 ? "yellow" : "red",
                    weight: speed < 20 ? 10 : speed >= 20 && speed <= 40 ? 5 : 2,
                    opacity: accuracy <= 10 ? 1.0 : accuracy > 10 && accuracy <= 20 ? 0.75 : 0.5
                }).addTo(map);

                polyline.bindPopup(`Speed: ${speed} km/h<br>Accuracy: ${accuracy} m`);
            }

            map.panTo([lat, lng]);
        }
    }
}



$('#device-select').change(function() {
    filterMap();
});

</script>
        @endsection

