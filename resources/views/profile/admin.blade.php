@extends('layouts.admin')

<title>GEEX - Profile</title>

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
                    <h3>Account Profile</h3>
                    <p class="text-subtitle text-muted">A page where users can change profile information</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div class="avatar avatar-2xl"
                                    style="width: 180px; height: 180px; overflow: hidden; border-radius: 50%;">
                                    <!-- Gambar Profil -->
                                    @if (Auth::user()->photo)
                                        <img src="/photos/{{ Auth::user()->photo }}"
                                            style="width: 100%; user-drag: none; -webkit-user-drag: none; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/default.jpg') }}"
                                            style="width: 100%;  user-drag: none; -webkit-user-drag: none;  height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                    
                                <!-- Nama User -->
                                <h3 class="mt-3" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{ $user->name }}</h3>
                    
                                <!-- Tombol Hapus Foto -->
                                @if ($user->photo)
                                    <form action="{{ route('delete.photo.customer') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mt-2">Hapus Foto</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>                    
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update', $user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Your Name" value="{{ $user->name }}" fdprocessedid="pczq">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Your Username"
                                    value="{{ $user->username }}" fdprocessedid="cgz6v"
                                    style="background-color: #f8f8f8;" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Your Email" value="{{ $user->email }}" fdprocessedid="4hujis">
                            </div>
                            <div class="form-group">
                                <label for="photo" class="form-label">Profile Picture</label>
                                <input type="file" name="photo" id="photo" class="form-control"
                                    accept="image/*">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" fdprocessedid="vp6voe">Save
                                    Changes</button>
                                <a href="/admin" class="btn btn-primary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </section>
</div>

<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>2023 © BARUDAK CIGS</p>
        </div>
        <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                by BARUDAK CIGS</p>
        </div>
    </div>
</footer>
</div>
