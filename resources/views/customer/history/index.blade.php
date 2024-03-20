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
                                <p class="card-text">Latitude: {{ $h->latitude }}</p>
                                <p class="card-text">Longitude: {{ $h->longitude }}</p>
                                <p class="card-text">Bounds: {{ $h->bounds }}</p>
                                <p class="card-text">Accuracy: {{ $h->accuracy }}</p>
                                <p class="card-text">Altitude: {{ $h->altitude }}</p>
                                <p class="card-text">Altitude Accuracy: {{ $h->altitude_acuracy }}</p>
                                <p class="card-text">Heading: {{ $h->heading }}</p>
                                <p class="card-text">Speeds: {{ $h->speeds }}</p>
                                <p class="card-text">Time: {{ $h->date_time }}</p>
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
    // Inisialisasi Select2
    $('#selectDevice').select2();

    // Tambahkan event listener untuk perubahan nilai pada Select2
    $('#selectDevice').on('select2:select', function(e) {
        var selectedDeviceId = e.params.data.id;

        // Periksa apakah perangkat telah dipilih
        if (selectedDeviceId) {
            getDataByDevice(selectedDeviceId, 1); // Mulai dari halaman pertama saat perangkat dipilih
        } else {
            // Jika tidak ada perangkat yang dipilih, kosongkan konten
            $('.row-cols-1').empty();
        }
    });

    // Event listener untuk tombol refresh
    $('#refreshButton').on('click', function() {
        location.reload(); // Muat ulang halaman saat tombol ditekan
    });

    // Event listener untuk tautan pagination
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        var selectedDeviceId = $('#selectDevice').val();
        getDataByDevice(selectedDeviceId, page); // Ambil data berdasarkan perangkat dan nomor halaman yang dipilih
    });

    function getDataByDevice(deviceId, page) {
        // Jalankan AJAX request untuk mendapatkan data histori yang sesuai dengan perangkat yang dipilih
        $.ajax({
            url: '/gethistorybydevice/' + deviceId,
            method: 'GET',
            data: {
                page: page
            },
            success: function(data) {
                $('.row-cols-1').empty();

                if (data.history.length > 0) {
                    $.each(data.history, function(index, history) {
                        var cardHtml = `
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${optional(history.device).name}</h5>
                                        <p class="card-text">Latitude: ${history.latitude}</p>
                                        <p class="card-text">Longitude: ${history.longitude}</p>
                                        <p class="card-text">Bounds: ${history.bounds}</p>
                                        <p class="card-text">Accuracy: ${history.accuracy}</p>
                                        <p class="card-text">Altitude: ${history.altitude}</p>
                                        <p class="card-text">Altitude Accuracy: ${history.altitude_acuracy}</p>
                                        <p class="card-text">Heading: ${history.heading}</p>
                                        <p class="card-text">Speeds: ${history.speeds}</p>
                                        <p class="card-text">Time: ${history.date_time}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('.row-cols-1').append(cardHtml);
                    });

                    // Tampilkan pagination
                    var paginationHtml = '';
                    paginationHtml += '<nav aria-label="Page navigation example">';
                    paginationHtml += '<ul class="pagination justify-content-center">';
                    paginationHtml += '<li class="page-item ' + (data.pagination.current_page == 1 ? 'disabled' : '') + '">';
                    paginationHtml += '<a class="page-link pagination-link" href="#" data-page="' + (data.pagination.current_page - 1) + '">Previous</a>';
                    paginationHtml += '</li>';
                    for (var i = 1; i <= data.pagination.last_page; i++) {
                        paginationHtml += '<li class="page-item ' + (data.pagination.current_page == i ? 'active' : '') + '">';
                        paginationHtml += '<a class="page-link pagination-link" href="#" data-page="' + i + '">' + i + '</a>';
                        paginationHtml += '</li>';
                    }
                    paginationHtml += '<li class="page-item ' + (data.pagination.current_page == data.pagination.last_page ? 'disabled' : '') + '">';
                    paginationHtml += '<a class="page-link pagination-link" href="#" data-page="' + (data.pagination.current_page + 1) + '">Next</a>';
                    paginationHtml += '</li>';
                    paginationHtml += '</ul>';
                    paginationHtml += '</nav>';
                    $('.pagination-container').html(paginationHtml);

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
