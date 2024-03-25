@extends('layouts.customer')


<title>GEEX - History</title>

@section('content')
<style>
    /* Aturan media queries untuk layar kecil */
    @media (max-width: 768px) {
        /* Contoh penyesuaian CSS untuk layar kecil */
        .container {
            padding-right: 15px;
            padding-left: 15px;
        }
    }
     .card {
    margin-bottom: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Tambahkan bayangan ke kartu */
    border: 1px solid #e0e0e0; /* Tambahkan batasan ke kartu */
}

.card-body {
    padding: 1rem;
}

</style>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/customer"><i
                                        class="fas fa-tachometer-alt"></i>
                                    Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-history"></i>History
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: -17px;">
        <form class="mt-2 row g-3">
            <div class="col-md-6 mb-3">
                <select id="selectDevice" class="form-select" aria-label="Select Device" style="width:100%;">
                    <option value="" disabled selected>Select Device</option>
                    @foreach ($devices as $device)
                    <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-2" style="margin-left:-10px;">
                <button id="refreshButton" class="btn btn-primary">
                    <p style="margin: -4px;"><i class="fas fa-eye"></i>&nbsp; Lihat Semua History</p>
                </button>
            </div>
        </form>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div id="validationMessage" class="alert alert-dismissible" role="alert" style="display: none;"></div>

                <h4 class="card-title">Data History</h4>
            </div>
            <div class="card-body">
                @if (count($history) > 0)
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach ($history as $h)
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
    <h5 class="card-title">{{ optional($h->device)->name }}</h5>
    <p class="card-text">
        Latitude: {{ $h->latitude }}<br>
        Longitude: {{ $h->longitude }}<br>
        Bounds: {{ $h->bounds }}<br>
        Accuracy: {{ $h->accuracy }}<br>
        Altitude: {{ $h->altitude }}<br>
        Altitude Accuracy: {{ $h->altitude_acuracy }}<br>
        Heading: {{ $h->heading }}<br>
        Speeds: {{ $h->speeds }}<br>
        Time: {{ $h->date_time }}
    </p>
</div>

                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>Data not available, sorry.</p>
                @endif
            </div>
        </div>
    </section>
    <footer>
        <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
                <p>2024 &copy; CIGS</p>
            </div>
            <div class="float-end">
            </div>
        </div>
    </footer>
</div>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
    integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    var currentPage = 1; // Halaman saat ini

    // Inisialisasi Select2
    $('#selectDevice').select2();

    // Tambahkan event listener untuk perubahan nilai pada Select2
    $('#selectDevice').on('change', function() {
        var selectedDeviceId = $(this).val();

        // Reset halaman ke 1 saat perangkat dipilih ulang
        currentPage = 1;

        // Pastikan selectedDeviceId tidak kosong atau null sebelum memanggil getDataByDevice
        if (selectedDeviceId) {
            getDataByDevice(selectedDeviceId, currentPage);
        } else {
            // Jika tidak ada perangkat yang dipilih, kosongkan konten
            $('.row-cols-1').empty();
        }
    });

    // Tambahkan event listener untuk tombol Next
    $('#nextPage').on('click', function() {
        currentPage++;
        var selectedDeviceId = $('#selectDevice').val();
        getDataByDevice(selectedDeviceId, currentPage);
    });

    // Tambahkan event listener untuk tombol Previous
    $('#prevPage').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            var selectedDeviceId = $('#selectDevice').val();
            getDataByDevice(selectedDeviceId, currentPage);
        }
    });

    // Fungsi getDataByDevice diperbarui untuk memperhitungkan halaman yang dipilih
    function getDataByDevice(deviceId, page) {
    $.ajax({
    url: '/gethistorybydevice/' + deviceId,
    method: 'GET',
    data: {
        page: page
    },
    success: function(response) {
        $('.row-cols-1').empty();

        if (response.history.length > 0) {
            $.each(response.history, function(index, history) {
                var cardHtml = `
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${response.device_name}</h5>
                                <p class="card-text">Latitude: ${history.latitude}<br>
                                Longitude: ${history.longitude}<br>
                                Bounds: ${history.bounds}<br>
                                Accuracy: ${history.accuracy}<br>
                                Altitude: ${history.altitude}<br>
                                Altitude Accuracy: ${history.altitude_accuracy}<br>
                                Heading: ${history.heading}<br>
                                Speeds: ${history.speeds}<br>
                                Time: ${history.date_time}</p>
                            </div>
                        </div>
                    </div>
                `;

                $('.row-cols-1').append(cardHtml);
            });

            showValidationMessage('Device selected successfully!');
        } else {
            showValidationMessage('No history data found for the selected device.');
        }
    },
    error: function(error) {
        console.error('Error fetching history data:', error);
        showValidationMessage('Error fetching history data. Please try again.', true);
    }
});
    }

    function showValidationMessage(message, isError = false) {
        var validationMessage = $("#validationMessage");

        if (isError) {
            validationMessage.removeClass('alert-success').addClass('alert-danger');
        } else {
            validationMessage.removeClass('alert-danger').addClass('alert-success');
        }

        validationMessage.text(message);
        validationMessage.show();

        // Sembunyikan pesan validasi setelah beberapa detik
        setTimeout(function() {
            validationMessage.hide();
        }, 1500);
    }
});

    </script>
    @extends('layouts.navbarcustomer')
@endsection
