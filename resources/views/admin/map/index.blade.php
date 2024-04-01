@extends('layouts.admin')

<title>GEEX - History Maps</title>

@extends('layouts.navbaradmin')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('content')
 <div class="notification-container" id="notification-container">
    <div class="notification" id="notification">
        <i class="fas fa-exclamation-circle"></i> Tidak ada data yang tersedia untuk perangkat dan rentang tanggal yang dipilih.
    </div>
</div>


<div id="main">
  <div class="form-group mb-3" style="width: 99%;"> <!-- Anda dapat mengubah nilai width sesuai dengan kebutuhan -->
    <label class="form-label">Select Device Users And Device</label>
    <select id="user_device" class="form-select input" style="width: 100%;">
        <option value="" disabled selected>Select</option>
        @foreach ($devices->sortBy('name') as $device)
            @if ($device->latestHistory && $device->user)
                <option value="{{ $device->id_device }}" data-device-id="{{ $device->id_device }}">
                    {{ $device->name }} - {{ $device->user->name }}
                </option>
            @endif
        @endforeach
    </select>
    <button id="reset-btn" class="btn btn-danger btn-sm" style="margin-left: auto;">Reset</button>
</div>

    <div id="device-names" data-device-names="{{ json_encode($deviceNames) }}" style="display: none;"></div>
    <div>
      <div class="form-group ml-3 date-time-input" style="display: flex; flex-direction: column; width: 100%;">
        <label for="date_range" style="margin-bottom: 5px;">Date range:</label>
        <div class="date-label" style="position: relative; left: 0;">
            <input type="text" id="date_range" class="form-control" placeholder="Start Date & Time - End Date & Time" style="width: 100%; padding-right: 30px;">
            <i class="fas fa-calendar" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);"></i>
        </div>
<div id="filter-options">
    <label for="speed-checkbox">
        <input type="checkbox" id="speed-checkbox" class="filter-checkbox" checked> Speed
    </label>
    <label for="accuracy-checkbox">
        <input type="checkbox" id="accuracy-checkbox" class="filter-checkbox" checked> Accuracy
    </label>
</div>

    </div>
</div>
</div>
<div id="map" style="height: 50%; width: 100%;"></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="path/to/jquery.fancybox.min.js"></script>

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
    width: 350px; /* Sesuaikan lebarnya sesuai kebutuhan Anda */
    text-align: left;
}
#map {
    z-index: 0; /* Atur nilai z-index yang sesuai */
    width: 100%;
        height: 300px; /* Tinggi peta pada layar non-mobile */
}


/* Atur lebar kontainer form */
@media (max-width: 768px) {
    #map {
        height: 400px; /* Sesuaikan tinggi peta untuk layar mobile */
    }
    #main {
        width: 100%; /* Lebar kontainer form menjadi 100% */
    }
}

.custom-div-icon {
    width: 32px;
    height: 32px;
}

.custom-div-icon i {
    color: green; /* Mengatur warna ikon menjadi merah */
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

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
    // Initialize Select2
    $('#user_device').select2({
        sorter: function(data) {
            return data.sort(function(a, b) {
                // Split the option text into device name and user name
                var aParts = a.text.split(' - ');
                var bParts = b.text.split(' - ');

                // Compare the device names first
                var deviceCompare = aParts[0].localeCompare(bParts[0]);
                if (deviceCompare !== 0) {
                    return deviceCompare;
                }

                // If device names are equal, compare the user names
                return aParts[1].localeCompare(bParts[1]);
            });
        }
    });
                // Add change event listener
                // $('#device-select').on('change', function() {
                //     var selectedValue = $(this).val();
                //     if (!selectedValue) {
                //         alert('Device not selected!');
                //     }
                // });

    $('#reset-btn').on('click', function () {
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
         var userNames = @json($userNames);
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
            var selectedDevice = $('#user_device').val();
 var filteredData = historyData.filter(function(item) {
            var currentDate = new Date(item.date_time);
            var isWithinDateRange = (!startDate || currentDate >= startDate) && (!endDate || currentDate <= endDate);
            return isWithinDateRange && (selectedDevice === null || selectedDevice.includes(item.device_id));
        });

        if (filteredData.length === 0) {
            // Tampilkan notifikasi
            $('#notification-container').css('opacity', '1'); // Menampilkan notifikasi

            // Atur timeout untuk menyembunyikan notifikasi setelah 5 detik (misalnya)
            setTimeout(function() {
                $('#notification-container').css('opacity', '0'); // Menyembunyikan notifikasi setelah 5 detik
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
        var isWithinDateRange = (!startDate || currentDate >= startDate) && (!endDate || currentDate <= endDate);

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
                            User: ${userNames[deviceId]}<br>
                            Latitude: ${lat.toFixed(6)}<br>
                            Longitude: ${lng.toFixed(6)}<br>
                            Date & Time: ${historyItem.date_time}<br>
                        </div>`;
    startMarkers[deviceId] = L.marker([lat, lng], { icon: markerIcon }).bindPopup(popupContent).addTo(map);
} else if (isEnd) { // If it's an end marker
    var markerIcon = endIcon;
    var popupContent = `<div>
                            <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">End</div>
                            Device: ${deviceNames[deviceId]}<br>
                            User: ${userNames[deviceId]}<br>
                            Latitude: ${lat.toFixed(6)}<br>
                            Longitude: ${lng.toFixed(6)}<br>
                            Date & Time: ${historyItem.date_time}<br>
                        </div>`;
    endMarkers[deviceId] = L.marker([lat, lng], { icon: markerIcon }).bindPopup(popupContent).addTo(map);
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
                // Fly to the new bounds with animation
                map.flyToBounds(bounds, {
                    animate: true,
                    duration: 1.5, // durasi animasi dalam detik
                    easeLinearity: 0.5 // pengaturan animasi
                });





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
                // console.log('Speed:', speed);
                // console.log('Accuracy:', accuracy);

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

       $('#user_device').change(function () {
    // Dapatkan ID perangkat yang dipilih
    var selectedDevice = $(this).val();

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

    // Memfilter peta berdasarkan perangkat yang dipilih
    filterMap(selectedDevice);
});


        dateRangePicker.config.onChange.push(function (selectedDates, dateStr, instance) {
            filterMap();
        });
    });

    </script>
@endsection
