<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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
  <section class="vh-100" id="login-page">
    <div class="container py-5 h-100">
      @if ($message = session('success'))
        <div class="alert alert-primary d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
          <div>
            {{ $message }}
          </div>
        </div>
      @endif
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <div class="img-container" style="margin-top: 100px; border-radius:50%">
                  <img src="/images/gps.png"
                    alt="login form" class="img-fluid" />
                </div>
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body">
                    <div class="logo-container">
                      <i class="fas fa-cubes"></i>
                      <span class="h1">GPS</span>
                    </div>
                    <h5 class="fw-normal mb-3">Sign into your account</h5>
                    <form method="POST" action="{{ route('login') }}">
                      @csrf
                      <div class="form-outline mb-4">
                        <input type="email" id="email" class="form-control form-control-lg" name="email" />
                        <label class="form-label" for="email" >Email address</label>
                      </div>
                      <div class="form-outline mb-4">
                        <input type="password" id="password" class="form-control form-control-lg" name="password" />
                        <label class="form-label" for="password" >Password</label>
                      </div>
                      <div class="pt-1 mb-4">
                        <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                      </div>
                    <a class="small text-muted" href="javascript:void(0);" onclick="showForgetPasswordForm()">Forgot password?</a>
                    <p class="mb-5" style="color: #393f81;">Don't have an account? <a href="{{route('register')}}" style="color: #393f81;">Register here</a></p>
                    <a href="#!" class="small text-muted">Terms of use.</a>
                    <a href="#!" class="small text-muted">Privacy policy</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="vh-100" id="forget-password-page" style="display: none;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card">
            <div class="card-body">
              <form>
                <div class="logo-container">
                  <i class="fas fa-cubes"></i>
                  <span class="h1">GPS</span>
                </div>
                <h5 class="fw-normal mb-3">Forgot Password</h5>
                <p class="mb-4">Enter your email address, and we'll send you a link to reset your password.</p>
                <div class="form-outline mb-4">
                  <input type="email" id="form2ExampleForgetEmail" class="form-control form-control-lg" />
                  <label class="form-label" for="form2ExampleForgetEmail">Email address</label>
                </div>
                <div class="pt-1 mb-4">
                  <button class="btn btn-dark btn-lg btn-block" type="button">Reset Password</button>
                </div>
                <p class="mb-5" style="color: #393f81;">Remember your password? <a href="javascript:void(0);"
                    onclick="showLoginForm()" style="color: #393f81;">Login here</a></p>
                <a href="#!" class="small text-muted">Terms of use.</a>
                <a href="#!" class="small text-muted">Privacy policy</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    function showRegisterForm() {
      document.getElementById('login-page').style.display = 'none';
      document.getElementById('register-page').style.display = 'block';
      document.getElementById('forget-password-page').style.display = 'none';
    }

    function showLoginForm() {
      document.getElementById('login-page').style.display = 'block';
      document.getElementById('register-page').style.display = 'none';
      document.getElementById('forget-password-page').style.display = 'none';
    }

    function showForgetPasswordForm() {
      document.getElementById('login-page').style.display = 'none';
      document.getElementById('register-page').style.display = 'none';
      document.getElementById('forget-password-page').style.display = 'block';
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>