@extends('layouts.customer')
@extends('layouts.navbarcustomer')

@section('content')
    <div id="main">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/customer"><i class="fas fa-tachometer-alt"></i>
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
                    <table class="table table-striped" id="table1" style="table-layout: auto">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Device</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Bounds</th>
                                <th>Accuracy</th>
                                <th>Altitude</th>
                                <th>Altitude Accuracy</th>
                                <th>Heading</th>
                                <th>Speeds</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($history) > 0)
                                @php $iteration = 1 @endphp
                                @foreach ($history as $h)
                                    <tr>
                                        <td>{{ $iteration++ }}</td>
                                        <td>{{ optional($h->device)->name }}</td>
                                        <td>{{ $h->latitude }}</td>
                                        <td>{{ $h->longitude }}</td>
                                        <td>{{ $h->bounds }}</td>
                                        <td>{{ $h->accuracy }}</td>
                                        <td>{{ $h->altitude }}</td>
                                        <td>{{ $h->altitude_acuracy }}</td>
                                        <td>{{ $h->heading }}</td>
                                        <td>{{ $h->speeds }}</td>
                                        <td>{{ $h->date_time }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <span style="font-size: 3rem;">&#x1F5FF;</span>
                                        <p class="mt-2">Data not available, sorry.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div id="paginationContainer" class="text-center">
                        <ul class="pagination"></ul>
                    </div>
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

    <!-- Your custom script -->
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#selectDevice').select2();

            // Tambahkan event listener untuk perubahan nilai pada Select2
            $('#selectDevice').on('select2:select', function(e) {
                var selectedDeviceId = e.params.data.id;

                // Periksa apakah perangkat telah dipilih
                if (selectedDeviceId) {
                    getDataByDevice(selectedDeviceId,
                        1); // Mulai dari halaman pertama saat perangkat dipilih
                } else {
                    // Jika tidak ada perangkat yang dipilih, hapus data dari tabel
                    $('#table1 tbody').empty();
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
                getDataByDevice(selectedDeviceId,
                    page); // Ambil data berdasarkan perangkat dan nomor halaman yang dipilih
            });

            function getDataByDevice(deviceId, page) {
                // Jalankan AJAX request untuk mendapatkan data histori yang sesuai dengan perangkat yang dipilih
                $.ajax({
                    url: '/gethistorybydevice/' + deviceId,
                    method: 'GET',
                    data: {
                        page: page
                    }, // Menggunakan nomor halaman yang dipilih
                    success: function(data) {
                        $('#table1 tbody').empty();

                        if (data.history.length > 0) {
                            // Tambahkan data baru ke tabel
                            $.each(data.history, function(index, history) {
                                $('#table1 tbody').append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${data.device_name}</td>
                                        <td>${history.latitude}</td>
                                        <td>${history.longitude}</td>
                                        <td>${history.bounds}</td>
                                        <td>${history.accuracy}</td>
                                        <td>${history.altitude}</td>
                                        <td>${history.altitude_acuracy}</td>
                                        <td>${history.heading}</td>
                                        <td>${history.speeds}</td>
                                        <td>${history.date_time}</td>
                                    </tr>
                                `);
                            });

                            // Tambahkan tautan pagination
                            // Tambahkan tautan pagination
                            var paginationHtml = '';
                            paginationHtml += '<li class="page-item ' + (data.pagination.current_page ==
                                1 ? 'disabled' : '') + '">';
                            paginationHtml +=
                                '<a class="page-link pagination-link" href="#" data-page="' + (data
                                    .pagination.current_page - 1) + '">Previous</a>';
                            paginationHtml += '</li>';
                            for (var i = 1; i <= data.pagination.last_page; i++) {
                                paginationHtml += '<li class="page-item ' + (data.pagination
                                    .current_page == i ? 'active' : '') + '">';
                                paginationHtml +=
                                    '<a class="page-link pagination-link" href="#" data-page="' + i +
                                    '">' + i + '</a>';
                                paginationHtml += '</li>';
                            }
                            paginationHtml += '<li class="page-item ' + (data.pagination.current_page ==
                                data.pagination.last_page ? 'disabled' : '') + '">';
                            paginationHtml +=
                                '<a class="page-link pagination-link" href="#" data-page="' + (data
                                    .pagination.current_page + 1) + '">Next</a>';
                            paginationHtml += '</li>';
                            $('#paginationContainer .pagination').html(paginationHtml);


                            showValidationMessage('Device selected successfully!');
                        } else {
                            // Tampilkan pesan jika tidak ada data yang tersedia
                            $('#table1 tbody').append(`
                    <tr>
                        <td colspan="11" class="text-center">
                            <span style="font-size: 3rem;">&#x1F5FF;</span>
                            <p class="mt-2">Data not available, sorry.</p>
                        </td>
                    </tr>
                `);
                            showValidationMessage('No history data found for the selected device.');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching history data:', error);
                        showValidationMessage('Error fetching history data. Please try again.');
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
@endsection
