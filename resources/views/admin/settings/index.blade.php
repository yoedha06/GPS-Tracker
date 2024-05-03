@extends('layouts.admin')
@extends('layouts.navbaradmin')
<title>GEEX - Settings</title>

@section('content')
    <div id="main">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
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
            <div class="card-header">Pengaturan</div>
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
                                <table class="table table-striped" id="table1" style="table-layout: auto">
                                    <tbody>
                                        @forelse ($pengaturan as $item)
                                            <tr>
                                                <td>Title :</td>
                                                <td>{{ $item->title_pengaturan }}</td>
                                            </tr>
                                            <tr>
                                                <td>Name :</td>
                                                <td>{{ $item->name_pengaturan }}</td>
                                            </tr>
                                            <tr>
                                                <td>Logo :</td>
                                                <td>
                                                    @if ($item->logo)
                                                        <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo"
                                                            style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                            data-toggle="modal"
                                                            data-target="#viewLogoPhotoModal{{ $item->id }}">
                                                    @else
                                                        No Logo Available
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Background :</td>
                                                <td>
                                                    @if ($item->background)
                                                        <img src="{{ asset('storage/' . $item->background) }}"
                                                            alt="Background"
                                                            style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                            data-toggle="modal"
                                                            data-target="#viewBackgroundPhotoModal{{ $item->id }}">
                                                    @else
                                                        No Background Available
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Action :</td>
                                                <td colspan="3">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#editModalPengaturan{{ $item->id }}">
                                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
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
                                            <tr>
                                                <td>Action :</td>
                                                <td colspan="3">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#editModalPengaturan">
                                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pengaturan -->
        @foreach ($pengaturan as $item)
            <div class="modal fade" id="editModalPengaturan{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalPengaturanLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalPengaturanLabel{{ $item->id }}">Edit Pengaturan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('pengaturan.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="title_pengaturan">Title <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="title_pengaturan" name="title_pengaturan"
                                        value="{{ $item->title_pengaturan }}">
                                </div>
                                <div class="form-group">
                                    <label for="name_pengaturan">Name<span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="name_pengaturan" name="name_pengaturan"
                                        value="{{ $item->name_pengaturan }}">
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo:</label>
                                    <input type="file" class="form-control" id="logo" name="logo"
                                        onchange="previewImage(this, 'logoPreview')" value="{{ $item->logo }}">
                                    <img id="logoPreview" src="{{ asset('storage/' . $item->logo) }}" alt="Preview Logo"
                                        style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;">
                                </div>
                                <div class="form-group">
                                    <label for="background">Background:</label>
                                    <input type="file" class="form-control" id="background" name="background"
                                        onchange="previewImage(this, 'backgroundPreview')"
                                        value="{{ $item->background }}">
                                    <img id="backgroundPreview" src="{{ asset('storage/' . $item->background) }}"
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
        @endforeach

        <!-- Modal View Photo Logo -->
        @foreach ($pengaturan as $item)
            <div class="modal fade" id="viewLogoPhotoModal{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="viewLogoPhotoModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewLogoPhotoModalLabel{{ $item->id }}">View Logo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal View Photo Background -->
        @foreach ($pengaturan as $item)
            <div class="modal fade" id="viewBackgroundPhotoModal{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="viewBackgroundPhotoModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewBackgroundPhotoModalLabel{{ $item->id }}">View Background
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $item->background) }}" alt="Background" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- Konten About -->
        <div class="card mt-4">
            <div class="card-header">Konten About</div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                @foreach ($about as $item)
                                    <tbody>
                                        <tr>
                                            <td>Title About</td>
                                            <td>{{ $item->title_about }}</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kiri</td>
                                            <td>
                                                <div>{{ $item->left_description }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi Kanan</td>
                                            <td>
                                                <div>{{ $item->right_description }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 1</td>
                                            <td>{{ $item->feature_1 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 2</td>
                                            <td>{{ $item->feature_2 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fitur 3</td>
                                            <td>{{ $item->feature_3 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Action :</td>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModalAbout{{ $item->id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        <tr>
                        </tr>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Modal Edit About -->
        @foreach ($about as $item)
            <div class="modal fade" id="editModalAbout{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModalAbout" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit About</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('about.update', $item->id) }}" method="POST"
                            enctype="multipart/form-data" id="editForm{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="title_about">Title <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="title_about" name="title_about"
                                        value="{{ $item->title_about }}">
                                </div>
                                <div class="form-group">
                                    <label for="left_description">Deskripsi Kiri <span style="color: red">*</span>
                                        :</label>
                                    <textarea class="form-control" id="left_description" name="left_description">{{ $item->left_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="right_description">Deskripsi Kanan <span
                                            style="color: red">*</span></label>
                                    <textarea class="form-control" id="right_description" name="right_description">{{ $item->right_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="feature_1">Fitur 1 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_1" name="feature_1"
                                        value="{{ $item->feature_1 }}">
                                </div>
                                <div class="form-group">
                                    <label for="feature_2">Fitur 2 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_2" name="feature_2"
                                        value="{{ $item->feature_2 }}">
                                </div>
                                <div class="form-group">
                                    <label for="feature_3">Fitur 3 <span style="color: red">*</span> :</label>
                                    <input type="text" class="form-control" id="feature_3" name="feature_3"
                                        value="{{ $item->feature_3 }}">
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

        <!-- Tim -->
        <div class="card mt-4">
            <div class="card-header">Team</div>
            <hr>
            <div class="card-body">
                <tr>
                    <thead>
                        <!-- Informasi -->
                        <table class="table table-striped" id="table2" style="table-layout: auto">
                            @foreach ($team as $item)
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
                                </tbody>
                            @endforeach
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


                    <!-- Tim 1 -->
                    <thead>
                        <table class="table table-striped" id="table2" style="table-layout: auto">
                            @foreach ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 1</td>
                                        <td>{{ $item->username_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Posision 1</td>
                                        <td>{{ $item->posisi_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi 1</td>
                                        <td>{{ $item->deskripsi_1 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 1</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->photo_1) }}" alt="Logo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal" data-target="#phototeam1{{ $item->id }}">
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
                            @endforeach
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
                        aria-labelledby="editModalAbout" aria-hidden="true">
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
                            @foreach ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 2</td>
                                        <td>{{ $item->username_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Posision 2</td>
                                        <td>{{ $item->posisi_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi 2</td>
                                        <td>{{ $item->deskripsi_2 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 2</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->photo_2) }}" alt="Logo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal" data-target="#phototeam2{{ $item->id }}">
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
                            @endforeach
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
                                                value="{{ $item->photo_2 }}">
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
                            @foreach ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 3</td>
                                        <td>{{ $item->username_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Posision 3</td>
                                        <td>{{ $item->posisi_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi 3</td>
                                        <td>{{ $item->deskripsi_3 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 3</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->photo_3) }}" alt="Logo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal" data-target="#phototeam3{{ $item->id }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam3{{ $item->id }}"
                                                style="margin-bottom:4px;">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
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
                        aria-labelledby="editModalAbout" aria-hidden="true">
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
                            @foreach ($team as $item)
                                <tbody>
                                    <tr>
                                        <td>Name 4</td>
                                        <td>{{ $item->username_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Posision 4</td>
                                        <td>{{ $item->posisi_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Deskripsi 4</td>
                                        <td>{{ $item->deskripsi_4 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Photo 4</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $item->photo_4) }}" alt="Logo"
                                                style="max-width: 200px; max-height: 200px; cursor: pointer; border-radius: 5px;"
                                                data-toggle="modal" data-target="#phototeam4{{ $item->id }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Action :</td>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModalTeam4{{ $item->id }}"
                                                style="margin-bottom:4px;">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
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
            <div class="card-header">Informasi Contact</div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                @foreach ($contact as $item)
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
                                        <td>Action :</td>
                                        <tr>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#editModalInformasiContact{{ $item->id }}"
                                                    style="margin-bottom:4px;">
                                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
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
                                    <input type="number" class="form-control" id="call_informasi" name="call_informasi"
                                        value="{{ $item->call_informasi }}">
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


        <!-- Informasi Sosmed -->
        <div class="card mt-4">
            <div class="card-header">Sosmed</div>
            <hr>
            <div class="card-body">
                <table class="table table-striped" id="table1" style="table-layout: auto">
                    <thead>
                        <tr>
                            <table class="table table-striped" id="table1" style="table-layout: auto">
                                @foreach ($sosmed as $item)
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
                                    </tbody>
                            </table>
                        </tr>
                    </thead>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection


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
