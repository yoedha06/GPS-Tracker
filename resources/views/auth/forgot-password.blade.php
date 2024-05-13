<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: whitesmoke;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 1rem;
            width: 100%;
            max-width: 800px;
        }

        .card-header {
            background-color: #393f81;
            color: #fff;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background-color: #393f81;
            border-color: #393f81;
            margin-right: 10px;
            /* tambahkan margin right */
        }

        .btn-primary:hover {
            background-color: #2b2f5c;
            border-color: #2b2f5c;
        }

        /* tambahkan gaya untuk tombol kembali */
        .back-btn {
            margin-top: 20px;
        }

        /* tambahkan gaya untuk pesan kesalahan */
        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forgot Password</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" id="forgot_password_form">
                            @csrf

                            <div class="form-outline mb-4">
                                <label for="verification_option">Choose Contact Option</label>
                                <select id="verification_option" class="form-control" name="verification_option"
                                    required>
                                    <option value="">Select</option>
                                    <!-- Perhatikan bahwa nilai atribut "value" diubah menjadi kosong -->
                                    <option value="email">Email</option>
                                    <option value="phone">Phone</option>
                                </select>

                                <div id="email_field" class="form-outline mb-4" style="display: none;">
                                    <label for="email">Email Address</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div id="phone_field" class="form-outline mb-4" style="display: none;">
                                    <label for="phone">Phone Number</label>
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}" autofocus>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <!-- tambahkan class d-flex dan justify-content-between untuk sejajarkan tombol -->
                                <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                                <!-- tambahkan tombol kembali ke halaman login -->
                                <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var verificationOption = document.getElementById('verification_option');
            var form = document.getElementById('forgot_password_form');

            verificationOption.addEventListener('change', function() {
                if (verificationOption.value === 'email') {
                    form.action = "{{ route('password.email') }}";
                } else if (verificationOption.value === 'phone') {
                    form.action = "{{ route('password.phone') }}";
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            var emailField = document.getElementById('email_field');
            var phoneField = document.getElementById('phone_field');
            var verificationOption = document.getElementById('verification_option');

            // Toggle visibility based on selected input
            verificationOption.addEventListener('change', function() {
                if (verificationOption.value === 'email') {
                    emailField.style.display = 'block';
                    phoneField.style.display = 'none';
                } else if (verificationOption.value === 'phone') {
                    phoneField.style.display = 'block';
                    emailField.style.display = 'none';
                } else {
                    emailField.style.display = 'none';
                    phoneField.style.display = 'none';
                }
            });
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
