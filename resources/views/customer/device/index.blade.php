@extends('layouts.customer')

@section('content')
@livewireStyles
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
                        <h3>Device</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="search">Search</span>
                    <input type="text" class="form-control" placeholder="search name or serial number" aria-label="search name or serial number" wire:model="search">
                </div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Device User</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>

                @livewire('device-customer')

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
@livewireScripts
@endsection
