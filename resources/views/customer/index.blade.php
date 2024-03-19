@extends('layouts.customer')

@section('content')

    <body>
        <div id="app">
            <div id="main">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>

                <div class="page-heading">
                    <h3>Profile Statistics</h3>
                </div>
                <div class="page-content">
                    <section class="row">
                        <div class="col-12 col-lg-9">
                            <div class="row">
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon purple mb-2">
                                                        <i class="fas fa-car"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Device Users</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $deviceCount }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                    </div>

                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">
                                                        <h6 class="font-extrabold mb-0">
                                                            <a href="/customer/map">Maps Users</a>
                                                            <h6 class="font-extrabold mb-0">{{ $history }}</h6>
                                                        </h6>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon green mb-2">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">
                                                        <h6 class="font-extrabold mb-0">
                                                            <a href="/customer/lastlocation">Last Location</a>
                                                        </h6>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                                    <div class="stats-icon red mb-2">
                                                        <i class="fas fa-history"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">
                                                        <h6 class="font-extrabold mb-0">
                                                            <a href="/history/customer">History</a>
                                                            <h6 class="font-extrabold mb-0">{{ $history }}</h6>
                                                        </h6>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="selected_date" class="form-label">Selected Date:</label>
                                        <input type="date" class="form-control" id="selected_date">
                                    </div>
                                </div>
                                <div id="validation-message" class="text-danger" style="display: none;"></div>

                                <div class="row mb-3" id="device_select_row" style="display: none;">
                                    <div class="col-md-6">
                                        <label for="selected_device" class="form-label">Select Device:</label>
                                        <select class="form-select" id="selected_device">
                                            <option value="" selected disabled>Select Device</option>
                                            @foreach ($devices as $device)
                                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>History Grafik</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-xl-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Profile Visit</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <svg class="bi text-primary" width="32" height="32"
                                                            fill="blue" style="width:10px">
                                                            <use
                                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                        </svg>
                                                        <h5 class="mb-0 ms-3">Europe</h5>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="mb-0">862</h5>
                                                </div>
                                                <div class="col-12">
                                                    <div id="chart-europe"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <svg class="bi text-success" width="32" height="32"
                                                            fill="blue" style="width:10px">
                                                            <use
                                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                        </svg>
                                                        <h5 class="mb-0 ms-3">America</h5>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="mb-0">375</h5>
                                                </div>
                                                <div class="col-12">
                                                    <div id="chart-america"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <svg class="bi text-danger" width="32" height="32"
                                                            fill="blue" style="width:10px">
                                                            <use
                                                                xlink:href="assets/images/bootstrap-icons.svg#circle-fill" />
                                                        </svg>
                                                        <h5 class="mb-0 ms-3">Indonesia</h5>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="mb-0">1025</h5>
                                                </div>
                                                <div class="col-12">
                                                    <div id="chart-indonesia"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Latest Comments</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-lg">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Comment</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md">
                                                                    </div>
                                                                    <p class="font-bold ms-3 mb-0">Si Cantik</p>
                                                                </div>
                                                            </td>
                                                            <td class="col-auto">
                                                                <p class=" mb-0">Congratulations on your graduation!</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md">
                                                                    </div>
                                                                    <p class="font-bold ms-3 mb-0">Si Ganteng</p>
                                                                </div>
                                                            </td>
                                                            <td class="col-auto">
                                                                <p class=" mb-0">Wow amazing design! Can you make another
                                                                    tutorial for
                                                                    this design?</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="card">
                                <div class="card-body py-4 px-4">
                                    <!-- Wrap the entire column content in an anchor tag -->
                                    <a href="#" class="dropdown-toggle" id="profileDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl"
                                                style="width: 80px; height: 80px; overflow: hidden; border-radius: 50%;">
                                                @if (Auth::user()->photo)
                                                    <img src="/photos/{{ Auth::user()->photo }}"
                                                        style="width: 100%; height: auto; object-fit: cover; user-drag: none; -webkit-user-drag: none;">
                                                @else
                                                    <img src="{{ asset('images/default.jpg') }}"
                                                        style="width: 100%; height: auto; user-drag: none; -webkit-user-drag: none;">
                                                @endif
                                            </div>
                                            @if ($user)
                                                <div class="ms-3 name">
                                                    <h5 class="font-bold text-truncate" style="max-width: 150px;">
                                                        {{ $user->name }}</h5>
                                                    <h6 class="text-muted mb-0">{{ $user->role }}</h6>
                                                </div>
                                            @else
                                                <div class="ms-3 name">
                                                    <h5 class="font-bold">User not found</h5>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <!-- Dropdown menu for profile options -->
                                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                        <li><a class="dropdown-item" href="customer/profile"><i class="fas fa-user"></i>
                                                Profile</a></li>
                                        <li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="{{ route('logout') }}" class='sidebar-link'
                                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                <i class="bi bi-box-arrow-left"></i>
                                                <span>Logout</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <footer>
                    <div class="footer clearfix mb-0 text-muted">
                        <div class="float-start">
                            <p>2021 &copy; GEEX</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                    href="https://saugi.me">BARUDAK CIGS</a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!-- Pastikan untuk memuat jQuery sebelum memuat skrip lain yang menggunakan jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Memuat skrip Bootstrap DatePicker dan stylesheet -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <!-- Memuat skrip Bootstrap -->
        <script src="{{ asset('template/assets/js/bootstrap.js') }}"></script>

        <!-- Memuat skrip ApexCharts -->
        <script src="{{ asset('template/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>

        <!-- Memuat skrip aplikasi Anda -->
        <script src="{{ asset('template/assets/js/app.js') }}"></script>
        <script src="{{ asset('template/assets/js/pages/dashboard.js') }}"></script>

        <!-- Memuat ApexCharts dari CDN -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            $(document).ready(function() {
                // Initial chart data from blade template
                var historyData = {!! json_encode($historyData) !!};
                var categories = historyData.map(function(item) {
                    return item.name;
                });
                var data = historyData.map(function(item) {
                    return item.count;
                });

                // Chart configuration
                var options = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        name: 'Jumlah History',
                        data: data
                    }],
                    xaxis: {
                        categories: categories
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10
                        }
                    }
                };

                // Initialize chart
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();

                // Function to update chart data
                function updateChart(selectedDevice) {
                    var selectedDate = $('#selected_date').val();

                    console.log("Selected Date:", selectedDate);
                    console.log("Selected Device:", selectedDevice);

                    if (!selectedDate) {
                        alert('Silahkan pilih tanggal terlebih dahulu.');
                        return; // Stop further execution if date is not selected
                    }

                    // Check if the device value is null, undefined, or an empty string
                    if (!selectedDevice && selectedDevice !== null && selectedDevice !== undefined && selectedDevice !==
                        '') {
                        alert('Silahkan pilih perangkat terlebih dahulu.');
                        return;
                        selectedDevice = null; // Set device value to null if none selected
                    }

                    $.ajax({
                        method: 'GET',
                        url: '/customer-chart',
                        data: {
                            selected_date: selectedDate,
                            selected_device: selectedDevice
                        },
                        success: function(response) {
                            console.log("Response Data:", response);

                            var dateTimes = response.date_time || [];
                            var deviceOptions = response.deviceOptions || [];
                            var chartData = response.chartData ||
                        []; // Data yang akan digunakan untuk grafik

                            // Update chart with new data
                            chart.updateSeries([{
                                data: chartData
                            }]);


                            if (selectedDate || selectedDevice) {
                                $('#device_select_row').show();
                            } else {
                                $('#device_select_row').hide();
                            }

                            // Update device selection dropdown
                            var deviceDropdown = $('#selected_device');
                            deviceDropdown.empty(); // Clear previous options

                            if (deviceOptions.length > 0) {
                                deviceDropdown.append($('<option>', {
                                    value: '', // Empty value
                                    text: 'Pilih Perangkat'
                                }));

                                deviceOptions.forEach(function(device) {
                                    deviceDropdown.append($('<option>', {
                                        value: device,
                                        text: device
                                    }));
                                });
                            } else {
                                // If no device options available, show default option
                                deviceDropdown.append($('<option>', {
                                    value: '', // Empty value
                                    text: 'Tidak Ada Perangkat Tersedia'
                                }));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }

                // Add event listener for date input change
                $('#selected_date').change(function() {
                    updateChart();
                });

                // Add event listener for device select change
                $('#selected_device').change(function() {
                    var selectedDevice = $(this).val();
                    console.log("Selected Device:", selectedDevice);
                    updateChart(selectedDevice);

                });

            });
        </script>
    @endsection
