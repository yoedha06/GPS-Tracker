@extends('layouts.customer')
@extends('layouts.navbarcustomer')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('content')
    <div id="main">
        <div class="form-group ml-3">
            <label for="device-select">Select Device:</label>
            <select id="device-select" class="form-control">
                <option value="" disabled selected>Select Device</option>
                @foreach ($devices as $device)
                    <option value="{{ $device->id_device }}">{{ $device->name }} || {{ $device->serial_number }}</option>
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
        $(document).ready(function() {
            $('#device-select').select2();

            var startDatePicker = flatpickr("#start_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: new Date().setHours(0, 0, 0, 0) // Set default date to today at 00:00
            });

            var endDatePicker = flatpickr("#end_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                defaultDate: new Date().setHours(23, 0, 0, 0) // Set default date to today at 23:00
            });

            var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var historyData = @json($history);
            var deviceName = {!! $devices->pluck('name') !!};
            var serialNumber = {!! $devices->pluck('serial_number') !!};

            var layerGroup = L.layerGroup();
            var polylinePoints = [];

            function filterMap() {
                var startDate = startDatePicker.selectedDates[0];
                var endDate = endDatePicker.selectedDates[0];
                var selectedDevice = $('#device-select').val(); // Ambil nilai perangkat yang dipilih

                map.removeLayer(layerGroup);
                layerGroup.clearLayers();
                polylinePoints = [];

                var polylineWeight;
                var dataFound = false;
                for (var i = 0; i < historyData.length; i++) {
                    var date_time = new Date(historyData[i].date_time);
                    var hours = date_time.getHours();
                    var minutes = date_time.getMinutes();
                    var seconds = date_time.getSeconds();
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;
                    var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

                    // Tambahkan logika untuk memeriksa apakah tanggal dalam rentang yang dipilih
                    // dan perangkat sesuai dengan yang dipilih (jika dipilih)
                    if (date_time >= startDate && date_time <= endDate && (!selectedDevice || historyData[i]
                            .device_id == selectedDevice)) {
                        var lat = parseFloat(historyData[i].latitude);
                        var lng = parseFloat(historyData[i].longitude);
                        var speed = parseFloat(historyData[i].speeds);
                        var accuracy = parseFloat(historyData[i].accuracy);

                        var opacity;
                        if (accuracy <= 10) {
                            opacity = 1.0;
                        } else if (accuracy > 10 && accuracy <= 20) {
                            opacity = 0.75;
                        } else {
                            opacity = 0.5;
                        }

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

                        var circleMarker = L.circleMarker([lat, lng], {
                            radius: 0,
                            color: color,
                            stroke: false,
                        });

                        layerGroup.addLayer(circleMarker);
                        polylinePoints.push([lat, lng]);

                        var polylineColor = speed < 20 ? "green" : speed >= 20 && speed <= 40 ? "yellow" : "red";

                        if (polylinePoints.length > 1) {
                            var polyline = L.polyline(polylinePoints.slice(-2), {
                                color: polylineColor,
                                weight: polylineWeight,
                                opacity: opacity,
                            }).addTo(map);

                            var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m";
                            polyline.bindPopup(popupContent);
                        }

                        var marker = L.marker([lat, lng]).addTo(map);

                        var popupContent =
                            "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis;'>" +
                            "<div style='font-size: 12px;'>" +
                            "Device Name: " + deviceName +
                            "<br>Serial Number: " + serialNumber +
                            "<br>Latitude: " + lat.toFixed(6) +
                            "<br>Longitude: " + lng.toFixed(6) +
                            "<br>Date & Time: " + date_time.toISOString().split('T')[0] + ' ' + timeString;
                        "</div>" +
                        "</div>";

                        var popupOptions = {
                            maxWidth: 200
                        };

                        marker.bindPopup(popupContent, popupOptions);
                        polylinePoints.push([lat, lng]);
                    }
                }

                var allLatLngs = polylinePoints.map(function(latlng) {
                    return L.latLng(latlng[0], latlng[1]);
                });

                layerGroup.addTo(map);
                map.fitBounds(L.latLngBounds(allLatLngs));
            }

            // Trigger filterMap function when start or end date changes
            // startDatePicker.config.onChange.push(filterMap);
            endDatePicker.config.onChange.push(filterMap);

            // Trigger filterMap function when device selection changes
            $('#device-select').change(function() {
                filterMap(); // Panggil filterMap() saat perangkat dipilih
            });

            // Initial filtering
            filterMap();
        });
    </script>
@endsection
