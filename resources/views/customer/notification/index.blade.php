@extends('layouts.customer')

<title>GEEX - Notification</title>

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
                                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-bell"></i>
                                    </i>
                                    Notification</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <center>
                            <div class="card-header"><b style="font-size: 1.5rem;">Send Data History</b></div>
                        </center>

                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (Session::has('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ Session::get('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.notification.store') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-md-right">My phone
                                        number</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            placeholder="your phone number" value="{{ auth()->user()->phone }}" 
                                            style="background-color: rgb(210, 210, 218); color: rgba(0, 0, 0, 0.423);"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="device" class="col-md-4 col-form-label text-md-right">Select
                                        Device</label>
                                    <div class="col-md-6">
                                        <select id="device" class="form-select" name="device" required>
                                            <option value="">Select Device</option>
                                            @foreach ($devices as $device)
                                                <option value="{{ $device->id_device }}">{{ $device->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="scheduled_time" class="col-md-4 col-form-label text-md-right">Start Date
                                    </label>
                                    <div class="col-md-6">
                                        <input id="scheduled_time" type="datetime-local" class="form-control"
                                            name="scheduled_time" required step="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="scheduled_end_time" class="col-md-4 col-form-label text-md-right">End
                                        Date</label>
                                    <div class="col-md-6">
                                        <input id="scheduled_end_time" type="datetime-local" class="form-control"
                                            name="scheduled_end_time" required step="1">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
