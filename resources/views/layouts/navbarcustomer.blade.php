<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl"
                                    style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%;">
                                    <!-- Gambar Profil -->
                                    @if (Auth::user()->photo)
                                        <img src="/photos/{{ Auth::user()->photo }}" style="width: 100%; height: auto;"
                                            alt="User Photo">
                                    @else
                                        <img src="{{ asset('images/default.jpg') }}" style="width: 100%; height: auto;"
                                            alt="Default User Photo">
                                    @endif
                                </div>
                                <div class="ms-2 name">
                                    <!-- Nama Pengguna -->
                                    <h6 class="font-bold text-truncate" style="max-width: 100px;">
                                        {{ Auth::user()->name }}</h6>
                                </div>
                            </div>
                        </a>
                        <!-- Dropdown Menu -->
                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                            <li>
                                <!-- Link ke Profil Pengguna -->
                                <a class="dropdown-item" href="{{ route('customer.profile') }}"><i
                                        class="fas fa-user"></i>
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
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>
