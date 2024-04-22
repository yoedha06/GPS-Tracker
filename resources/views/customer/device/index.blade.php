@extends('layouts.customer')

<title>GEEX - Device</title>
<style>
    @media (max-width: 768px) {

        /* Contoh penyesuaian CSS untuk layar kecil */
        .cardContainer {
            padding-right: 15px;
            padding-left: 15px;
        }
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Ubah warna dan opacity (alpha) sesuai kebutuhan */
        transition: box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        /* Efek box shadow saat mouse hover, sesuaikan sesuai kebutuhan */
    }
</style>
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
                                <li class="breadcrumb-item"><a href="/customer"><i class="fas fa-tachometer-alt"></i>
                                        Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><i class="bi bi-hdd-stack-fill"></i>
                                    Data Device</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3" style="margin-top:-50px;">
            <label for="search" class="form-label"></label>
            <input type="text" id="search" placeholder="Search Device ..." class="form-control"
                oninput="liveSearch()">
        </div>

        <div id="searchResults" class="mt-3"></div>
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Device User</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                        <i class="bi bi-plus"></i> Add Device
                    </button>
                </div>

                <div class="card-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    @if (Session::has('photo_format_error'))
                        <div class="alert alert-danger">
                            {{ Session::get('photo_format_error') }}
                        </div>
                    @endif
                    @if (Session::has('photo_format_error'))
                        <div class="alert alert-danger">
                            {{ Session::get('photo_format_error') }}
                        </div>
                    @endif
                    @if ($errors->has('plat_nomor'))
                        <div class="alert alert-danger">
                            {{ $errors->first('plat_nomor') }}
                        </div>
                    @endif
                    @if ($errors && $errors->has('serial_number'))
                        <div class="alert alert-danger">
                            {{ $errors->first('serial_number') }}
                        </div>
                    @endif
                    <div id="cardContainer" class="row row-cols-1 row-cols-md-2 g-4">
                        @forelse ($userDevices as $item)
                            <div class="col">
                                <div class="card" data-bs-toggle="modal"
                                    data-bs-target="#editDeviceModal{{ $item->id_device }}">
                                    <div class="card-body">
                                        @if ($item->photo)
                                            <img src="{{ asset('storage/' . $item->photo) }}"
                                                class="card-img-top view-photo" alt="Device Photo" data-bs-toggle="modal"
                                                data-bs-target="#viewPhotoModal{{ $item->id_device }}">
                                        @else
                                            <div class="text-center">
                                                <p>No Image</p>
                                                <p style="font-size: 100px;">&#x1F5FF;</p>
                                            </div>
                                        @endif
                                        <h5 class="card-title" style="margin-top: 5%">{{ $item->name }}</h5>
                                        <p class="card-text">Serial Number: {{ $item->serial_number }}</p>
                                        <p class="card-text">Plat Nomor: {{ $item->plat_nomor }}</p>
                                        @if ($item->timezone)
                                            <p class="card-text">Time Zone: UTC {{ $item->timezone }}</p>
                                        @else
                                            <p class="card-text">Time Zone: -</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">No Data Available</h5>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
    </div>

    <!-- ADD Device Modal -->
    <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDeviceModalLabel">Add Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDeviceForm" action="{{ route('device.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number"
                                value="{{ old('serial_number') }}"required>
                        </div>
                        <!-- Add Photo and Plat Nomor fields -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                onchange="previewPhoto(event)">
                            <img id="photoPreview" src="#" alt="Photo Preview"
                                style="max-width: 100%; margin-top: 10px; {{ old('photo') ? '' : 'display: none;' }}">
                        </div>
                        <div class="mb-3">
                            <label for="plat_nomor" class="form-label">Plat Nomor</label>
                            <input type="text" class="form-control" id="plat_nomor" name="plat_nomor"
                                value="{{ old('plat_nomor') }}">
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone" required>
                                <option value="" {{ is_null(old('timezone')) ? 'selected' : '' }} disabled>Select
                                    Timezone</option>
                                <option value="-11">UTC -11</option>
                                <option value="-10">UTC -10</option>
                                <option value="-9">UTC -9</option>
                                <option value="-8">UTC -8</option>
                                <option value="-7">UTC -7</option>
                                <option value="-6">UTC -6</option>
                                <option value="-5">UTC -5</option>
                                <option value="-4">UTC -4</option>
                                <option value="-3">UTC -3</option>
                                <option value="-3">UTC -2</option>
                                <option value="-2">UTC -1</option>
                                <option value="0"> UTC 0</option>
                                <option value="+1">UTC +1</option>
                                <option value="+2">UTC +2</option>
                                <option value="+3">UTC +3</option>
                                <option value="+4">UTC +4</option>
                                <option value="+5">UTC +5</option>
                                <option value="+6">UTC +6</option>
                                <option value="+7">UTC +7</option>
                                <option value="+8">UTC +8</option>
                                <option value="+9">UTC +9</option>
                                <option value="+10">UTC +10</option>
                                <option value="+11">UTC +11</option>
                                <option value="+11">UTC +12</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Device</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Device Modal -->
    @foreach ($userDevices as $item)
        <div class="modal fade" id="editDeviceModal{{ $item->id_device }}" tabindex="-1"
            aria-labelledby="editDeviceModalLabel{{ $item->id_device }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDeviceModalLabel{{ $item->id_device }}">Edit Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('device.update', $item->id_device) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name{{ $item->id_device }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_name{{ $item->id_device }}"
                                    name="name" value="{{ $item->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_serial_number{{ $item->id_device }}" class="form-label">Serial
                                    Number</label>
                                <input type="text" class="form-control" id="edit_serial_number{{ $item->id_device }}"
                                    name="serial_number" value="{{ $item->serial_number }}" required readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Photo</label>
                                <input type="file" class="form-control" id="edit_photo{{ $item->id_device }}"
                                    name="photo" onchange="previewEditPhoto(this, {{ $item->id_device }})">
                                @if ($item->photo)
                                    <div class="mt-2">
                                        <img id="editPhotoPreview{{ $item->id_device }}"
                                            src="{{ asset('storage/' . $item->photo) }}" alt="Current Device Photo"
                                            style="max-width: 200px; max-height: 200px;">
                                    </div>
                                    <button type="button" class="btn btn-danger mt-2"
                                        onclick="deletePhoto({{ $item->id_device }})">
                                        <i class="fas fa-trash"></i> Delete Photo
                                    </button>
                                @else
                                    <p>No photo available.</p>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="edit_plat_nomor{{ $item->id_device }}" class="form-label">Plat Nomor</label>
                                <input type="text" class="form-control" id="edit_plat_nomor{{ $item->id_device }}"
                                    name="plat_nomor" value="{{ $item->plat_nomor }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_timezone{{ $item->id_device }}" class="form-label">Timezone</label>
                                <select class="form-select" id="edit_timezone{{ $item->id_device }}" name="timezone"
                                    required>
                                    <option value="" disabled>Select Timezone</option>
                                    <option value="-11" {{ $item->timezone == '-11' ? 'selected' : '' }}>UTC -11
                                    </option>
                                    <option value="-10" {{ $item->timezone == '-10' ? 'selected' : '' }}>UTC -10
                                    </option>
                                    <option value="-9" {{ $item->timezone == '-9' ? 'selected' : '' }}>UTC -9</option>
                                    <option value="-8" {{ $item->timezone == '-8' ? 'selected' : '' }}>UTC -8</option>
                                    <option value="-7" {{ $item->timezone == '-7' ? 'selected' : '' }}>UTC -7</option>
                                    <option value="-6" {{ $item->timezone == '-6' ? 'selected' : '' }}>UTC -6</option>
                                    <option value="-5" {{ $item->timezone == '-5' ? 'selected' : '' }}>UTC -5</option>
                                    <option value="-4" {{ $item->timezone == '-4' ? 'selected' : '' }}>UTC -4</option>
                                    <option value="-3" {{ $item->timezone == '-3' ? 'selected' : '' }}>UTC -3
                                    </option>
                                    <option value="-2" {{ $item->timezone == '-2' ? 'selected' : '' }}>UTC -2
                                    </option>
                                    <option value="-1" {{ $item->timezone == '-1' ? 'selected' : '' }}>UTC -1
                                    </option>
                                    <option value="0" {{ $item->timezone == '0' ? 'selected' : '' }}>UTC 0
                                    </option>
                                    <option value="+1" {{ $item->timezone == '+1' ? 'selected' : '' }}>UTC +1</option>
                                    <option value="+2" {{ $item->timezone == '+2' ? 'selected' : '' }}>UTC +2</option>
                                    <option value="+3" {{ $item->timezone == '+3' ? 'selected' : '' }}>UTC +3</option>
                                    <option value="+4" {{ $item->timezone == '+4' ? 'selected' : '' }}>UTC +4</option>
                                    <option value="+5" {{ $item->timezone == '+5' ? 'selected' : '' }}>UTC +5</option>
                                    <option value="+6" {{ $item->timezone == '+6' ? 'selected' : '' }}>UTC +6</option>
                                    <option value="+7" {{ $item->timezone == '+7' ? 'selected' : '' }}>UTC +7</option>
                                    <option value="+8" {{ $item->timezone == '+8' ? 'selected' : '' }}>UTC +8</option>
                                    <option value="+9" {{ $item->timezone == '+9' ? 'selected' : '' }}>UTC +9</option>
                                    <option value="+10" {{ $item->timezone == '+10' ? 'selected' : '' }}>UTC +10
                                    </option>
                                    <option value="+11" {{ $item->timezone == '+11' ? 'selected' : '' }}>UTC +11
                                    <option value="+11" {{ $item->timezone == '+12' ? 'selected' : '' }}>UTC +12
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteDeviceModal{{ $item->id_device }}">Delete Device</button>
                            <button type="submit" class="btn btn-primary">Update Device</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <!-- Delete Device Modals -->
    @foreach ($userDevices as $item)
        <div class="modal fade" id="deleteDeviceModal{{ $item->id_device }}" tabindex="-1"
            aria-labelledby="deleteDeviceModalLabel{{ $item->id_device }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteDeviceModalLabel{{ $item->id_device }}">Delete Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('device.destroy', $item->id_device) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p>Jika Kamu Menghapus Device,History Devicenya Akan Terhapus Juga.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Device</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modals Photo device -->
    @foreach ($userDevices as $item)
        <div class="modal fade" id="viewPhotoModal{{ $item->id_device }}" tabindex="-1"
            aria-labelledby="viewPhotoModalLabel{{ $item->id_device }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPhotoModalLabel{{ $item->id_device }}">Picture -
                            {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        @if ($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" alt="Device Photo"
                                style="max-width: 100%; max-height: 100vh; border-radius: 10px">
                        @else
                            No Image
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <footer>
        <div class="footer clearfix mb-0 text-muted">
            <div class="float-start">
                <p>2024 &copy; CIGS</p>
            </div>
            <div class="float-end">
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mengatur timezone menjadi UTC +7 ketika modal muncul
            $('#addDeviceModal').on('show.bs.modal', function(event) {
                $('#timezone').val('+7'); // Ubah sesuai dengan nilai timezone yang diinginkan
            });
        });

        $('#table1').on('click', '[data-bs-toggle="modal"]', function() {
            var targetModalId = $(this).data('bs-target');
            $(targetModalId).modal('show');
        });

        function liveSearch() {
            const searchInput = document.getElementById('search');
            const searchTerm = searchInput.value.toLowerCase();
            const cardContainer = document.getElementById('cardContainer');
            const deviceData = {!! $userDevices->toJson() !!};

            // Filter devices based on the search term
            const filteredResults = deviceData.filter(device =>
                device.name.toLowerCase().includes(searchTerm) ||
                device.serial_number.toLowerCase().includes(searchTerm) ||
                device.plat_nomor.toLowerCase().includes(searchTerm)
            );

            // Display search results
            if (filteredResults.length > 0) {
                cardContainer.innerHTML = ''; // Clear existing card container

                filteredResults.forEach(device => {
                    const cardItem = document.createElement('div');
                    cardItem.classList.add('col');
                    cardItem.innerHTML = `
                <div class="card" data-bs-toggle="modal" data-bs-target="#editDeviceModal${device.id_device}">
                    <div class="card-body">
                        <h5 class="card-title">${device.name}</h5>
                        <p class="card-text">Serial Number: ${device.serial_number}</p>
                        <p class="card-text">Plat Nomor: ${device.plat_nomor}</p>
                        <div class="text-center mt-3">
                            ${device.photo ? `<img src="{{ asset('storage/') }}/${device.photo}" class="card-img-top view-photo" alt="Device Photo" data-bs-toggle="modal" data-bs-target="#viewPhotoModal${device.id_device}">` : '<p>No Image</p><p style="font-size: 100px;">&#x1F5FF;</p>'}
                        </div>
                    </div>
                </div>
            `;
                    cardContainer.appendChild(cardItem);
                });
            } else {
                cardContainer.innerHTML =
                    '<div class="col"><div class="card"><div class="card-body"><h5 class="card-title">No devices found</h5></div></div></div>';
            }
        }


        function previewPhoto(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var preview = document.getElementById('photoPreview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }

        function previewEditPhoto(input, deviceId) {
            var previewId = 'editPhotoPreview' + deviceId;
            var preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Update preview of newly uploaded photo
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    // Update preview in the form popup if it's open
                    if (window.opener && !window.opener.closed) {
                        var editPhotoPreview = window.opener.document.getElementById(previewId);
                        if (editPhotoPreview) {
                            editPhotoPreview.src = e.target.result;
                        }
                    }
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function deletePhoto(deviceId) {
            // Send an AJAX request to your server to delete the photo
            $.ajax({
                type: 'DELETE',
                url: '/delete-photo/' + deviceId,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    // If the photo is successfully deleted, update the UI
                    $('#edit_photo' + deviceId).val('');
                    $('#edit_photo' + deviceId).siblings('.mt-2').remove();
                    $('#edit_photo' + deviceId).siblings('button').remove();
                    $('#edit_photo' + deviceId).siblings('p').text('No photo available.');

                    var previewId = 'editPhotoPreview' + deviceId;
                    var preview = document.getElementById(previewId);
                    preview.removeAttribute('data-new-photo-url');

                    $('#editPhotoMessage').html(
                        '<div class="alert alert-success">Photo deleted successfully</div>');
                },
                error: function(error) {
                    console.error('Error deleting photo:', error);
                    $('#editPhotoMessage').html('<div class="alert alert-danger">Failed to delete photo</div>');
                }
            });
        }
    </script>
@endsection
