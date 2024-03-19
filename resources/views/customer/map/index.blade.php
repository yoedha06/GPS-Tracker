@extends('layouts.customer')
@extends('layouts.navbarcustomer')

        <title>GEEX - Maps</title>


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
                <option value="{{ $device->id_device }}">{{ $device->user->name }} - {{ $device->name }}</option>
                @endforeach

            </select>

            <button id="reset-btn" class="btn btn-danger btn-sm">Reset</button>
        </div>
    </div>

    <div>
    <div id="device-names" data-device-names="{{ json_encode($deviceNames) }}" style="display: none;">
    </div>
    <div class="form-group date-time-input">
        <label for="date_range">Date range:</label>

        <div class="date-label" style="position: relative; left: 0;">
            <input type="text" id="date_range" class="form-control" placeholder="Start Date & Time - End Date & Time" style="text-align: left;">
            <i class="fas fa-calendar"></i>
        </div>
    </div>
   </div>

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
</style>
<script>
  $(document).ready(function() {
            // Initialize Select2
            $('#device-select').select2({
                sorter: function(data) {
                    return data.sort(function(a, b) {
                        return a.text.localeCompare(b.text);
                    });
                }
            });

            // Add change event listener
            $('#device-select').on('change', function() {
                var selectedValue = $(this).val();
                if (!selectedValue) {
                    alert('Device not selected!');
                }
            });

    // Menangani klik pada tombol "Reset"
$('#reset-btn').on('click', function () {
    // Mereset nilai Select2 ke null dan memicu perubahan
    $('#device-select').val(null).trigger('change');

    // Mereset tanggal menggunakan flatpickr jika Anda menggunakan flatpickr
    flatpickr("#date-range-input").clear();

    // Hapus semua marker dari peta
    map.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
            map.removeLayer(layer);
        }
    });

    // Hapus semua garis dari peta
    map.eachLayer(function (layer) {
        if (layer instanceof L.Polyline) {
            map.removeLayer(layer);
        }
    });

    // Memanggil fungsi filterMap() untuk memperbarui peta
    filterMap();
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

  // Mendefinisikan ikon kustom untuk "Start" dan "End"
var startIcon = L.icon({
    iconUrl: '/images/mapgreen.png',
    iconSize: [32, 32], // Sesuaikan ukuran ikon sesuai kebutuhan
    iconAnchor: [16, 32], // Sesuaikan jika diperlukan
    popupAnchor: [0, -16] // Sesuaikan jika diperlukan
});

var endIcon = L.icon({
    iconUrl: '/images/redmap.png',
    iconSize: [30, 30], // Mengatur ukuran marker menjadi 30x30 piksel
    iconAnchor: [15, 30], // Mengatur titik pusat marker agar berada di tengah-tengah
    popupAnchor: [1, -28] // Mengatur posisi popup
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

// Setelah itu, tambahkan kembali marker sesuai dengan data yang ada
for (var i = 0; i < historyData.length; i++) {
    var historyItem = historyData[i];
    var lat = parseFloat(historyItem.latitude);
    var lng = parseFloat(historyItem.longitude);
    var speed = parseFloat(historyItem.speeds);
    var accuracy = parseFloat(historyItem.accuracy);
    var deviceId = historyItem.device_id;

    var isDeviceSelected = !selectedDevice || selectedDevice.includes(deviceId);

    if (isDeviceSelected &&
        (!startDate || new Date(historyItem.date_time) >= startDate) &&
        (!endDate || new Date(historyItem.date_time) <= endDate)) {

        // Check if it's start or end marker
        var isStart = !startMarkers[deviceId];
        var markerIcon = isStart ? startIcon : endIcon;

        var marker = L.marker([lat, lng], { icon: markerIcon });
        markers.push(marker);

        var popupContent =
            `<div>
                Device: ${deviceNames[deviceId]}<br>
                Latitude: ${lat.toFixed(6)}<br>
                Longitude: ${lng.toFixed(6)}<br>
                Date & Time: ${historyItem.date_time}<br>
            </div>`;

        if (isStart) {
            popupContent += "<b>Start</b>";
            startMarkers[deviceId] = marker;
        } else {
            popupContent += "<b>End</b>";
            endMarkers[deviceId] = marker;
            marker.setIcon(endIcon); // Set icon to endIcon for end markers
        }

        marker.bindPopup(popupContent);

        if (!devicePolylines[deviceId]) {
            devicePolylines[deviceId] = L.polyline([], {}).addTo(map);
        }

        devicePolylines[deviceId].addLatLng([lat, lng]);

        // Add marker to map only if it's start or end marker
        if (isStart || !isDeviceSelected[deviceId]) {
            marker.addTo(map);
        }
    }
}

// Update popup content for start and end markers
Object.keys(startMarkers).forEach(deviceId => {
    var startMarker = startMarkers[deviceId];
    var startPopupContent = startMarker.getPopup().getContent();
    startMarker.getPopup().setContent(startPopupContent + " (Start)");
});

Object.keys(endMarkers).forEach(deviceId => {
    var endMarker = endMarkers[deviceId];
    var endPopupContent = endMarker.getPopup().getContent();
    endMarker.getPopup().setContent(endPopupContent + " (End)");
});

// Update polyline styles
Object.values(devicePolylines).forEach(polyline => {
    var color = 'blue'; // Default color
    var weight = 3;
    var opacity = 1.0;

    // Change color for end marker polyline to red
    if (polyline.getLatLngs().length > 0) {
        var lastLatLng = polyline.getLatLngs()[polyline.getLatLngs().length - 1];
        var lastDeviceId = historyData.find(item => item.latitude == lastLatLng.lat && item.longitude == lastLatLng.lng).device_id;
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





        Object.values(devicePolylines).forEach(polyline => {
    var color;
    var weight;
    var popupContent;

    var accuracy; // Deklarasikan variabel accuracy di sini

    polyline.getLatLngs().forEach(point => {
        var data = historyData.find(data => {
            // Membandingkan koordinat dengan toleransi 0.0001 (sekitar 11 meter)
            return Math.abs(parseFloat(data.latitude) - point.lat) < 0.0001 &&
                   Math.abs(parseFloat(data.longitude) - point.lng) < 0.0001;
        });

        if (data && data.speeds !== null && data.speeds !== undefined) {
            var speed = parseFloat(data.speeds);
            accuracy = parseFloat(data.accuracy); // Set nilai accuracy di sini
            console.log('Speed:', speed);
            console.log('Accuracy:', accuracy);

            if (speed <= 20) {
                color = 'green';  // If speed is less than or equal to 20 km/h, color is green
                weight = 10;  // Set polyline weight to wide
            } else if (speed <= 40) {
                color = 'yellow';  // If speed is between 21 and 40 km/h, color is yellow
                weight = 7;  // Set polyline weight to medium
            } else {
                color = 'red';  // If speed is greater than 40 km/h, color is red
                weight = 3;  // Set polyline weight to thin
            }
            popupContent = "Speed: " + speed.toFixed(2) + " km/h<br>Accuracy: " + accuracy.toFixed(2) + " meters";

            // Hentikan perulangan setelah menemukan data kecepatan pertama
            return;
        }
    });

    if (!color || !weight) {
        // Jika tidak ada data kecepatan yang ditemukan, berikan warna dan berat default
        color = 'blue';
        weight = 3;
        popupContent = "No speed data available";
    }

    var opacity;

    if (accuracy <= 10) {
        opacity = 1.0;   // Set opasitas ke 1.0 jika akurasi kurang dari atau sama dengan 10 (tidak transparan)
    } else if (accuracy <= 20) {
        opacity = 0.6;   // Set opasitas ke 0.5 jika akurasi di antara 11 dan 20 (sedang transparan)
    } else {
        opacity = 0.3;   // Set opasitas ke 0.1 jika akurasi di atas 20 (sangat transparan)
    }

    polyline.setStyle({
        color: color,       // Set color
        weight: weight, // Set thickness based on accuracy
        opacity: opacity    // Set opacity based on accuracy
    });



    // Ikatan popup ke polyline
    polyline.bindPopup(popupContent);
});


        // Fit bounds to markers
        var allMarkers = markers;
        var bounds = L.featureGroup(allMarkers).getBounds();
        map.fitBounds(bounds);
    }

    $('#device-select').change(function () {
        filterMap();
    });

    dateRangePicker.config.onChange.push(function (selectedDates, dateStr, instance) {
        filterMap();
    });
});

    </script>
@endsection
