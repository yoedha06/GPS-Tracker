@extends('layouts.admin')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('content')
    <div id="main">
        <div class="form-group ml-3">
            <label for="device-select">Select Device:</label>
            <select id="device-select" class="form-control">
                <option value="" disabled selected>Select Device</option>
                @foreach ($devices as $device)
                    <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="date-time-input ml-auto">
            <label for="start-date" class="date-label">
                Start Date:
                <div class="input-group">
                    <input type="date" id="start-date" class="form-control">
                    <div class="input-group-append">
                    </div>
                </div>
            </label>
            <label for="end-date" class="date-label">
                End Date:
                <div class="input-group">
                    <input type="date" id="end-date" class="form-control">
                    <div class="input-group-append">
                    </div>
                </div>
            </label>
        </div>
        <div id="map" style="height: 50%; width: 100%;"></div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
        var map = L.map('map', {
            center: [-6.8955992330108895, 107.54240919668543],
            zoom: 13
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var historyData = @json($history);

        var layerGroup = L.layerGroup().addTo(map);
        var polylinePoints = [];

        for (var i = 0; i < historyData.length; i++) {
            var latlngStr = historyData[i].latlng;
            var latlngArr = latlngStr.split(", ");
            var lat = parseFloat(latlngArr[0]);
            var lng = parseFloat(latlngArr[1]);
            var speed = parseFloat(historyData[i].speeds);
            var accuracy = parseFloat(historyData[i].accuracy);

            var color;
            if (speed < 20) {
                color = 'green';
            } else if (speed >= 20 && speed <= 40) {
                color = 'yellow';
            } else {
                color = 'red';
            }

            var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m";

            var circleMarker = L.circleMarker([lat, lng], {
                radius: 10,
                stroke: false,
                color: color,
                fillOpacity: 1
            }).bindPopup(popupContent).addTo(layerGroup);

            polylinePoints.push([lat, lng]);

            // Adding a Polyline connecting the circle markers
            var polyline = L.polyline(polylinePoints, {
                color: color,
                weight: 5,
                opacity: 5
            }).addTo(layerGroup);
        }

        // Zoom ke lokasi marker pertama
        if (historyData.length > 0) {
            map.setView([historyData[0].latlng.split(',')[0], historyData[0].latlng.split(',')[1]], 15);
        }

        layerGroup.addTo(map);
    </script>

    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#device-select').select2();

            $('#start-date, #end-date').on('change', function() {
                var startDate = $('#start-date').val();
                var endDate = $('#end-date').val();
                var deviceId = $('#device-select').val();

                if (startDate && endDate && deviceId) {
                    // Send an AJAX request to the server
                    $.ajax({
                        type: 'GET',
                        url: '/history-filter', // Updated endpoint
                        data: {
                            startDate: startDate,
                            endDate: endDate,
                            deviceId: deviceId
                        },
                        success: function(response) {
                            // Update the map or handle the filtered data as needed
                            updateMap(response);
                        },
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                }
            });

            function updateMap(filteredData) {
                // Implement your logic to update the map with the filtered data
                console.log('Filtered Data:', filteredData);
            }
        });
    </script>
@endsection
