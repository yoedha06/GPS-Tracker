@extends('layouts.customer')


<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



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


    <div class="date-time-input ml-auto">
        <div class="d-flex flex-row-reverse">
            <button id="filter-button" class="btn btn-primary ml-2">Filter</button> <!-- Tombol filter -->
            <div class="date-label mr-2">
                <label for="end-date">End Date:</label>
                <div class="input-group">
                    <input type="date" id="end-date" class="form-control">
                    <div class="input-group-append"></div>
                </div>
            </div>
            <div class="date-label">
                <label for="start-date">Start Date:</label>
                <div class="input-group">
                    <input type="date" id="start-date" class="form-control">
                    <div class="input-group-append"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="map" style="height: 50%; width: 100%;"></div>

    <div id="map" style="height: 50%; width: 100%;"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .date-time-input {
        display: flex;
        align-items: center;
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

    #filter-button {
        margin-left: 10px;
    }
</style>
<script>
    var map = L.map('map').setView([-6.8955992330108895, 107.54240919668543], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var historyData = {!! json_encode($history) !!};
    var deviceData = {!! json_encode($device) !!};

    var layerGroup = L.layerGroup().addTo(map);
    var polylinePoints = [];

    // Function to filter history data by date range
    function filterHistoryDataByDate(startDate, endDate) {
        return historyData.filter(function(record) {
            var recordDate = new Date(record.waktu);
            return recordDate >= startDate && recordDate <= endDate;
        });
    }

    // Function to refresh markers and polyline based on filtered data
function refreshMapMarkersAndPolyline(filteredData) {
    // Clear existing markers and polyline
    layerGroup.clearLayers();
    polylinePoints = [];

    // Loop through filtered data to create markers and build polyline points
    filteredData.forEach(function(record) {
        var latlngArr = record.latlng.split(", ");
        var lat = parseFloat(latlngArr[0]);
        var lng = parseFloat(latlngArr[1]);
        var speed = parseFloat(record.speeds);
        var accuracy = parseFloat(record.accuracy);

        // Check if lat and lng are valid numbers
        if (!isNaN(lat) && !isNaN(lng)) {
            // Find device information
            var deviceId = record.device_id;
            var deviceInfo = Array.isArray(deviceData) ? deviceData.find(function(device) {
                return device.id === deviceId;
            }) : null;
            var deviceName = deviceInfo ? deviceInfo.name : "Unknown";

            var color;
            if (speed < 20) {
                color = 'green';
            } else if (speed >= 20 && speed <= 40) {
                color = 'yellow';
            } else {
                color = 'red';
            }

            var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m<br>Device: " + deviceName;

            // Create circle marker for each point
            var circleMarker = L.circleMarker([lat, lng], {
                radius: 10,
                stroke: false,
                color: color,
                fillOpacity: 1
            }).bindPopup(popupContent).addTo(layerGroup);

            // Push coordinates to polylinePoints array
            polylinePoints.push([lat, lng]);
        }
    });

    // Create polyline with the new polylinePoints array
    var polyline = L.polyline(polylinePoints, {
        color: 'blue', // Set the color of the polyline
        weight: 10, // Set the weight of the polyline
        opacity: 0.5 // Set the opacity of the polyline
    }).addTo(layerGroup);

    // Check if polylinePoints is not empty before fitting bounds
    if (polylinePoints.length > 0) {
        // Fit the map to the bounds of the polyline
        map.fitBounds(polyline.getBounds());
    }
}


    // Event listener for when the date inputs change
    $("#start-date, #end-date").change(function() {
        var startDate = new Date($("#start-date").val());
        var endDate = new Date($("#end-date").val());

        // Filter history data by date range
        var filteredData = filterHistoryDataByDate(startDate, endDate);

        // Refresh markers and polyline based on filtered data
        refreshMapMarkersAndPolyline(filteredData);
    });

    // Initially load markers and polyline based on full history data
    refreshMapMarkersAndPolyline(historyData);
</script>






<script>
        $(document).ready(function() {
    $('#device-select').on('change', function() {
        var deviceId = $(this).val();

        $.ajax({
            url: '/admin/get-related-data/' + deviceId,
            method: 'GET',
            success: function(data) {
                var historyData = data.devices.find(device => device.id == deviceId).history;
                $('#related-data-select').empty(); // Kosongkan dropdown terlebih dahulu
                $.each(historyData, function(index, history) {
                    $('#related-data-select').append('<option value="' + history.id + '">' + history.name + '</option>');
                });
                $('#related-data-select').trigger('change');
            },
            error: function(error) {
                console.error('Error fetching related data:', error);
            }
        });
    });

    $('#device-select').select2();
});

    </script>
@endsection

