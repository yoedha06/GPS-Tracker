@extends('layouts.admin')

<title>GEEX - Settings</title>

@section('content')
@include('layouts.navbaradmin')

    <div id="main">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin"><i class="bi bi-person-check-fill"></i>
                                        Admin</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-cog"></i>
                                    Settings</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaturan -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Team</span>
                @if (!$pengaturan)
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddPengaturan">
                        <i class="bi bi-plus"></i> Add Pengaturan
                    </button>
                @endif
            </div>
            <hr>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <table class="table table-striped" id="table1" style="table-layout: auto">
                        <thead>
                            <tr>
                        <tbody>
                            @if ($pengaturan)
                                <tr>
                                    <td>Title :</td>
                                    <td>{{ $pengaturan->title_pengaturan }}</td>
                                </tr>
                                <tr>
                                    <td>Name :</td>
                                    <td>{{ $pengaturan->name_pengaturan }}</td>
                                </tr>
                                <tr>
                                    <td>Logo :</td>
                                    <td>
                                        @if ($pengaturan->logo)
                                            <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo"
                                                style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal" data-target="#viewLogoPhotoModal{{ $pengaturan->id }}">
                                        @else
                                            No Logo Available
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Background :</td>
                                    <td>
                                        @if ($pengaturan->background)
                                            <img src="{{ asset('storage/' . $pengaturan->background) }}" alt="Background"
                                                style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal"
                                                data-target="#viewBackgroundPhotoModal{{ $pengaturan->id }}">
                                        @else
                                            No Background Available
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Action :</td>
                                    <td colspan="3">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#editModalPengaturan{{ $pengaturan->id }}">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>Title :</td>
                                    <td>No data available</td>
                                </tr>
                                <tr>
                                    <td>Name :</td>
                                    <td>No data available</td>
                                </tr>
                                <tr>
                                    <td>Logo :</td>
                                    <td>No data available</td>
                                </tr>
                                <tr>
                                    <td>Background :</td>
                                    <td>No data available</td>
                                </tr>
                            @endif
                        </tbody>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pengaturan -->
        @if ($pengaturan)
            <div class="modal fade" id="editModalPengaturan{{ $pengaturan->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalPengaturanLabel{{ $pengaturan->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalPengaturanLabel{{ $pengaturan->id }}">Edit Pengaturan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('pengaturan.update', $pengaturan->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $pengaturan->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="title_pengaturan">Title<span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="title_pengaturan" name="title_pengaturan"
                                        value="{{ $pengaturan->title_pengaturan }}">
                                </div>
                                <div class="form-group">
                                    <label for="name_pengaturan">Name<span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="name_pengaturan" name="name_pengaturan"
                                        value="{{ $pengaturan->name_pengaturan }}">
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo:</label>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                        onchange="previewImage(this, 'logoPreview')" value="{{ $pengaturan->logo }}">
                                    <img id="logoPreview" src="{{ asset('storage/' . $pengaturan->logo) }}"
                                        alt="Preview Logo"
                                        style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                </div>
                                <div class="form-group">
                                    <label for="background">Background:</label>
                                    <input type="file" class="form-control" id="background" name="background"
                                        onchange="previewImage(this, 'backgroundPreview')"
                                        value="{{ $pengaturan->background }}">
                                    <img id="backgroundPreview" src="{{ asset('storage/' . $pengaturan->background) }}"
                                        alt="backgroundPreview"
                                        style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>
                                    Save
                                    changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Add Pengaturan -->
        <div class="modal fade" id="modalAddPengaturan" tabindex="-1" role="dialog"
            aria-labelledby="modalAddPengaturanLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddPengaturanLabel">Add Pengaturan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('pengaturan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title_pengaturan">Title <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control" id="title_pengaturan" name="title_pengaturan" value="{{ old('title_pengaturan') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="name_pengaturan">Name <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control" id="name_pengaturan" name="name_pengaturan" value=" {{ old('name_pengaturan') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Logo:</label>
                                <input type="file" class="form-control" id="logo" name="logo" value="{{ old('logo') }}"
                                    onchange="previewImage(this, 'logoPreview')">
                                <img id="logoPreview" src="#" alt="Preview Logo"
                                    style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px; display: none;">
                            </div>
                            <div class="form-group">
                                <label for="background">Background:</label>
                                <input type="file" class="form-control" id="background" name="background" value="{{ old('background') }}"
                                    onchange="previewImage(this, 'backgroundPreview')">
                                <img id="backgroundPreview" src="#" alt="backgroundPreview"
                                    style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px; display: none;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"> <i class="bi bi-plus"></i> Add
                                Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal View Photo Logo -->
        @if ($pengaturan)
            <div class="modal fade" id="viewLogoPhotoModal{{ $pengaturan->id }}" tabindex="-1" role="dialog"
                aria-labelledby="viewLogoPhotoModalLabel{{ $pengaturan->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewLogoPhotoModalLabel{{ $pengaturan->id }}">View Logo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal View Photo Background -->
        @if ($pengaturan)
            <div class="modal fade" id="viewBackgroundPhotoModal{{ $pengaturan->id }}" tabindex="-1" role="dialog"
                aria-labelledby="viewBackgroundPhotoModalLabel{{ $pengaturan->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewBackgroundPhotoModalLabel{{ $pengaturan->id }}">View
                                Background
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $pengaturan->background) }}" alt="Background"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <!-- Konten About -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>About</span>
                @if (!$about)
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddAbout">
                        <i class="bi bi-plus"></i> Add About
                    </button>
                @endif
            </div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                <tbody>
                                    @if ($about)
                                        <tr>
                                            <td>Title About</td>
                                            <td>{{ $about->title_about }}</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kiri</td>
                                            <td>
                                                <div>{{ $about->left_description }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kanan</td>
                                            <td>
                                                <div>{{ $about->right_description }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 1</td>
                                            <td>{{ $about->feature_1 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 2</td>
                                            <td>{{ $about->feature_2 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 3</td>
                                            <td>{{ $about->feature_3 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Action :</td>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModalAbout{{ $about->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>Title About</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kiri</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kanan</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 1</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 2</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 3</td>
                                            <td>No data available</td>
                                        </tr>
                                </tbody>
                                @endif
                            </table>
                        <tr>
                        </tr>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Modal Edit About -->
        @if ($about)
            <div class="modal fade" id="editModalAbout{{ $about->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalAbout" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit About</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('about.update', $about->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $about->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="title_about">Title <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="title_about" name="title_about"
                                        value="{{ $about->title_about }}">
                                </div>
                                <div class="form-group">
                                    <label for="left_description">Deskripsi Kiri <span style="color: red">*</span>
                                        :</label>
                                    <textarea class="form-control" id="left_description" name="left_description">{{ $about->left_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="right_description">Deskripsi Kanan <span
                                            style="color: red">*</span></label>
                                    <textarea class="form-control" id="right_description" name="right_description">{{ $about->right_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="feature_1">Fitur 1 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_1" name="feature_1"
                                        value="{{ $about->feature_1 }}">
                                </div>
                                <div class="form-group">
                                    <label for="feature_2">Fitur 2 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_2" name="feature_2"
                                        value="{{ $about->feature_2 }}">
                                </div>
                                <div class="form-group">
                                    <label for="feature_3">Fitur 3 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_3" name="feature_3"
                                        value="{{ $about->feature_3 }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>
                                    Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Add About -->
        <div class="modal fade" id="modalAddAbout" tabindex="-1" role="dialog" aria-labelledby="modalAddAboutLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddAboutLabel">Add About</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('about.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title_about">Title About <span style="color: red">*</span>:</label>
                                <input type="text" class="form-control" id="title_about" name="title_about" value="{{ old('title_about') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="left_description">Deskripsi Kiri <span style="color: red">*</span>:</label>
                                <textarea class="form-control" id="left_description" name="left_description"  value ="{{ old('left_description') }}" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="right_description">Deskripsi Kanan <span style="color: red">*</span>:</label>
                                <textarea class="form-control" id="right_description" name="right_description" value ="{{ old('right_description') }}" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="feature_1">Fitur 1:</label>
                                <input type="text" class="form-control" id="feature_1" name="feature_1" value="{{ old('feature_1') }}">
                            </div>
                            <div class="form-group">
                                <label for="feature_2">Fitur 2:</label>
                                <input type="text" class="form-control" id="feature_2" name="feature_2" value="{{ old('feature_2') }}">
                            </div>
                            <div class="form-group">
                                <label for="feature_3">Fitur 3:</label>
                                <input type="text" class="form-control" id="feature_3" name="feature_3" value="{{ old('feature_3') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i> Add
                                About</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Tim -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between">
                <span>Team</span>
                @if ($team->isEmpty())
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddInformasi">
                        <i class="bi bi-plus"></i> Add Team
                    </button>
                @endif
            </div>
            <hr>
            <div class="card-body">
                <tr>
                    <thead>
                        <!-- Informasi -->
                        <table class="table table-striped" id="table2" style="table-layout: auto">
                            @forelse ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Informasi</td>
                                        <td>{{ $item->informasi }}</td>
                                    </tr>
                                    <tr>
                                        <td>Action</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editinfomasi{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>Informasi</td>
                                        <td>No data available</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </thead>
                    <br>
                    <br>
                    <hr>
                    <!-- Modal Edit Informasi -->
                    @foreach ($team as $item)
                        <div class="modal fade" id="editinfomasi{{ $item->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="editinfomasi" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Informasi Team</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('informasi.update', $item->id) }}" method="POST"
                                        enctype="multipart/form-data" id="editForm{{ $item->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="Informasi">Informasi <span
                                                        style="color: red">*</span>:</label>
                                                <textarea class="form-control" id="informasi" name="informasi">{{ $item->informasi }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fa-regular fa-floppy-disk"></i>
                                                Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Modal Add Team -->
                    <div class="modal fade" id="modalAddInformasi" tabindex="-1" role="dialog"
                        aria-labelledby="modalAddInformasiLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAddInformasiLabel">Add Informasi Team</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('team.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="informasi">Informasi <span
                                                        style="color: red">*</span>:</label>
                                                <textarea class="form-control" id="informasi" name="informasi" rows="3" required>{{ old('informasi') }}</textarea>
                                            </div>
                                            <hr>

                                            <!-- Team 1 -->
                                            <div class="col-md-6 mb-4">
                                                <h4>Team 1</h4>
                                                <div class="form-group">
                                                    <label for="username_1">Username 1:</label>
                                                    <input type="text" class="form-control" id="username_1"
                                                        name="username_1" value="{{ old('username_1') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="posisi_1">Posisi 1:</label>
                                                    <input type="text" class="form-control" id="posisi_1"
                                                        name="posisi_1" value="{{ old('posisi_1') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi_1">Deskripsi 1:</label>
                                                    <textarea class="form-control" id="deskripsi_1" name="deskripsi_1" rows="3">{{ old('deskripsi_1') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo_1">Photo 1:</label>
                                                    <input type="file" class="form-control" id="photo_1"
                                                        name="photo_1">
                                                </div>
                                            </div>

                                            <!-- Team 2 -->
                                            <div class="col-md-6 mb-4">
                                                <h4>Team 2</h4>
                                                <div class="form-group">
                                                    <label for="username_2">Username 2:</label>
                                                    <input type="text" class="form-control" id="username_2"
                                                        name="username_2" value="{{ old('username_2') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="posisi_2">Posisi 2:</label>
                                                    <input type="text" class="form-control" id="posisi_2"
                                                        name="posisi_2" value="{{ old('posisi_2') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi_2">Deskripsi 2:</label>
                                                    <textarea class="form-control" id="deskripsi_2" name="deskripsi_2" rows="3">{{ old('deskripsi_2') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo_2">Photo 2:</label>
                                                    <input type="file" class="form-control" id="photo_2"
                                                        name="photo_2">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">

                                            <!-- Team 3 -->
                                            <div class="col-md-6 mb-4">
                                                <h4>Team 3</h4>
                                                <div class="form-group">
                                                    <label for="username_3">Username 3:</label>
                                                    <input type="text" class="form-control" id="username_3"
                                                        name="username_3" value="{{ old('username_3') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="posisi_3">Posisi 3:</label>
                                                    <input type="text" class="form-control" id="posisi_3"
                                                        name="posisi_3" value="{{ old('posisi_3') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi_3">Deskripsi 3:</label>
                                                    <textarea class="form-control" id="deskripsi_3" name="deskripsi_3" rows="3">{{ old('deskripsi_3') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo_3">Photo 3:</label>
                                                    <input type="file" class="form-control" id="photo_3"
                                                        name="photo_3">
                                                </div>
                                            </div>

                                            <!-- Team 4 -->
                                            <div class="col-md-6 mb-4">
                                                <h4>Team 4</h4>
                                                <div class="form-group">
                                                    <label for="username_4">Username 4:</label>
                                                    <input type="text" class="form-control" id="username_4"
                                                        name="username_4" value="{{ old('username_4') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="posisi_4">Posisi 4:</label>
                                                    <input type="text" class="form-control" id="posisi_4"
                                                        name="posisi_4" value="{{ old('posisi_4') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="deskripsi_4">Deskripsi 4:</label>
                                                    <textarea class="form-control" id="deskripsi_4" name="deskripsi_4" rows="3">{{ old('deskripsi_4') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photo_4">Photo 4:</label>
                                                    <input type="file" class="form-control" id="photo_4"
                                                        name="photo_4">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="bi bi-plus"></i> Add Team</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tim 1 -->
                    <thead>
                        <table class="table table-striped" id="table1" style="table-layout: auto">
                            @forelse ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 1</td>
                                        <td>{{ $item->username_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position 1</td>
                                        <td>{{ $item->posisi_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description 1</td>
                                        <td>{{ $item->deskripsi_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 1</td>
                                        <td>
                                            @if ($item->photo_1)
                                                <img src="{{ asset('storage/' . $item->photo_1) }}" alt="Photo 1"
                                                    style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                    data-toggle="modal" data-target="#phototeam1{{ $item->id }}">
                                            @else
                                                No Photo Available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam1{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td>Name 1</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Position 1</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Description 1</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 1</td>
                                        <td>No data available</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </thead>
                </tr>
                <br>
                <br>
                <br>
                <hr>

                <!-- Modal Edit Team 1 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="editModalteam1{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalteam1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Team 1</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('team1.update', $item->id) }}" method="POST"
                                    enctype="multipart/form-data" id="editForm{{ $item->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="Username">Username <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="username_1" name="username_1"
                                                value="{{ $item->username_1 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Posision">Posision <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="posisi_1" name="posisi_1"
                                                value="{{ $item->posisi_1 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Deskripsi">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi_1" name="deskripsi_1">{{ $item->deskripsi_1 }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="photo">Photo:</label>
                                            <input type="file" class="form-control" id="photo_1" name="photo_1"
                                                onchange="previewImage(this, 'preview_photo_1')"
                                                value="{{ $item->photo_1 }}">
                                        </div>
                                        <div class="form-group">
                                            <img id="preview_photo_1" src="{{ asset('storage/' . $item->photo_1) }}"
                                                alt="Preview Photo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-regular fa-floppy-disk"></i>
                                            Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modal Photo Team 1 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="phototeam1{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="phototeam1{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="phototeam1{{ $item->id }}">View Logo
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $item->photo_1) }}" alt="Logo"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Tim 2-->
                <tr>
                    <thead>
                        <table class="table table-striped" id="table2" style="table-layout: auto">
                            @forelse ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 2</td>
                                        <td>{{ $item->username_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position 2</td>
                                        <td>{{ $item->posisi_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description 2</td>
                                        <td>{{ $item->deskripsi_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 2</td>
                                        <td>
                                            @if ($item->photo_1)
                                                <img src="{{ asset('storage/' . $item->photo_2) }}" alt="Photo 2"
                                                    style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                    data-toggle="modal" data-target="#phototeam2{{ $item->id }}">
                                            @else
                                                No Photo Available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam2{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td>Name 2</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Position 2</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Description 2</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 2</td>
                                        <td>No data available</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </thead>
                </tr>
                <br>
                <br>
                <br>
                <hr>
                <!-- Modal Edit Team 2 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="editModalteam2{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalAbout" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Team 2</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('team2.update', $item->id) }}" method="POST"
                                    enctype="multipart/form-data" id="editForm{{ $item->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="Username">Username <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="username_2" name="username_2"
                                                value="{{ $item->username_2 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Posision">Posision <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="posisi_2" name="posisi_2"
                                                value="{{ $item->posisi_2 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Deskripsi">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi_2" name="deskripsi_2">{{ $item->deskripsi_2 }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="photo">Photo:</label>
                                            <input type="file" class="form-control" id="photo_2" name="photo_2"
                                                onchange="previewImage(this, 'preview_photo_2')"
                                                value="{{ $item->photo_1 }}">
                                        </div>
                                        <div class="form-group">
                                            <img id="preview_photo_2" src="{{ asset('storage/' . $item->photo_2) }}"
                                                alt="Preview Photo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-regular fa-floppy-disk"></i>
                                            Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modal Photo Team 2 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="phototeam2{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="phototeam2{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="phototeam1{{ $item->id }}">View Logo
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $item->photo_2) }}" alt="Logo"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Tim 3-->
                <tr>
                    <thead>
                        <table class="table table-striped" id="table3" style="table-layout: auto">
                            @forelse ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 3</td>
                                        <td>{{ $item->username_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position 3</td>
                                        <td>{{ $item->posisi_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description 3</td>
                                        <td>{{ $item->deskripsi_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 3</td>
                                        <td>
                                            @if ($item->photo_3)
                                                <img src="{{ asset('storage/' . $item->photo_3) }}" alt="Photo 3"
                                                    style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                    data-toggle="modal" data-target="#phototeam3{{ $item->id }}">
                                            @else
                                                No Photo Available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam3{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td>Name 3</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Position 3</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Description 3</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 3</td>
                                        <td>No data available</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </thead>
                </tr>
                <br>
                <br>
                <br>
                <hr>
                <!-- Modal Edit Team 3 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="editModalteam3{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalteam3" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Team 3</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('team3.update', $item->id) }}" method="POST"
                                    enctype="multipart/form-data" id="editForm{{ $item->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="Username">Username <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="username_3" name="username_3"
                                                value="{{ $item->username_3 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Posision">Posision <span style="color: red">*</span>
                                                :</label>
                                            <input type="text" class="form-control" id="posisi_3" name="posisi_3"
                                                value="{{ $item->posisi_3 }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="Deskripsi">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi_3" name="deskripsi_3">{{ $item->deskripsi_3 }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="photo">Photo:</label>
                                            <input type="file" class="form-control" id="photo_3" name="photo_3"
                                                onchange="previewImage(this, 'preview_photo_3')"
                                                value="{{ $item->photo_3 }}">
                                        </div>
                                        <div class="form-group">
                                            <img id="preview_photo_3" src="{{ asset('storage/' . $item->photo_3) }}"
                                                alt="Preview Photo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-regular fa-floppy-disk"></i>
                                            Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modal Photo Team 3 -->
                @foreach ($team as $item)
                    <div class="modal fade" id="phototeam3{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="phototeam3{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="phototeam1{{ $item->id }}">View Logo
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $item->photo_3) }}" alt="Logo"
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Tim 4 -->
                <tr>
                    <thead>
                        <table class="table table-striped" id="table4" style="table-layout: auto">
                            @forelse ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 4</td>
                                        <td>{{ $item->username_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Position 4</td>
                                        <td>{{ $item->posisi_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Description 4</td>
                                        <td>{{ $item->deskripsi_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 4</td>
                                        <td>
                                            @if ($item->photo_1)
                                                <img src="{{ asset('storage/' . $item->photo_4) }}" alt="Photo 4"
                                                    style="max-width: 150px; max-height: 150px; cursor: pointer; border-radius: 5px;"
                                                    data-toggle="modal" data-target="#phototeam4{{ $item->id }}">
                                            @else
                                                No Photo Available
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam4{{ $item->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td>Name 4</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Position 4</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Description 4</td>
                                        <td>No data available</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 4</td>
                                        <td>No data available</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </thead>
                </tr>
            </div>
        </div>

        <!-- Modal Edit Team 4 -->
        @foreach ($team as $item)
            <div class="modal fade" id="editModalteam4{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalteam4" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Team 3</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('team4.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="Username">Username <span style="color: red">*</span>
                                        :</label>
                                    <input type="text" class="form-control" id="username_4" name="username_4"
                                        value="{{ $item->username_4 }}">
                                </div>
                                <div class="form-group">
                                    <label for="Posision">Posision <span style="color: red">*</span>
                                        :</label>
                                    <input type="text" class="form-control" id="posisi_4" name="posisi_4"
                                        value="{{ $item->posisi_4 }}">
                                </div>
                                <div class="form-group">
                                    <label for="Deskripsi">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi_4" name="deskripsi_4">{{ $item->deskripsi_4 }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="photo">Photo:</label>
                                    <input type="file" class="form-control" id="photo_4" name="photo_4"
                                        onchange="previewImage(this, 'preview_photo_4')" value="{{ $item->photo_4 }}">
                                </div>
                                <div class="form-group">
                                    <img id="preview_photo_4" src="{{ asset('storage/' . $item->photo_4) }}"
                                        alt="Preview Photo"
                                        style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>
                                    Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Photo Team 4 -->
        @foreach ($team as $item)
            <div class="modal fade" id="phototeam4{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="phototeam4{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="phototeam1{{ $item->id }}">View Logo
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $item->photo_4) }}" alt="Logo" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Informasi Kontak -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between">
                <span>Informasi Contact </span>
                @if ($contact->isEmpty())
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#addModalInformasiContact">
                        <i class="bi bi-plus"></i> Add Informasi Contact 
                    </button>
                @endif
            </div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                @forelse ($contact as $item)
                                    <tbody>
                                        <tr>
                                            <td>Name Location</td>
                                            <td>{{ $item->name_location }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $item->email_informasi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Call</td>
                                            <td>{{ $item->call_informasi }}</td>
                                        </tr>
                                        <tr>
                                            <td>Action :</td>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModalInformasiContact{{ $item->id }}"
                                                    style="margin-bottom:4px;">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Name Location</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Call</td>
                                            <td>No data available</td>
                                        </tr>
                                    </tbody>
                                @endforelse
                            </table>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Modal Edit Informasi Contact -->
        @foreach ($contact as $item)
            <div class="modal fade" id="editModalInformasiContact{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalInformasiContact" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Informasi Contact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('informasicontact.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name_location">Name Location <span style="color: red">*</span>
                                        :</label>
                                    <input type="text" class="form-control" id="name_location" name="name_location"
                                        value="{{ $item->name_location }}">
                                </div>
                                <div class="form-group">
                                    <label for="email_informasi">Email <span style="color: red">*</span>
                                        :</label>
                                    <input type="text" class="form-control" id="email_informasi"
                                        name="email_informasi" value="{{ $item->email_informasi }}">
                                </div>
                                <div class="form-group">
                                    <label for="call_informasi">Call <span style="color: red">*</span>
                                        :</label>
                                    <input type="number" class="form-control" id="call_informasi"
                                        name="call_informasi" value="{{ $item->call_informasi }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa-regular fa-floppy-disk"></i>
                                    Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Add Informasi Contact -->
        <div class="modal fade" id="addModalInformasiContact" tabindex="-1" role="dialog"
            aria-labelledby="addModalInformasiContact" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Informasi Contact</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('informasicontact.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name_location">Name Location <span style="color: red">*</span>:</label>
                                <input type="text" class="form-control" id="name_location" name="name_location"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="email_informasi">Email <span style="color: red">*</span>:</label>
                                <input type="text" class="form-control" id="email_informasi"
                                    name="email_informasi" required>
                            </div>
                            <div class="form-group">
                                <label for="call_informasi">Call <span style="color: red">*</span>:</label>
                                <input type="number" class="form-control" id="call_informasi" name="call_informasi"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Add Informasi Contact 
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informasi Sosmed -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Sosmed</span>
                <tr>
                    @if($sosmed->isEmpty())
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddsosmed">
                        <i class="bi bi-plus"></i> Add Sosmed
                    </button>
                    @endif
                </tr>
            </div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                @forelse ($sosmed as $item)
                                    <tbody>
                                        <tr>
                                            <td>Title</td>
                                            <td>{{ $item->title_sosmed }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Jalan</td>
                                            <td>{{ $item->street_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamatan</td>
                                            <td>{{ $item->subdistrict }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelurahan</td>
                                            <td>{{ $item->ward }}</td>
                                        </tr>
                                        <tr>
                                            <td>Call</td>
                                            <td>{{ $item->call }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>{{ $item->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Action</td>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModalInformasiSosmed{{ $item->id }}"
                                                    style="margin-bottom:4px;">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Title</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Jalan</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamatan</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Kelurahan</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Call</td>
                                            <td>No data available</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>No data available</td>
                                        </tr>
                                    </tbody>
                            </table>
                        </tr>
                    </thead>
                    @endforelse
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Informasi Sosmed -->
    @foreach ($sosmed as $item)
        <div class="modal fade" id="editModalInformasiSosmed{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editModalInformasiSosmed" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Informasi Sosmed</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('informasisosmed.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data" id="editForm{{ $item->id }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title_sosmed">Title <span style="color: red">*</span>
                                    :</label>
                                <input type="text" class="form-control" id="title_sosmed" name="title_sosmed"
                                    value="{{ $item->title_sosmed }}">
                            </div>
                            <div class="form-group">
                                <label for="street_name">Jalan <span style="color: red">*</span>
                                    :</label>
                                <input type="text" class="form-control" id="street_name" name="street_name"
                                    value="{{ $item->street_name }}">
                            </div>
                            <div class="form-group">
                                <label for="subdistrict">Kecamatan <span style="color: red">*</span>
                                    :</label>
                                <input type="teks" class="form-control" id="subdistrict" name="subdistrict"
                                    value="{{ $item->subdistrict }}">
                            </div>
                            <div class="form-group">
                                <label for="ward">Kelurahan <span style="color: red">*</span>
                                    :</label>
                                <input type="teks" class="form-control" id="ward" name="ward"
                                    value="{{ $item->ward }}">
                            </div>
                            <div class="form-group">
                                <label for="call">Call <span style="color: red">*</span>
                                    :</label>
                                <input type="number" class="form-control" id="call" name="call"
                                    value="{{ $item->call }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span style="color: red">*</span>
                                    :</label>
                                <input type="teks" class="form-control" id="email" name="email"
                                    value="{{ $item->email }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>
                                Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Add Informasi Sosmed -->
    <div class="modal fade" id="modalAddsosmed" tabindex="-1" role="dialog"
     aria-labelledby="modalAddsosmed" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Informasi Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('informasisosmed.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title_sosmed">Title Sosmed <span style="color: red">*</span>:</label>
                            <input type="text" class="form-control" id="title_sosmed" name="title_sosmed" value="{{ old('title_sosmed') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="street_name">Street Name <span style="color: red">*</span>:</label>
                            <input type="text" class="form-control" id="street_name"
                                name="street_name" value="{{ old('street_name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="subdistrict">Subdistrict <span style="color: red">*</span>:</label>
                            <input type="text" class="form-control" id="subdistrict" name="subdistrict"value="{{ old('subdistrict') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="ward">Ward <span style="color: red">*</span>:</label>
                            <input type="text" class="form-control" id="ward" name="ward" value="{{ old('ward') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="call">Call <span style="color: red">*</span>:</label>
                            <input type="number" class="form-control" id="call" name="call" value="{{ old('call') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span style="color: red">*</span>:</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add Informasi Contact 
                    </div>
                </form>
            </div>
        </div>
     </div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function previewImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]); // Mengkonversi gambar ke URL data
        }
    }
</script>

@endsection