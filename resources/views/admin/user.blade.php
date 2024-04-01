@extends('layouts.admin')

<title>GEEX - Data User</title>

@extends('layouts.navbaradmin')
@section('content')
    <div id="main">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3></h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <i class="fas fa-users"></i> DataUser
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchUser" oninput="liveSearchUser()"
                            placeholder="Search..." aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <table class="table table-striped" id="table1">
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
                            @if (count($users) > 0)
                                @php $iteration = 1 @endphp
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            @if ($user->email_verified_at)
                                                <span style="color: green;">Verified</span>
                                            @else
                                                <span style="color: red;">Not Verified</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <span style="font-size: 3rem;">&#x1F5FF;</span>
                                        <p class="mt-2">Data not available, sorry.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $users->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </section>

        <footer>
            <div class="footer clearfix mb-0 text-muted">
                <div class="float-start">
                    <p>2024 &copy; CIGS</p>
                </div>
                <div class="float-end">
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        function liveSearchUser() {
            const searchInput = document.getElementById('searchUser');
            const searchTerm = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('table1').getElementsByTagName('tbody')[0];
            const tableRows = tableBody.getElementsByTagName('tr');

            for (let i = 0; i < tableRows.length; i++) {
                const user = tableRows[i];
                const name = user.getElementsByTagName('td')[1].innerText.toLowerCase();
                const username = user.getElementsByTagName('td')[2].innerText.toLowerCase();
                const email = user.getElementsByTagName('td')[3].innerText.toLowerCase();

                if (name.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm)) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        }
    </script>
@endsection
