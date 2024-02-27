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

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>History</h3>
                    </div>
                </div>
            </div>
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
@endsection
