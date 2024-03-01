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
            <form class="mt-2">
                <div class="mb-2">
                    <label>Select Device</label>
                    <select id="selectDevice" class="form-select" aria-label="Default select example">
                        <option value="" selected>Select Device</option>
                        @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach
                    </select>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
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

        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                $('#selectDevice').select2();

                // Tambahkeun event listener kanggo ngabogaan perubahan nilai dina Select2
                $('#selectDevice').on('change', function() {
                    var selectedDeviceId = $(this).val();

                    // Periksa eta perangkat dipilih
                    if (selectedDeviceId) {
                        // Jalukeun AJAX request kanggo ngagaduhan data history nu cocog jeung device nu dipilih
                        $.ajax({
                            url: '/getHistoryByDevice/' + selectedDeviceId,
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
            });
        </script>
    @endsection
