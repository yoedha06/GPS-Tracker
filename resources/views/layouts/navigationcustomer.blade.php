<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Navbar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
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
        top: auto; /* Mengubah posisi ke bagian bawah */
        bottom: 0;
        box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        color: #000000;
        text-decoration: none;
        font-size: 12px;
    }

    .nav-item img {
        width: 30px;
        height: 30px;
        object-fit: cover;
        margin-bottom: 5px;
    }

    .logo {
        border-radius: 50%;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

<body>
    <div class="content">
        <!-- Your page content here -->
    </div>

    <div class="navbar">
        <div class="nav-item">
            <img src="{{ asset('img/landing-page/home-1.png') }}" alt="Beranda">
            </a>
            <span>Beranda</span>
        </div>

        <div class="nav-item">
            <img src="{{ asset('img/landing-page/tickets-1.png') }}" alt="Pesanan">
            </a>
            <span>Pesanan</span>
        </div>

        <div class="nav-item logo">
            <img src="{{ asset('img/landing-page/logo1.png') }}" alt="Logo">
        </div>

        <div class="nav-item">
            <img src="{{ asset('img/landing-page/mail-1.png') }}" alt="Inbox">
            </a>
            <span>Inbox</span>
        </div>

        <div class="nav-item">
            <img src="{{ asset('img/landing-page/user-3.png') }}" alt="Akun Saya">
            </a>
            <span>Akun Saya</span>
        </div>
    </div>
</body>
</html>
