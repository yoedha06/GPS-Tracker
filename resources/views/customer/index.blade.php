@extends('layouts.customer')
{{-- @extends('layouts.navigationcustomer') --}}

<title>Dashboard</title>

@section('content')
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Menggunakan font family Arial */
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            height: 70px;
            width: 375px;
            left: 50%;
            position: fixed;
            transform: translate(-50%);
            bottom: 0;
            box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            /* Menambahkan border radius */
            padding: 0 20px;
            /* Memberikan padding pada navbar */
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: #000000;
            text-decoration: none;
            font-size: 17px;
            font-weight: normal;
            /* Memperbesar ukuran fontsizenya */
            flex: 1;
            /* Menyesuaikan ruang setiap item */
        }

        .nav-item span {
            margin-top: 5px;
            font-weight: normal;
            /* Memberikan margin atas pada span */
        }

        .nav-item img {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            /* Menambahkan border radius */
            margin-bottom: 5px;
        }

        .logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            /* Menambahkan border radius */
        }

        .avatar img {
            width: 45px;
            /* Ubah ukuran sesuai kebutuhan */
            height: 45px;
            /* Ubah ukuran sesuai kebutuhan */
            border-radius: 50%;
            /* Agar gambar menjadi lingkaran */
        }

        .name {
            max-width: 100px;
            /* Sesuaikan dengan lebar maksimum yang Anda inginkan */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .name h6 {
            font-weight: normal;
            /* Menghilangkan efek tebal pada teks */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

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
                                    <a href="/customer/device" class="card-link">
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
                                                        <h6 class="text-muted font-semibold">
                                                            <h6 class="font-bold mb-0">
                                                                Data Device
                                                                <h6 class="font-extrabold mb-0">{{ $deviceCount }}</h6>
                                                            </h6>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <a href="/customer/map" class="card-link">
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
                                                            <h6 class="font-bold mb-0">
                                                                Maps History Users
                                                                {{-- <h6 class="font-extrabold mb-0">{{ $history }}</h6> --}}
                                                            </h6>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <a href="/customer/lastlocation" class="card-link">
                                        <div class="card">
                                            <div class="card-body px-4 py-4-5">
                                                <div class="row">
                                                    <div
                                                        class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                        <div class="stats-icon green mb-2">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                        <h6 class="text-muted font-semibold">
                                                            <h6 class="font-bold mb-0">
                                                                Last Location
                                                            </h6>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-lg-3 col-md-6">
                                    <a href="/history/customer" class="card-link">
                                        <div class="card">
                                            <div class="card-body px-4 py-4-5">
                                                <div class="row">
                                                    <div
                                                        class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                        <div class="stats-icon red mb-2">
                                                            <i class="fas fa-history"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                        <h6 class="text-muted font-semibold">
                                                            <h6 class="font-bold mb-0">
                                                                History
                                                                <h6 class="font-extrabold mb-0">{{ $totalHistoryPerDevice }}
                                                                </h6>
                                                            </h6>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="selected_date" class="form-label">Selected Date:</label>
                                                <input type="date" class="form-control" id="selected_date">
                                            </div>
                                        </div>
                                        <div id="validation-message" class="text-danger" style="display: none;"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-3" id="device_chart_select_row" style="display: none;">
                                            <div class="col-md-6">
                                                <label for="selected_device" class="form-label">Select Device:</label>
                                                <select class="form-select" id="selected_device">
                                                    <option value="" selected disabled>Select Device</option>
                                                    @foreach ($devices as $device)
                                                        <option value="{{ $device->id }}">{{ $device->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="selected_chart" class="form-label">Select Chart:</label>
                                                <select class="form-select" id="selected_chart">
                                                    <option value="" selected disabled>Select Chart</option>
                                                    <option value="latitude">Latitude</option>
                                                    <option value="longitude">Longitude</option>
                                                    <option value="speed">Speed</option>
                                                    <option value="accuracy">Accuracy</option>
                                                    <option value="heading">Heading</option>
                                                    <option value="altitude_accuracy">Altitude Accuracy</option>
                                                </select>
                                            </div>
                                        </div>
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
                        </div>
                        {{-- <div class="col-12 col-lg-3">
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
                </div> --}}

                        <footer>
                            <div class="footer clearfix mb-0 text-muted">
                                <div class="float-start">
                                    <p>2021 &copy; GEEX</p>
                                </div>
                                <div class="float-end">
                                    <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                            href="#">BARUDAK CIGS</a></p>
                                </div>
                            </div>
                        </footer>

                </div>
            </div>
            <div class="content">
                <!-- Your page content here -->
            </div>

            <div class="navbar">
                <div class="nav-item">
                    <a href="customer/lastlocation">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>LastLoc</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="customer/map">
                        <i class="bi bi-map-fill"></i>
                        <br>
                        <span>Maps</span>
                    </a>
                </div>

                <div class="nav-item logo">
                    <a href="#">
                        <img src="/images/g.png" alt="Logo">
                    </a>
                </div>

                <div class="nav-item">
                    <a href="customer/device">
                        <i class="bi bi-ev-front-fill"></i>
                        <span>Device</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="/customer/profile">
                        <div class="avatar">
                            <!-- Gambar Profil -->
                            @if (Auth::user()->photo)
                                <img src="/photos/{{ Auth::user()->photo }}" alt="User Photo">
                            @else
                                <img src="{{ asset('images/default.jpg') }}" alt="Default User Photo">
                            @endif
                        </div>
                    </a>
                    {{-- <!-- Dropdown Menu -->
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                    <li>
                        <!-- Link ke Profil Pengguna -->
                        <a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="fas fa-user"></i>
                            Profile</a>
                    </li>
                    <li>
                        <!-- Form untuk Logout -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <!-- Link Logout -->
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul> --}}
                </div>
            </div>

            <!-- Pastikan untuk memuat jQuery sebelum memuat skrip lain yang menggunakan jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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
                            type: 'line'
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
                                borderRadius: 10,
                                dataLabels: {
                                    position: 'top', // Menempatkan label di atas bar
                                    offsetY: -20, // Mengatur offset vertical label
                                    formatter: function(val) {
                                        return val; // Menampilkan nilai di atas bar
                                    }
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            y: {
                                formatter: function(value) {
                                    return 'Jumlah History: ' +
                                        value; // Menampilkan jumlah history saat mouse di atas bar
                                }
                            }
                        },
                        colors: ['#1f77b4'] // Ubah atau hapus opsi warna untuk mengembalikan ke warna default
                    };

                    // Initialize chart
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                    // Function to update chart data
                    function updateChart(selectedDevice, selectedChart, selectedDate) {
                        var selectedDate = $('#selected_date').val();

                        console.log("Selected Date:", selectedDate);
                        console.log("Selected Device:", selectedDevice);
                        console.log("Selected Chart:", selectedChart);

                        if (!selectedDate) {
                            alert('Silahkan pilih tanggal terlebih dahulu.');
                            return; // Stop further execution if date is not selected
                        }

                        // Show device select row and chart select row
                        $('#device_select_row').show();
                        $('#chart_select_row').show();

                        $.ajax({
                            method: 'GET',
                            url: '/customer-chart',
                            data: {
                                selected_date: selectedDate,
                                selected_device: selectedDevice,
                                selected_chart: selectedChart // 
                            },
                            success: function(response) {
                                console.log("Response Data:", response);
                                console.log("Chart Data:", chartData);

                                var chartData = response.data || [];

                                // Prepare series data for selected device
                                var seriesData = [];
                                var categories = [];

                                // Iterate through each data point
                                chartData.forEach(function(item) {
                                    // Add data to series and categories arrays
                                    seriesData.push(item.count);
                                    categories.push(item.date_time);
                                });

                                // Update chart with new data
                                chart.updateOptions({
                                    xaxis: {
                                        categories: categories // Use date_time as categories
                                    }
                                });
                                chart.updateSeries([{
                                    data: seriesData
                                }]);

                                // Update plot options based on the number of data points
                                if (chartData.length === 1) {
                                    // If there is only one data point, reduce the bar width
                                    chart.updateOptions({
                                        plotOptions: {
                                            bar: {
                                                columnWidth: '30%' // Adjust columnWidth as needed
                                            }
                                        }
                                    });
                                } else {
                                    // If there are multiple data points, reset the plot options to default
                                    chart.updateOptions({
                                        plotOptions: {
                                            bar: {
                                                columnWidth: '80%' // Set the default columnWidth
                                            }
                                        }
                                    });
                                }

                                // Update device selection dropdown
                                var deviceDropdown = $('#selected_device');
                                deviceDropdown.empty(); // Clear previous options

                                if (response.deviceOptions.length > 0) {
                                    deviceDropdown.append($('<option>', {
                                        value: '', // Empty value
                                        text: 'All History Device'
                                    }));

                                    // Add device options received from the server response
                                    response.deviceOptions.forEach(function(device) {
                                        deviceDropdown.append($('<option>', {
                                            value: device,
                                            text: device // Use the device name directly as the option text
                                        }));
                                    });
                                } else {
                                    // If no device options available, show default option
                                    deviceDropdown.append($('<option>', {
                                        value: '', // Empty value
                                        text: 'Tidak Ada Perangkat Tersedia'
                                    }));
                                }

                                // Set selected device option
                                if (selectedDevice) {
                                    deviceDropdown.val(
                                        selectedDevice); // Set the selected device as the selected option
                                }

                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    }

                    // Add event listener for date input change
                    $('#selected_date').change(function() {
                        var selectedDate = $(this).val(); // Get the selected date

                        // Check if the selected date is not empty
                        if (selectedDate) {
                            // Show the device and chart selection row
                            $('#device_chart_select_row').show();
                        } else {
                            // Hide the device and chart selection row if the date is empty
                            $('#device_chart_select_row').hide();
                        }
                        updateChart();
                    });

                    // Add event listener for device select change
                    $('#selected_device').change(function() {
                        var selectedDevice = $(this).val();
                        var selectedChart = $('#selected_chart').val(); // Get the selected chart
                        console.log("Selected Device:", selectedDevice);
                        console.log("Selected Chart:", selectedChart);
                        updateChart(selectedDevice, selectedChart);
                    });

                    $(document).on('change', '#selected_chart', function() {
                        var selectedChart = $(this).val();
                        var selectedDevice = $('#selected_device').val();
                        var selectedDate = $('#selected_date').val();

                        if (!selectedDate) {
                            alert('Silahkan pilih tanggal terlebih dahulu.');
                            return;
                        }

                        if (!selectedDevice) {
                            alert('Silahkan pilih perangkat terlebih dahulu.');
                            return;
                        }

                        updateChart(selectedDevice, selectedChart, selectedDate);
                    });
                });
            </script>
        @endsection
