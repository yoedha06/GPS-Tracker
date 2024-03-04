@extends('layouts.customer')

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
                                <li class="breadcrumb-item"><a href="/customer">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">History</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form class="mt-2 row g-3">
                <div class="col-md-6 mb-3">
                    <label for="selectDevice" class="form-label">Select Device</label>
                    <select id="selectDevice" class="form-select" aria-label="Select Device" style="width: 202%">
                        <option value="" selected>Select Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>        

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-6 mb-3">
                            <button id="refreshButton" class="btn btn-primary">
                                Lihat Semua History <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <h4 class="card-title">Data History</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1" style="table-layout: auto">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Device</th>
                                    <th>latlng</th>
                                    <th>bounds</th>
                                    <th>accuracy</th>
                                    <th>altitude</th>
                                    <th>altitude_acuracy</th>
                                    <th>heading</th>
                                    <th>speeds</th>
                                    <th>waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $h)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($h->device)->name }}</td>
                                        <td>{{ $h->latlng }}</td>
                                        <td>{{ $h->bounds }}</td>
                                        <td>{{ $h->accuracy }}</td>
                                        <td>{{ $h->altitude }}</td>
                                        <td>{{ $h->altitude_acuracy }}</td>
                                        <td>{{ $h->heading }}</td>
                                        <td>{{ $h->speeds }}</td>
                                        <td>{{ $h->date_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $history->links() }}
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

                // Tambahkeun event listener kanggo ngabogaan perubahan nilai dina Select2
                $('#selectDevice').on('select2:select', function(e) {
                    var selectedDeviceId = e.params.data.id;
                    console.log("contoh", selectedDeviceId);


                    // Periksa eta perangkat dipilih
                    if (selectedDeviceId) {
                        // Jalukeun AJAX request kanggo ngagaduhan data history nu cocog jeung device nu dipilih
                        $.ajax({
                            url: '/gethistorybydevice/' + selectedDeviceId,
                            method: 'GET',
                            success: function(data) {
                                // Hapus data lama tina tabel
                                $('#table1 tbody').empty();

                                // Tambahkeun data anyar kana tabel
                                $.each(data.history, function(index, history) {
                                    $('#table1 tbody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${data.device_name}</td>
                                <td>${history.latlng}</td>
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
                            },
                            error: function(error) {
                                console.error('Error fetching history data:', error);
                            }
                        });
                    } else {
                        // Mun henteu aya perangkat nu dipilih, hapus data tina tabel
                        $('#table1 tbody').empty();
                    }
                });

                // Pastikeun data history tampil nalika kaca dimuat pikeun kaliwang
                var initialDeviceId = $('#selectDevice').val();
                if (initialDeviceId) {
                    $('#selectDevice').trigger('change');
                }
                $('#refreshButton').on('click', function() {
                    // Reload the current page
                    location.reload();
                });
            });
        </script>
    @endsection
