<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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
                  <form>
                    <div class="logo-container">
                      <i class="fas fa-cubes"></i>
                      <span class="h1">GPS</span>
                    </div>
                    <h5 class="fw-normal mb-3">Sign into your account</h5>
                    <div class="form-outline mb-4">
                      <input type="email" id="form2Example17" class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example17">Email address</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input type="password" id="form2Example27" class="form-control form-control-lg" />
                      <label class="form-label" for="form2Example27">Password</label>
                    </div>
                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="button">Login</button>
                    </div>
                    <a class="small text-muted" href="javascript:void(0);" onclick="showForgetPasswordForm()">Forgot password?</a>
                    <p class="mb-5" style="color: #393f81;">Don't have an account? <a href="javascript:void(0);"
                        onclick="showRegisterForm()" style="color: #393f81;">Register here</a></p>
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

  <section class="vh-100" id="register-page" style="display: none;">
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
                <h5 class="fw-normal mb-3">Register for an account</h5>
                <div class="form-outline mb-4">
                  <input type="text" id="form2ExampleName" class="form-control form-control-lg" />
                  <label class="form-label" for="form2ExampleName">Full Name</label>
                </div>
                <div class="form-outline mb-4">
                  <input type="email" id="form2ExampleEmail" class="form-control form-control-lg" />
                  <label class="form-label" for="form2ExampleEmail">Email address</label>
                </div>
                <div class="form-outline mb-4">
                  <input type="password" id="form2ExamplePassword" class="form-control form-control-lg" />
                  <label class="form-label" for="form2ExamplePassword">Password</label>
                </div>
                <div class="pt-1 mb-4">
                  <button class="btn btn-dark btn-lg btn-block" type="button">Register</button>
                </div>
                <p class="mb-5" style="color: #393f81;">Already have an account? <a href="javascript:void(0);"
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
</body>
</html>
