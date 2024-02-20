<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
        max-height: 400px; /* Sesuaikan tinggi maksimum sesuai kebutuhan */
        }

        .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center; /* Menambahkan properti object-position */
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
                      <i class="fas fa-cubes"></i>
                      <span class="h1">GPS</span>
                    </div>
                    <h5 class="fw-normal mb-3">Register for an account</h5>
                    <div class="form-outline mb-4">
                      <input type="text" id="name" name="name" class="form-control form-control-lg" />
                      <label class="form-label" for="name">Name</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="email" id="email" name="email" class="form-control form-control-lg" />
                      <label class="form-label" for="email">Email address</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" id="password" name="password" class="form-control form-control-lg" />
                      <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="submit">Register</button>
                    </div>
                    <p class="mb-5" style="color: #393f81;">Already have an account? <a href="{{ route("login")}}" style="color: #393f81;">Login here</a></p>
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