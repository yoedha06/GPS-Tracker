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
            <option value="" disabled selected>Select Device</option>
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
    var map = L.map('map').setView([-6.895364793103795, 107.53971757412086], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var historyData = @json($history);

    var layerGroup = L.layerGroup();
    var polylinePoints = [];

    for (var i = 0; i < historyData.length; i++) {
        var latlngStr = historyData[i].latlng;
        var latlngArr = latlngStr.split(", ");
        var lat = parseFloat(latlngArr[0]);
        var lng = parseFloat(latlngArr[1]);
        var speed = parseFloat(historyData[i].speeds);
        var accuracy = parseFloat(historyData[i].accuracy);
        var bounds = historyData[i].bounds;

        // Menentukan warna marker lingkaran berdasarkan kecepatan
        var markerColor;
        if (speed < 20) {
            markerColor = 'green';
        } else if (speed >= 20 && speed <= 40) {
            markerColor = 'yellow';
        } else {
            markerColor = 'red';
        }

        // Membuat marker lingkaran dengan warna yang ditentukan
        var circleMarker = L.circleMarker([lat, lng], {
            radius: 0,
            color: markerColor,
            stroke: false,
        });
        layerGroup.addLayer(circleMarker);

        // Menambahkan titik dan batas ke polylinePoints
        polylinePoints.push({lat: lat, lng: lng, bounds: bounds});

        // Menentukan warna polyline berdasarkan akurasi
        var polylineColor;
        if (accuracy >= 10 && accuracy < 20) {
            polylineColor = 'green';
        } else if (accuracy >= 20 && accuracy <= 50) {
            polylineColor = 'yellow';
        } else {
            polylineColor = 'red';
        }

        // Menentukan berat polyline berdasarkan akurasi
        var polylineWeight;
        if (accuracy >= 10 && accuracy < 20) {
            polylineWeight = 10;
        } else if (accuracy >= 20 && accuracy <= 50) {
            polylineWeight = 5;
        } else {
            polylineWeight = 2;
        }

        // Membuat polyline dengan warna dan berat yang ditentukan
        if (polylinePoints.length > 1) {
            var polyline = L.polyline(polylinePoints.slice(-2), {
                color: polylineColor,
                weight: polylineWeight,
                opacity: 1.0,
            }).addTo(map);

            // Menambahkan popup dengan data ke polyline
            var popupContent = "Speed: " + speed + " km/h<br>Accuracy: " + accuracy + " m";
            polyline.bindPopup(popupContent);
        }

        // Menambahkan marker untuk data terbaru dan data terakhir
        if (i === 0 || i === historyData.length - 1) {
            var marker = L.marker([lat, lng], {
                color: i === 0 ? 'blue' : 'red' // Data terbaru berwarna biru, data terakhir berwarna merah
            }).addTo(map);

            // Menambahkan popup dengan informasi lengkap, termasuk batas
            var popupContent = "<div style='max-width: 200px; overflow: hidden; text-overflow: ellipsis;'>" +
                "<div style='font-size: 12px;'>" +
                "Latitude: " + lat.toFixed(7) +
                "<br>Longitude: " + lng.toFixed(7) +
                "<br>Bounds: " + bounds + // Menampilkan data batas di dalam popup

                "</div>" +
                "</div>";

            marker.bindPopup(popupContent);
        }
    }

    // Menambahkan layerGroup ke peta
    layerGroup.addTo(map);
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

