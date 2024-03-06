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
        <select id="device-select" class="form-control">
            <option></option>
            @foreach($devices as $device)
                <option value="{{ $device->id_device }}">{{ $device->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="start_date">Tanggal dan Waktu Mulai</label>
        <input type="text" id="start_date" placeholder="Start Date & Time">
    </div>

    <div class="form-group">
        <label for="end_date">Tanggal dan Waktu Selesai</label>
        <input type="text" id="end_date" placeholder="End Date & Time">
    </div>

    <button id="filterButton" class="btn btn-primary">Filter</button>
    <div id="map" style="height: 50%; width: 100%;"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include Select2 JS -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
    var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var customIcon = L.icon({
        iconUrl: '/assets/img/hitamm-removebg-preview.png',
        iconSize: [26, 37],
        iconAnchor: [10, 39],
        popupAnchor: [3, -34],
        shadowSize: [41, 41]
    });

    var historyData = @json($history);
    var deviceName = {!! $devices->pluck('name') !!};

    var layerGroup = L.layerGroup(); // Create a layer group to hold markers and polylines
    var polylinePoints = [];

    var startDatePicker, endDatePicker; // Define the variables outside $(document).ready()

    // Function to reset map and markers based on selected date range
    function filterMap() {
        var startDate = startDatePicker.selectedDates[0];
        var endDate = endDatePicker.selectedDates[0];

        // Clear existing markers and polyline
        layerGroup.clearLayers();
        polylinePoints = [];

        // Iterate through historyData and add markers and polyline based on selected date range
        historyData.forEach(function(history, index) {
            var historyDate = new Date(history.date_time);
            if (historyDate >= startDate && historyDate <= endDate) {
                var latitude = parseFloat(history.latitude);
                var longitude = parseFloat(history.longitude);
                var speed = parseFloat(history.speeds);
                var accuracy = parseFloat(history.accuracy);

                // Add marker with popup
                var marker;
                if (index === 0) {
                    marker = L.marker([latitude, longitude], {icon: customIcon}).addTo(layerGroup);
                    var popupContent = "<b>Device Name:</b> " + deviceName + "<br>" +
                                       "<b>Latitude:</b> " + latitude.toFixed(7) + "<br>" +
                                       "<b>Longitude:</b> " + longitude.toFixed(7) + "<br>" +
                                       "<b>Date & Time:</b> " + history.date_time + "<br>" +
                                       "<b>Bounds:</b> " + history.bounds + "<br>" +
                                       "<b>Start</b>";
                } else if (index === historyData.length - 1) {
                    marker = L.marker([latitude, longitude], {icon: customIcon}).addTo(layerGroup);
                    var popupContent = "<b>Device Name:</b> " + deviceName + "<br>" +
                                       "<b>Latitude:</b> " + latitude.toFixed(7) + "<br>" +
                                       "<b>Longitude:</b> " + longitude.toFixed(7) + "<br>" +
                                       "<b>Date & Time:</b> " + history.date_time + "<br>" +
                                       "<b>Bounds:</b> " + history.bounds + "<br>" +
                                       "<b>End</b>";
                } else {
                    marker = L.marker([latitude, longitude], {icon: customIcon}).addTo(layerGroup);
                    var popupContent = "<b>Device Name:</b> " + deviceName + "<br>" +
                                       "<b>Latitude:</b> " + latitude.toFixed(7) + "<br>" +
                                       "<b>Longitude:</b> " + longitude.toFixed(7) + "<br>" +
                                       "<b>Date & Time:</b> " + history.date_time + "<br>" +
                                       "<b>Bounds:</b> " + history.bounds;
                }
                marker.bindPopup(popupContent);

                // Add polyline point
                polylinePoints.push([latitude, longitude]);

                // Add polyline if there are enough points
                if (polylinePoints.length > 1) {
                    // Calculate polyline color and weight based on speed
                    var polylineColor = speed < 20 ? "green" : speed >= 20 && speed <= 40 ? "yellow" : "red";
                    var polylineWeight = speed < 20 ? 5 : speed >= 20 && speed <= 40 ? 8 : 3;

                    // Calculate polyline opacity based on accuracy
                    var polylineOpacity = accuracy >= 0 && accuracy <= 10 ? 1 : accuracy > 10 && accuracy <= 20 ? 0.7 : 0.4;

                    var polyline = L.polyline(polylinePoints, {
                        color: polylineColor,
                        weight: polylineWeight,
                        opacity: polylineOpacity
                    }).addTo(layerGroup);
                }
            }
        });

        // Add layerGroup to the map
        layerGroup.addTo(map);

        // Set map view to fit the new markers
        var allLatLngs = polylinePoints.map(function(latlng) {
            return L.latLng(latlng[0], latlng[1]);
        });
        map.fitBounds(L.latLngBounds(allLatLngs));
    }

    $(document).ready(function() {
    $('#device-select').select2();

    startDatePicker = flatpickr("#start_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        defaultDate: new Date().setHours(0, 0, 0, 0) // Set default date to today at 00:00
    });

    endDatePicker = flatpickr("#end_date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        defaultDate: new Date().setHours(23, 0, 0, 0) // Set default date to today at 23:00
    });

    function getTodayDate() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
        var yyyy = today.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    }

    $('#device-select').on('change', function() {
        // Perform data retrieval and manipulation as needed
    });
    function setStartDateEndDateToToday() {
    // Ambil nilai default jika tidak ada yang dipilih
    var selectedStartDate = startDatePicker.selectedDates.length > 0 ? startDatePicker.selectedDates[0] : new Date().setHours(0, 0, 0, 0);
    var selectedEndDate = endDatePicker.selectedDates.length > 0 ? endDatePicker.selectedDates[0] : new Date().setHours(23, 0, 0, 0);

    startDatePicker.setDate(selectedStartDate); // Set start date to today at 00:00 or selected value
    endDatePicker.setDate(selectedEndDate); // Set end date to today at 23:00 or selected value
}

    $('#filterButton').on('click', function() {
        setStartDateEndDateToToday();
        filterMap();
    });
});
</script>




@endsection

