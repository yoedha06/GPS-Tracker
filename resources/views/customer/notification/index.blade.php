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
                                <li class="breadcrumb-item"><a href="/customer"> <i class="fas fa-user"></i></i>
                                        Customer</a></li>
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
            <div class="row justify-content-left">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header"><b style="font-size: 1.5rem;">Send Automatic Notification</b></div>

                        <div class="card-body">

                            @if (Session::has('notif'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('notif') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('store.notiftype') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-md-right">My phone
                                        number</label>
                                    <div class="col-md-8">
                                        <input id="phone" type="text" class="form-control" name="phone"
                                            placeholder="your phone number" value="{{ auth()->user()->phone }}"
                                            style="background-color: rgb(210, 210, 218); color: rgba(0, 0, 0, 0.423);"
                                            readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="count" class="col-md-4 col-form-label text-md-right">amount of data<span
                                            style="color:red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="count" id="count"
                                            placeholder="amount of data to send" min="1" value="{{ session('count') }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="scedhule" class="col-md-4 col-form-label text-md-right">schedule<span
                                            style="color:red;">*</span></label>
                                    <div class="col-md-8">
                                        <input type="time" class="form-control" name="time_schedule" id="scedule"
                                            value="{{session('time_schedule')}}" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- send manual notification --}}
                    <div class="card">
                        <center>
                            <div class="card-header"><b style="font-size: 1.5rem;">Send Manual Notification</b></div>
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

                {{-- tabel logs history today --}}
                <div class="col-md-7" style="border-radius:10px;">
                    <div class="card">
                        <div class="card-header"><b style="font-size: 1.5rem;">Logs Today History</b></div>

                        <div class="card-body">
                            <div class="table-responsive" style="height: 300px;">
                                <table class="table table-hover mb-0" style="margin-top:10px;">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-bold-500">#</th>
                                            <th scope="col" class="text-bold-500">device</th>
                                            <th scope="col" class="text-bold-500">Coordinate</th>
                                            <th scope="col" class="text-bold-500">Speeds</th>
                                            <th scope="col" class="text-bold-500">Date Time</th>
                                            <th scope="col" class="text-bold-500">Whattsapp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $log->device_name }}</td>
                                                <td>{{ $log->latitude }}, {{ $log->longitude }}</td>
                                                <td>{{ $log->speeds }}</td>
                                                <td>{{ $log->date_time }}</td>
                                                <td>{{ $log->whatsapp_sent }}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- automatic select type --}}
{{-- <div class="form-group row">
                                    <label for="notification_type"
                                        class="col-md-4 col-form-label text-md-right">Notification Type</label>
                                    <div class="col-md-8">
                                        <select id="notification_type" class="form-select" name="notification_type" required
                                            onchange="showCustomInterval()">
                                            <option value="">Select Type</option>
                                            <option value="1"
                                                {{ session('notification_type') == 1 ? 'selected' : '' }}>Send 5 data per
                                                day</option>
                                            <option value="2"
                                                {{ session('notification_type') == 2 ? 'selected' : '' }}>Send 1 data after
                                                8 AM</option>
                                            <option value="3"
                                                {{ session('notification_type') == 3 ? 'selected' : '' }}>Custom Interval
                                                (1-5 hours)</option>
                                        </select>
                                    </div>
                                </div> --}}

{{-- <div class="form-group row" id="custom_interval" style="display: none;">
                                    <label for="notification_type" class="col-md-6 col-form-label text-md-right">Select
                                        interval</label>
                                    <div class="col-md-8">
                                        <select id="custom_interval_hours" class="form-select" name="custom_interval_hours">
                                            <option value="">Select Interval</option>
                                            <option value="1jam">1 hour</option>
                                            <option value="2jam">2 hours</option>
                                            <option value="3jam">3 hours</option>
                                            <option value="4jam">4 hours</option>
                                            <option value="5jam">5 hours</option>
                                        </select>
                                    </div>
                                </div> 

                                <script>
            function showCustomInterval() {
                var selectedValue = document.getElementById("notification_type").value;
                if (selectedValue == 3) {
                    document.getElementById("custom_interval").style.display = "block";
                } else {
                    document.getElementById("custom_interval").style.display = "none";
                }
            }
        </script>
                                
                                
                                
                                
                                
                                
                                
                                
                                --}}
