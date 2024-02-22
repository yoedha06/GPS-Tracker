<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/main/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="/images/gpslogo.png" rel="icon">

    <link rel="stylesheet" href="{{ asset('template/assets/css/shared/iconly.css') }}">
</head>
<body>
   <h2>User List</h2>

   <table class="table table-striped">
    <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
</thead>
<tbody>
    @foreach($users as $user)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
            @if($user->is_verified)
                <span style="color: green;">Verified</span>
            @else
                <span style="color: red;">Not Verified</span>
            @endif
        </td>
    </tr>
    @endforeach
</tbody>
</table>
</body>
</html>
