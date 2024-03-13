@extends('layouts.admin')
@extends('layouts.navbaradmin')
@section('content')
    <div id="main">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>DataUser</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">DataUser</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    Simple DataUser
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
            const userData = {!! json_encode($users) !!}; // Convert PHP array to JavaScript

            // Filter users based on the search term
            const filteredResults = userData.filter(user => user.name.toLowerCase().includes(searchTerm) || user.username
                .toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm));

            // Display search results
            if (filteredResults.length > 0) {
                tableBody.innerHTML = ''; // Clear existing table body

                filteredResults.forEach((user, index) => {
                    const resultItem = document.createElement('tr');
                    resultItem.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${user.name}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>
                        ${user.email_verified_at ? '<span style="color: green;">Verified</span>' : '<span style="color: red;">Not Verified</span>'}
                    </td>
                `;
                    tableBody.appendChild(resultItem);
                });

            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No users found</td></tr>';
            }
        }   
    </script>
@endsection
