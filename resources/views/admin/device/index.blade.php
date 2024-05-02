@extends('layouts.admin')

<title>GEEX - Data Device</title>

@extends('layouts.navbaradmin')

<style>
    .loader {
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
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
                                <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-hdd-stack-fill"></i>
                                    Data
                                    Device</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <select id="selectDevice" class="form-select" aria-label="Default select example">
                <option value="">Select Users</option>
                @foreach ($users as $user)
                    @if ($user->role === 'customer')
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <button class="btn btn-primary" id="showAllDataBtn">
                <i class="fas fa-eye"></i> See All Device
            </button>
        </div>

        <div class="card">
            <div id="validationMessage">
                Successfully selected data!
            </div>
            <section class="section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Device User</h4>
                </div>
                <div class="card-body" style="position: relative;">
                    <div id="overlay"
                        style="display:none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.7);">
                        <div class="overlay-content d-flex justify-content-center align-items-center">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <table class="table table-striped" id="table1" style="table-layout: auto">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Device</th>
                                <th>Serial Number</th>
                                <th>Nopol</th>
                                <th>Photo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($device as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->user)->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>
                                        @if ($item->plat_nomor)
                                            {{ $item->plat_nomor }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->photo)
                                            <img src="{{ asset('storage/' . $item->photo) }}" alt="Device Photo"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewPhotoModal{{ $item->id_device }}"
                                                style="max-width: 100px; max-height: 100px;">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-body">
                        {{ $device->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
        </div>
        </section>

        <!-- Modals Photo device -->
        @foreach ($device as $item)
            <div class="modal fade" id="viewPhotoModal{{ $item->id_device }}" tabindex="-1"
                aria-labelledby="viewPhotoModalLabel{{ $item->id_device }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewPhotoModalLabel{{ $item->id_device }}">Picture -
                                {{ $item->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="Device Photo"
                                    style="max-width: 100%; max-height: 100vh; border-radius: 10px">
                            @else
                                No Image
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $("#selectDevice").select2({
                placeholder: "Select Users",
                allowClear: true,
                data: {!! $users->pluck('name', 'id')->toJson() !!}, // Populate initial data
            });

            // Tambahkan event listener untuk perubahan nilai atau pencarian
            $("#selectDevice").on('change', function() {
                var userId = $(this).val();
                fetchData(userId);
                // Show validation message when a user is selected
                showValidationMessage(userId ? 'Successfully selected user: ' + $(
                    "#selectDevice option:selected").text() : '');
            });

            // Tambahkan event listener untuk tombol "Lihat Semua Data"
            $("#showAllDataBtn").on('click', function() {
                // Call the fetchData function with an empty user ID to get all data
                fetchData('');
                // Show validation message when showing all data
                showValidationMessage('Successfully showing all data!');
            });

            // Fungsi untuk memperbarui tabel dengan data yang diterima
            function updateDeviceTable(data) {
                var tableBody = $("#table1 tbody");
                tableBody.empty();

                // Iterasi melalui data dan tambahkan baris ke tabel
                $.each(data, function(index, item) {
                    console.log('Processing item:', item);
                    var platNomor = item.plat_nomor ? item.plat_nomor : '-';
                    var row = `<tr>
                    <td>${index + 1}</td>
                    <td>${item.user ? item.user.name : ''}</td>
                    <td>${item.name}</td>
                    <td>${item.serial_number}</td>
                    <td>${platNomor}</td>
                    <td>
                        ${item.photo ? `<img src="/storage/${item.photo}" alt="Device Photo" data-bs-toggle="modal"
                                                                            data-bs-target="#viewPhotoModal${item.id_device}" style="max-width: 100px; max-height: 100px;">` : 'No Image'}
                    </td>
                    </tr>`;
                    tableBody.append(row);
                });
            }

            // Fungsi untuk menampilkan pesan validasi
            function showValidationMessage(message) {
                var validationMessage = $("#validationMessage");
                validationMessage.text(message);
                validationMessage.show();

                // Hide the validation message after 3 seconds
                setTimeout(function() {
                    validationMessage.hide();
                }, 1500);
            }


            // Fungsi untuk mengambil data dari server berdasarkan user ID
            function fetchData(userId) {
                // Kirim permintaan Ajax untuk mendapatkan data berdasarkan nilai yang dipilih
                $.ajax({
                    url: '/getDevicesByUser',
                    type: 'GET',
                    data: {
                        userId: userId
                    },
                    beforeSend: function() {
                        // Tampilkan overlay sebelum mengirim permintaan
                        $('#overlay').show();
                    },
                    success: function(response) {
                        console.log('Received response:', response);
                        // Sembunyikan overlay setelah menerima respons yang berhasil
                        $('#overlay').hide();

                        // Periksa jika respons adalah array dan tidak kosong
                        if (Array.isArray(response) && response.length > 0) {
                            // Perbarui tabel atau bagian tampilan dengan data yang diterima
                            updateDeviceTable(response);
                        } else {
                            console.error('Invalid or empty response:', response);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching devices:', error);
                        // Sembunyikan overlay jika terjadi kesalahan
                        $('#overlay').hide();
                    }
                });
            }

            // Tambahkan event listener untuk tombol "Lihat Semua Data"
            $("#showAllDataBtn").on('click', function() {
                // Reload the current page
                location.reload();
            });
        });
    </script>
@endsection
