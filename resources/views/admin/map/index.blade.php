@extends('layouts.admin')

<title>GEEX - History Maps</title>

@


('layouts.navbaradmin')

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
<div class="row d-flex align-items-center">
    <div class="col-md-6">
        <div class="form-group mb-3" style="width: 99%;">
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
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group ml-3 date-time-input" style="display: flex; flex-direction: column; width: 100%;">
            <label for="date_range" style="margin-bottom: 5px;">Date range:</label>
            <div class="date-label" style="position: relative; left: 0;">
                <input type="text" id="date_range" class="form-control" placeholder="Start Date & Time - End Date & Time" style="width: 100%; padding-right: 30px;">
                <i class="fas fa-calendar" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);"></i>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        <button id="reset-btn" class="btn btn-danger btn-sm">Resett</button>
    </div>
</div>



<div id="filter-options">
    <label for="speed-checkbox">
        <input type="checkbox" id="speed-checkbox" class="filter-checkbox"  > Speed
    </label>
    <label for="accuracy-checkbox">
        <input type="checkbox" id="accuracy-checkbox" class="filter-checkbox"   > Accuracy
    </label>
</div>

    </div>
</div>
</div>
<div id="map-container">
    <div id="map"></div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
#map-container {
    width: 100%;
    position: relative;
}

