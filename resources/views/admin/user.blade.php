@extends('layout.admin')
@section('content')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

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
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @auth
                        @php $no = 1 @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ Auth::user()->name }}</td>
                            <td>{{ Auth::user()->username }}</td>
                            <td>{{ Auth::user()->email }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="password" id="password" value="{{ Auth::user()->password }}" class="form-control" aria-describedby="basic-addon2" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </td>
                            <td>{{ Auth::user()->role }}</td>
                            <td>
                                @if(Auth::user()->is_verified)
                                    <span style="color: green;">Verified</span>
                                @else
                                    <span style="color: red;">Not Verified</span>
                                @endif
                            </td>
                        </tr>
                        @endauth
                    </tbody>
                </table>
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
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
</script>
@endsection
