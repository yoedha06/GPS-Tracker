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
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
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
                                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                    </div>
                                                                                                                                                           
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Map Users</h6>
                                                    <h6 class="font-extrabold mb-0"><a href="/customer/map">Look Maps</a></h6>
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
                                                    <h6 class="text-muted font-semibold">Last Location</h6>
                                                    <h6 class="font-extrabold mb-0"><a href="/lastlocation">Look Maps</a></h6>
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
                                                    <h6 class="text-muted font-semibold">History Users</h6>
                                                    <h6 class="font-extrabold mb-0"><a href="/history/customer">History</a></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Map</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-profile-visit"></div>
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
                                                        style="width: 100%; height: auto;">
                                                @else
                                                    <img src="{{ asset('images/default.jpg') }}"
                                                        style="width: 100%; height: auto;">
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
        <script src="{{ asset('template/assets/js/bootstrap.js') }}"></script>
        <script src="{{ asset('template/assets/js/app.js') }}"></script>

        <!-- Need: Apexcharts -->
        <script src="{{ asset('template/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('template/assets/js/pages/dashboard.js') }}"></script>

    </body>
@endsection
