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
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Serial Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($device as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
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
                                    <td colspan="4" class="text-center">No devices found</td>
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

        <!-- ADD Device Modalss -->
        <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-labelledby="addDeviceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDeviceModalLabel">Add Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('device.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="serial_number" class="form-label">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
                                @if ($errors->has('serial_number'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('serial_number') }}
                                    </div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Add Device</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Device Modals -->
        @foreach ($device as $item)
            <div class="modal fade" id="editDeviceModal{{ $item->id_device }}" tabindex="-1"
                aria-labelledby="editDeviceModalLabel{{ $item->id_device }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDeviceModalLabel{{ $item->id_device }}">Edit Device</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('device.update', $item->id_device) }}" method="POST">
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
        function liveSearch() {
            const searchInput = document.getElementById('search');
            const searchTerm = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('table1').getElementsByTagName('tbody')[0];
            const deviceData = {!! json_encode($device) !!}; // Convert PHP array to JavaScript

            // Filter devices based on the search term
            const filteredResults = deviceData.filter(device => device.name.toLowerCase().includes(searchTerm) || device
                .serial_number.toLowerCase().includes(searchTerm));

            // Display search results
            if (filteredResults.length > 0) {
                tableBody.innerHTML = ''; // Clear existing table body

                filteredResults.forEach((device, index) => {
                    const resultItem = document.createElement('tr');
                    resultItem.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${device.name}</td>
                        <td>${device.serial_number}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editDeviceModal${device.id_device}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteDeviceModal${device.id_device}">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(resultItem);
                });

            } else {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No devices found</td></tr>';
            }
        }
        
    </script>
@endsection
