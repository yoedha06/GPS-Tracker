<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl"
                                    style="width: 40px; height: 40px; overflow: hidden; border-radius: 50%;">
                                    @if (Auth::user()->photo)
                                        <img src="/photos/{{ Auth::user()->photo }}" style="width: 100%; height: auto;"
                                            alt="User Photo">
                                    @else
                                        <img src="{{ asset('images/default.jpg') }}" style="width: 100%; height: auto;"
                                            alt="Default User Photo">
                                    @endif
                                </div>
                                <div class="ms-2 name">
                                    <h6 class="font-bold text-truncate" style="max-width: 100px;">
                                        {{ Auth::user()->name }}</h6>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user"></i>
                                    Profile</a>
                            </li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
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
