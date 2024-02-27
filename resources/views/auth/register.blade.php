<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: whitesmoke;
        }

        .card {
            border-radius: 1rem;
        }

        .img-container {
            overflow: hidden;
            border-radius: 1rem 0 0 1rem;
            height: 100%;
            max-height: 400px;
            /* Sesuaikan tinggi maksimum sesuai kebutuhan */
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            /* Menambahkan properti object-position */
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-container i {
            color: #ff6219;
            font-size: 2rem;
            margin-right: 10px;
        }

        .logo-container span {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }

        .card-body {
            padding: 4rem 2rem;
        }

        .form-outline {
            margin-bottom: 1.5rem;
        }

        .btn-dark {
            background-color: #393f81;
            color: #fff;
        }

        .btn-dark:hover {
            background-color: #2b2f5c;
        }

        .small {
            font-size: 0.875rem;
        }

        .text-muted {
            color: #6c757d;
        }

        .mb-5 {
            margin-bottom: 3rem !important;
        }
    </style>
</head>

<body>
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="logo-container">
                                <div style="display: flex; align-items: center; margin-top: 10px;">
                                    <img src="/images/g.png" alt="GPS logo" style="max-width: 16%; height: auto; margin-right: 10px;" />
                                    <span style="font-weight: bold;">GEEX</span>
                                </div>
                            </div>
                            
                            <h5 class="fw-normal mb-3">Register for an account</h5>

                            <div class="form-outline mb-4">
                                <label for="name">Name</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-outline mb-4">
                                <label for="email">Email Address</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-outline mb-4">
                                <label for="password">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="pt-1 mb-4">
                                <button class="btn btn-dark btn-lg btn-block" type="submit">Register</button>
                            </div>

                            <p class="mb-5" style="color: #393f81;">Already have an account? <a
                                    href="{{ route('login') }}" style="color: #393f81;">Login here</a></p>
                            <a href="#!" class="small text-muted">Terms of use.</a>
                            <a href="#!" class="small text-muted">Privacy policy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
