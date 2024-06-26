@extends('layouts.admin')

<title>GEEX - Data User</title>

@section('content')
    @include('layouts.navbaradmin')

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
                                <li class="breadcrumb-item">
                                    <a href="/admin">
                                        <i class="bi bi-person-check-fill"></i> Admin
                                    </a>
                                </li>
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
                                <th>No Telp</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->phone }}</td>
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
                            @endif
                            <tr id="noDataFound" style="display: none;">
                                <td colspan="6" class="text-center">Data not found.</td>
                            </tr>
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
            const tableBody = document.getElementById('userTableBody');
            const tableRows = tableBody.getElementsByTagName('tr');
            const noDataFound = document.getElementById('noDataFound');
            let dataFound = false;

            for (let i = 0; i < tableRows.length; i++) {
                const user = tableRows[i];

                if (user.id === 'noDataFound') {
                    continue;
                }

                const name = user.getElementsByTagName('td')[1].innerText.toLowerCase();
                const username = user.getElementsByTagName('td')[2].innerText.toLowerCase();
                const email = user.getElementsByTagName('td')[3].innerText.toLowerCase();
                const role = user.getElementsByTagName('td')[4].innerText.toLowerCase();
                const status = user.getElementsByTagName('td')[5].innerText.toLowerCase();

                if (name.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm) || role
                    .includes(searchTerm) || status.includes(searchTerm)) {
                    user.style.display = '';
                    dataFound = true;
                } else {
                    user.style.display = 'none';
                }
            }

            if (dataFound) {
                noDataFound.style.display = 'none';
            } else {
                noDataFound.style.display = '';
            }
        }
    </script>

@endsection
