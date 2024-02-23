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
                                <li class="breadcrumb-item active" aria-current="page">Data Device</li>
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
                        <h3>Device</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Device User</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1" style="table-layout: auto">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Name</th>
                                <th>Serial Number</th>
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
                                    </td>
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
