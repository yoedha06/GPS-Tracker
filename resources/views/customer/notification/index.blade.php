@extends('layouts.customer')

@section('title', 'GEEX - Notification')

@section('content')
    <div id="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><b>Send Data History</b></div>

                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('success') }}
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
                                    <label for="phone" class="col-md-4 col-form-label text-md-right">No handphone</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            value="{{ auth()->user()->phone }}" style="background-color: rgb(210, 210, 218); color: rgba(0, 0, 0, 0.423);" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="scheduled_time" class="col-md-4 col-form-label text-md-right">Start Date
                                        </label>
                                    <div class="col-md-6">
                                        <input id="scheduled_time" type="datetime-local" class="form-control"
                                            name="scheduled_time" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="scheduled_end_time" class="col-md-4 col-form-label text-md-right">End
                                        Date</label>
                                    <div class="col-md-6">
                                        <input id="scheduled_end_time" type="datetime-local" class="form-control"
                                            name="scheduled_end_time" required>
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