#map {
    width: 100%;
    height: 100%; /* Set initial height to 100% */
}
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
#filter-options {
    display: none; /* Sembunyikan container filter */
}
</style>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>

    $(document).ready(function() {
        // Inisialisasi Select2 untuk dropdown perangkat
        $('#user_device').select2({
            sorter: function(data) {
                return data.sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                });
            }
        });

        // Cek apakah ada perangkat yang dipilih sebelumnya
        var selectedDevice = localStorage.getItem('selectedDevice');
        if (selectedDevice) {
            // Atur nilai dropdown perangkat
            $('#user_device').val(selectedDevice);
        }

        // Nonaktifkan checkbox "Speed" dan "Accuracy" secara default
        $('#speed-checkbox').prop('disabled', true);
        $('#accuracy-checkbox').prop('disabled', true);

        // Mengatur opsi checkbox "Speed" dan "Accuracy" menjadi tidak terlihat saat halaman dimuat
        $('#filter-options').css('display', 'none');

        // Menambahkan event listener untuk perubahan pada dropdown #device-select
        $('#user_device').on('change', function() {
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

        $('#reset-btn').on('click', function() {
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

        var currentURL = new URL(window.location.href);

        // Dapatkan tanggal awal dan akhir dari URL
        var urlStartDate = currentURL.searchParams.get("start");
        var urlEndDate = currentURL.searchParams.get("end");

        // Tentukan elemen formulir untuk rentang tanggal
        var dateRangeInput = document.querySelector("#date_range");

        // Atur nilai awal formulir dengan tanggal dari URL jika tersedia
        if (urlStartDate && urlEndDate) {
            var startDate = new Date(urlStartDate);
            var endDate = new Date(urlEndDate);
            dateRangeInput.value = formatDate(startDate) + " to " + formatDate(endDate);
        } else {
            // Atur nilai awal formulir dengan tanggal default jika URL tidak berisi tanggal
            dateRangeInput.value = formatDate(defaultStartDate) + " to " + formatDate(defaultEndDate);
        }

       function filterMap() {
    var selectedDevice = $('#user_device').val();
    var selectedUser = $('#user_select').val(); // Mengambil ID pengguna yang dipilih
    var startDate = dateRangePicker.selectedDates[0];
    var endDate = dateRangePicker.selectedDates[1];
    var startFormatted = formatDate(startDate);
    var endFormatted = formatDate(endDate);

    var ajaxURL = 'http://127.0.0.1:8000/admin/map/filter'; // Perbarui URL untuk menggunakan endpoint 'filter'
    var requestData = {
        start: startFormatted,
        end: endFormatted,
        deviceId: selectedDevice,
        userId: selectedUser // Sertakan ID pengguna dalam data permintaan
    };

    $.ajax({
        url: ajaxURL,
        type: 'GET', // Ubah metode permintaan menjadi POST
        data: requestData,
        success: function(response) {
            var filteredData = response.filteredData;
            updateMap(filteredData);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

        function updateMap(filteredData) {
            clearMarkers();

            filteredData.forEach(function(item) {
                var lat = parseFloat(item.latitude);
                var lng = parseFloat(item.longitude);
                var deviceId = item.device_id;

                // Tambahkan marker
                var marker = L.marker([lat, lng]).addTo(map);
                markers.push(marker);

                // Tambahkan popup ke marker
                var popupContent = `
                    <div>
                        <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">Device Information</div>
                        Device: ${deviceNames[deviceId]}<br>
                          User: ${userNames[deviceId]}<br>
                        Latitude: ${lat.toFixed(6)}<br>
                        Longitude: ${lng.toFixed(6)}<br>
                        Date & Time: ${item.date_time}<br>
                    </div>`;
                marker.bindPopup(popupContent);

                // Tambahkan polyline dan sesuaikan gaya sesuai kebutuhan
                if (!devicePolylines[deviceId]) {
                    devicePolylines[deviceId] = L.polyline([], {}).addTo(map);
                }
                devicePolylines[deviceId].addLatLng([lat, lng]);
            });

            // Tambahkan notifikasi
            if (filteredData.length === 0) {
                $('#notification-container').css('opacity', '1'); // Menampilkan notifikasi

                // Atur timeout untuk menyembunyikan notifikasi setelah beberapa detik
                setTimeout(function() {
                    $('#notification-container').css('opacity', '0'); // Menyembunyikan notifikasi setelah beberapa detik
                }, 4000); // Waktu dalam milidetik (misalnya 4000 milidetik = 4 detik)
            } else {
                $('#notification-container').css('opacity', '0'); // Sembunyikan notifikasi jika ada data yang cocok
            }

            // Sesuaikan warna dan ketebalan garis berdasarkan kecepatan dan akurasi
            filteredData.forEach(function(item) {
                var color;
                var weight;
                var opacity;
                var accuracy;

                // Mencari data kecepatan yang sesuai
                var speedData = historyData.find(data => {
                    // Membandingkan koordinat dengan toleransi 0.0001 (sekitar 11 meter)
                    return Math.abs(parseFloat(data.latitude) - parseFloat(item.latitude)) < 0.0001 &&
                        Math.abs(parseFloat(data.longitude) - parseFloat(item.longitude)) < 0.0001;
                });

                if (speedData && speedData.speeds !== null && speedData.speeds !== undefined) {
                    var speed = parseFloat(speedData.speeds);
                    accuracy = parseFloat(speedData.accuracy);

                    if ($('#speed-checkbox').is(':checked')) {
                        if (speed <= 20) {
                            color = 'green'; // Jika kecepatan kurang dari atau sama dengan 20 km/h, warna adalah hijau
                            weight = 10; // Set ketebalan garis menjadi tebal
                        } else if (speed <= 40) {
                            color = 'yellow'; // Jika kecepatan di antara 21 dan 40 km/h, warna adalah kuning
                            weight = 7; // Set ketebalan garis menjadi sedang
                        } else {
                            color = 'red'; // Jika kecepatan lebih dari 40 km/h, warna adalah merah
                            weight = 3; // Set ketebalan garis menjadi tipis
                        }
                    } else {
                        // Jika checkbox "Speed" tidak dicentang, atur warna dan ketebalan garis default
                        color = 'blue';
                        weight = 3;
                    }

                    // Bangun konten popup
                    var popupContent = "Speed: " + speed.toFixed(2) + " km/h<br>Accuracy: " + accuracy.toFixed(2) + " meters";

                    // Atur opasitas berdasarkan akurasi
                    if ($('#accuracy-checkbox').is(':checked')) {
                        if (accuracy <= 10) {
                            opacity = 1.0; // Set opasitas ke 1.0 jika akurasi kurang dari atau sama dengan 10 (tidak transparan)
                        } else if (accuracy <= 20) {
                            opacity = 0.6; // Set opasitas ke 0.6 jika akurasi di antara 11 dan 20 (sedang transparan)
                        } else {
                            opacity = 0.3; // Set opasitas ke 0.3 jika akurasi di atas 20 (transparan)
                        }
                    } else {
                        // Jika checkbox "Accuracy" tidak dicentang, atur opasitas default
                        opacity = 1.0;
                    }

                    // Atur gaya polyline
                    devicePolylines[item.device_id].setStyle({
                        color: color,
                        weight: weight,
                        opacity: opacity
                    });

                    // Ikatan popup ke polyline
                    devicePolylines[item.device_id].bindPopup(popupContent);
                }
            });
        }

        // Fungsi untuk menghapus semua marker dari peta
        function clearMarkers() {
            markers.forEach(marker => {
                map.removeLayer(marker);
            });
            markers = []; // Kosongkan array markers

            Object.values(devicePolylines).forEach(polyline => {
                map.removeLayer(polyline);
            });

            devicePolylines = {};
        }

        // Fungsi untuk memformat tanggal ke format yang diinginkan (YYYY-MM-DD HH:MM)
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2); // Tambahkan 1 karena bulan dimulai dari 0
            var day = ('0' + date.getDate()).slice(-2);
            var hours = ('0' + date.getHours()).slice(-2);
            var minutes = ('0' + date.getMinutes()).slice(-2);

            return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes;
        }

        // Panggil event handler change untuk memfilter peta saat halaman dimuat
        $('#user_device').change();

        // Event handler untuk perubahan pada dropdown #device-select
$('#user_device').change(function() {
    selectedDevice = $(this).val();
    var selectedDates = $('#date_range').val().split(" to ");
    var startFormatted = selectedDates[0];
    var endFormatted = selectedDates[1];
    var selectedUser = $('#user_select').val(); // Mengambil ID pengguna yang dipilih

    // Memeriksa apakah selectedUser adalah undefined atau null
    if (selectedUser === undefined || selectedUser === null) {
        selectedUser = ''; // Jika selectedUser tidak valid, beri nilai default kosong
    }

    // Membuat URL dengan parameter deviceId
     newURL = 'http://127.0.0.1:8000/admin/map?start=' + startFormatted + '&end=' + endFormatted + '&deviceId=' + selectedDevice + '&userId=' + selectedUser;

    // Jika selectedUser memiliki nilai yang valid, tambahkan userId ke URL
    if (selectedUser.trim() !== '') {
        newURL += '&userId=' + selectedUser;
    }

    window.history.replaceState(null, null, newURL);

    localStorage.setItem('selectedDevice', selectedDevice);

    clearMarkers();
    filterMap(selectedDevice);
});



        // Event handler untuk checkbox
$('#speed-checkbox, #accuracy-checkbox').change(function() {
    console.log('Checkbox changed');
    filterMap(selectedDevice);
});


        dateRangePicker.config.onChange.push(function(selectedDates, dateStr, instance) {
            filterMap(selectedDevice);
        });

       dateRangeInput.addEventListener("change", function() {
        // Perbarui peta dengan rentang tanggal yang dipilih
        filterMap();

        // Pastikan perangkat yang dipilih sebelumnya tetap dipertahankan
        var selectedDevice = $('#user_device').val();
        if (selectedDevice) {
            // Jika perangkat sudah dipilih sebelumnya, atur URL dengan menambahkan tanggal baru
            var selectedDates = this.value.split(" to ");
            var startFormatted = selectedDates[0];
            var endFormatted = selectedDates[1];
            var newURL = 'http://127.0.0.1:8000/admin/map?start=' + startFormatted + '&end=' + endFormatted + '&deviceId=' + selectedDevice + '&userId=' + selectedUser;
            window.history.replaceState(null, null, newURL);
        } else {
            // Jika tidak ada perangkat yang dipilih sebelumnya, atur URL hanya dengan tanggal baru
            var selectedDates = this.value.split(" to ");
            var startFormatted = selectedDates[0];
            var endFormatted = selectedDates[1];
            var newURL = 'http://127.0.0.1:8000/admin/map?start=' + startFormatted + '&end=' + endFormatted;
            window.history.replaceState(null, null, newURL);
        }
    });

});

    </script>

@endsection
