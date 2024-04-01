<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap JS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<style>
    /* CSS untuk konten utama */

    .navbar {
        display: flex;
        justify-content: center;
        /* Atau gunakan nilai lain sesuai kebutuhan */
        align-items: center;
        /* Atau gunakan nilai lain sesuai kebutuhan */
        background - color: #ffffff;
        height: 70 px;
        width: 375 px;
        left: 50 %;
        position: fixed;
        transform: translate(-50 %);
        bottom: 0;
        box - shadow: 0 px - 2 px 5 px rgba(0, 0, 0, 0.2);
        border - radius: 15 px;
        /* Menambahkan border radius */
        padding: 0 20 px;
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
        margin-right: 10px;
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

    footer {
        padding: 10px 20px;
        position: absolute;
        /* Mengubah posisi menjadi absolute */
        bottom: 0;
        width: 100%;
    }
</style>

<body>
    @yield('navcus')
    <footer>

        <!-- Navbar -->
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
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>




</html>
