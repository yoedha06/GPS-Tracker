<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GEEX</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/shared/iconly.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    


    <link href="/images/geex.png" rel="icon">
    <link rel="stylesheet" href="{{ asset('template/assets/css/shared/iconly.css') }}">
    <style>
        /* CSS for the splash screen */
        body {
            margin: 0;
            overflow: hidden;
        }

        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            /* Menggunakan tinggi viewport untuk menutupi seluruh tinggi layar */
            background: #ffffff;
            /* Warna latar belakang dapat disesuaikan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Menetapkan nilai z-index tinggi agar elemen muncul di atas elemen lain */
        }


        #splash-screen img {
            width: 150px;
            /* Adjust the width as needed */
            height: 70px;
            /* Adjust the height as needed */
        }

        #validationMessage {
            display: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            animation: fadeInOut 3s ease-in-out;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0;
            }

            10%,
            90% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div id="splash-screen">
        <img src="https://cdn.dribbble.com/users/1595839/screenshots/12327466/media/76bf93a21483ac790702bd19a20f0be5.gif"
            alt="Logo" style="width: 300px; height: 300px;">
    </div>
    @yield('content')
    <div id="sidebar" class="active">
        <div class="sidebar-wrapper">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="/admin">
                            <img src="/images/geex.png" alt="Logo" style="width: 145px; height: 100px;">
                        </a>

                    </div>
                    <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                            height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                            <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                    opacity=".3"></path>
                                <g transform="translate(-210 -1)">
                                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                    <circle cx="220.5" cy="11.5" r="4"></circle>
                                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                            <label class="form-check-label"></label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                            height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                            </path>
                        </svg>
                    </div>
                    <div class="sidebar-toggler  x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>
                    <hr>
                    <li class="sidebar-item {{ request()->is('admin') ? 'active' : '' }}">
                        <a href="/admin" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item  has-sub {{ request()->is('admin/user') || request()->is('admin/device') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-stack"></i>
                            <span>Users</span>
                        </a>
                        <ul class="submenu {{request()->is('admin/user') || request()->is('admin/device') ? 'active' : ''}}">
                            <li class="submenu-item {{request()->is('admin/user') ? 'active' : ''}}">
                                <a href="{{ route('admin.user') }}">
                                    <i class="fas fa-user"></i> <!-- Ikon user -->
                                    <span>Data User</span>
                                </a>
                            </li>
                            <li class="submenu-item {{request()->is('admin/device') ? 'active' : ''}}">
                                <a href="{{ route('admin.device.index') }}">
                                    <i class="fas fa-laptop-code"></i> <!-- Ikon user -->
                                    <span>Data Device</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item has-sub {{request()->is('admin/map') || request()->is('admin/lastlocation') ? 'active' : ''}}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-map-fill"></i>
                            <span>Map</span>
                        </a>
                        <ul class="submenu {{request()->is('admin/map') || request()->is('admin/lastlocation') ? 'active' : ''}}">
                            <li class="submenu-item {{request()->is('admin/map') ? 'active' : ''}}">
                                <a href="{{ route('admin.map') }}">
                                    <i class="bi bi-clock-fill"></i>
                                    <span class="ml-1">History</span>
                                </a>
                            </li>
                            <li class="submenu-item {{request()->is('admin/lastlocation') ? 'active' : ''}}">
                                <a href="{{ route('admin.lastlocation') }}">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="ml-1">Last Locations</span>
                                </a>
                            </li>
                        </ul>
                    </li>

            </div>
        </div>
    </div>
</body>
<script src="{{ asset('template/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/assets/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

    

<!-- Need: Apexcharts -->
<script src="{{ asset('template/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('template/assets/js/pages/dashboard.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove splash screen after a few seconds (e.g., 3 seconds)
        setTimeout(function() {
            document.getElementById('splash-screen').style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 1000);
    });
</script>
</body>

</html>
