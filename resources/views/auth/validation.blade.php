<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation Reset</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        .card-header {
            background-color: #393f81;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center; /* Tambahkan untuk membuat teks menjadi tengah */
            padding: 1rem; /* Tambahkan padding untuk memperbaiki tata letak */
        }

    </style>
</head>
<body>
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                Password reset email has been sent to {{ session('reset_email') }}.
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <img src="{{ asset('images/geex.png') }}" style="width:200px;height:160px;" alt=""> <!-- Tambahkan gambar di header -->
            </div>
            <div class="card-body">
                <h4 class="alert-heading text-center">Email Reset Kata Sandi Terkirim</h4>
                <p class="text-center">Kami telah mengirim tautan pengaturan ulang kata sandi Anda melalui email. Silakan periksa kotak masuk email Anda untuk instruksi lebih lanjut.</p>
                <hr>
                <p class="text-center mb-0">Jika Anda tidak menerima email, silakan periksa folder spam Anda atau coba kirim ulang email pengaturan ulang kata sandi.</p>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
