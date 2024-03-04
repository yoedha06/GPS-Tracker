@extends('layouts.customer')

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
                                <li class="breadcrumb-item"><a href="/customer">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data Device</li>
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

        <div class="mb-3">
            <label for="search" class="form-label">Search:</label>
            <input type="text" id="search" class="form-control" oninput="liveSearch()">
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
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Serial Number</th>
                                <th>Plat Nomor</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($device as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->plat_nomor }}</td>
                                    <td>
                                        @if ($item->photo)
                                            <button type="button" class="btn btn-link" data-bs-toggle="modal"
                                                data-bs-target="#viewPhotoModal{{ $item->id_device }}">
                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="View Photo"
                                                    style="max-width: 100px; border-radius: 5px">
                                            </button>
                                        @else
                                            No Image
                                        @endif
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editDeviceModal{{ $item->id_device }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteDeviceModal{{ $item->id_device }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No devices found</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($errors && $errors->has('serial_number'))
                            <div class="alert alert-danger">
                                {{ $errors->first('serial_number') }}
                            </div>
                        @endif
                    </table>
                </div>
            </div>
        </section>

        <!-- ADD Device Modal -->
        <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDeviceModalLabel">Add Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('device.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="serial_number" class="form-label">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
                            </div>
                            <!-- Add Photo and Plat Nomor fields -->
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="plat_nomor" class="form-label">Plat Nomor</label>
                                <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Device</button>
                            @if ($errors->has('serial_number'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('serial_number') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Device Modal -->
        @foreach ($device as $item)
            <div class="modal fade" id="editDeviceModal{{ $item->id_device }}" tabindex="-1"
                aria-labelledby="editDeviceModalLabel{{ $item->id_device }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDeviceModalLabel{{ $item->id_device }}">Edit Device</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
                                    <input type="text" class="form-control"
                                        id="edit_serial_number{{ $item->id_device }}" name="serial_number"
                                        value="{{ $item->serial_number }}" required readonly>
                                </div>
                                <div class="mb-3">
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
                                    <label for="edit_plat_nomor{{ $item->id_device }}" class="form-label">Plat
                                        Nomor</label>
                                    <input type="text" class="form-control"
                                        id="edit_plat_nomor{{ $item->id_device }}" name="plat_nomor"
                                        value="{{ $item->plat_nomor }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update Device</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Delete Device Modals -->
        @foreach ($device as $item)
            <div class="modal fade" id="deleteDeviceModal{{ $item->id_device }}" tabindex="-1"
                aria-labelledby="deleteDeviceModalLabel{{ $item->id_device }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteDeviceModalLabel{{ $item->id_device }}">Delete Device</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('device.destroy', $item->id_device) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p>Are you sure you want to delete this device?</p>
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
        @foreach ($device as $item)
            <div class="modal fade" id="viewPhotoModal{{ $item->id_device }}" tabindex="-1"
                aria-labelledby="viewPhotoModalLabel{{ $item->id_device }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewPhotoModalLabel{{ $item->id_device }}">Picture -
                                {{ $item->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $('#table1').on('click', '[data-bs-toggle="modal"]', function() {
            var targetModalId = $(this).data('bs-target');
            $(targetModalId).modal('show');
        });

        function liveSearch() {
            const searchInput = document.getElementById('search');
            const searchTerm = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('table1').getElementsByTagName('tbody')[0];
            const deviceData = {!! json_encode($device) !!};

            // Filter devices based on the search term
            const filteredResults = deviceData.filter(device =>
                device.name.toLowerCase().includes(searchTerm) ||
                device.serial_number.toLowerCase().includes(searchTerm) ||
                device.plat_nomor.toLowerCase().includes(searchTerm) // Added condition for Plat Nomor
            );

            // Display search results
            if (filteredResults.length > 0) {
                tableBody.innerHTML = ''; // Clear existing table body

                filteredResults.forEach((device, index) => {
                    const resultItem = document.createElement('tr');
                    resultItem.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${device.name}</td>
                    <td>${device.serial_number}</td>
                    <td>${device.plat_nomor}</td>
                    <td>
                        ${device.photo
                            ? `<button type="button" class="btn btn-link view-photo-btn" data-bs-toggle="modal" data-bs-target="#viewPhotoModal${device.id_device}">
                                                                <img src="{{ asset('storage/') }}/${device.photo}" alt="Device Photo" style="max-width: 100px;">
                                                            </button>`
                            : 'No photo available'}
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDeviceModal${device.id_device}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDeviceModal${device.id_device}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                        <!-- Add this block to attach the event handler for View Photo button -->
                        <button type="button" class="btn btn-link view-photo-btn" data-bs-toggle="modal" data-bs-target="#viewPhotoModal${device.id_device}">
                        </button>
                    </td>
                `;

                    tableBody.appendChild(resultItem);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No devices found</td></tr>';
            }
        }

        function previewEditPhoto(input, deviceId) {
            var previewId = 'editPhotoPreview' + deviceId;
            var preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.setAttribute('data-new-photo-url', e.target.result);
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

        $('#editDeviceModal').on('shown.bs.modal', function(e) {
            var deviceId = $(e.relatedTarget).data('device-id');
            var previewId = 'editPhotoPreview' + deviceId;
            var preview = document.getElementById(previewId);

            $('#updatePhotoBtn').on('click', function() {
                var newPhotoUrl = preview.getAttribute('data-new-photo-url');

                if (newPhotoUrl) {
                    preview.src = newPhotoUrl;
                    $('#deletePhotoBtn').hide();
                }
            });

            $('#deletePhotoBtn').on('click', function() {
                deletePhoto(deviceId);
            });
        });
    </script>
@endsection
