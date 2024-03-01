<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: whitesmoke;
        }

        .card {
            border-radius: 1rem;
            margin-top: 55px;
        }

        .card-body {
            padding: 2rem;
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: #393f81;
            border-color: #393f81;
        }

        .btn-primary:hover {
            background-color: #2b2f5c;
            border-color: #2b2f5c;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                {{-- email berhasil dikirim --}}
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                {{-- email resend berhasil dikirim --}}
                        @if (Session::has('successs'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('successs') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="logo-container text-center">
                            <img src="{{ asset('images/geex.png') }}" style="width:200px;height:160px;" alt="">
                        </div>
                        <h5 class="fw-normal mb-3 text-center">Resend Verification</h5>
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Resend Verification Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>