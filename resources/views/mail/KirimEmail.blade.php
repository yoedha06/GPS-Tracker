<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Kirim</title>
</head>
<body>
    <h1>Selamat datang di aplikasi kami!</h1>
    <p>Terima kasih atas kunjungan Anda.</p>
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo">
</body>
</html>
