<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-8a4bI/7Nb+C8Fm55o/G3GLJUswGch5o7kP9iHgxy2CjsMP9SDf9u+LydFziF4+irf7kNUewa2oa0bOXkJQHjw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="/images/geex.png" rel="icon">

    <link rel="stylesheet" href="{{ asset('template/assets/css/shared/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/splashscreen.css') }}">
</head>
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
    <div id="splash-screen" style="height: 100%">
        <img src="https://cdn.dribbble.com/users/1595839/screenshots/12327466/media/76bf93a21483ac790702bd19a20f0be5.gif"
            alt="Logo" style="width: 350px; height: 310px;">
    </div>
    @yield('content')
    <div id="sidebar" class="active">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="logo">
                        <a href="/customer">
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
                            aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                            </path>
                        </svg>
                    </div>
                    <div class="sidebar-toggler  x">
                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>
                    <hr>
                    <li class="sidebar-item active ">
                        <a href="/customer" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <br>
                    <a href="{{ route('lastlocation') }}" class="sidebar-link">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Last Location</span>
                    </a>

                    <a href="/customer/map" class="sidebar-link">
                        <i class="bi bi-map-fill"></i>
                        <span>Maps</span>
                    </a>

                    <a href="/history/customer" class="sidebar-link">
                        <i class="bi bi-clock-fill"></i>
                        <span>History</span>
                    </a>

                    <a href="/customer/device" class="sidebar-link">
                        <i class="fas fa-tablet"></i>
                        <span>Device</span>
                    </a>

                    {{-- <li class="sidebar-item  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-bar-chart-fill"></i>
                            <span>Users</span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <a href="/history/customer"><i class="bi bi-clock-fill"></i> History</a>
                            </li>
                            <li class="submenu-item ">
                                <a href="/customer/device"><i class="fas fa-tablet"></i> Device</a>
                            </li>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
    <footer>
        <div class="navbar">
            <div class="nav-item">
                <a href="/customer/lastlocation">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>LastLoc</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/customer/map">
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
                <a href="/customer/device">
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

            </div>
        </div>
    </footer>
</body>
<script src="{{ asset('template/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('template/assets/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    $(document).ready(function() {
        $('#selectUser').change(function() {
            var userId = $(this).val();

            // Use Ajax to update the table based on the selected user
            $.ajax({
                url: '/admin/device/' + userId, // Update the URL based on your Laravel routes
                type: 'GET',
                success: function(data) {
                    $('#table1 tbody').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

    });
</script>

</html>
