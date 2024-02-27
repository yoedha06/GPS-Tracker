<div class="card-body">
    <table class="table table-striped" id="table1" style="table-layout: auto">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Serial Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($device as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->serial_number }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editDeviceModal{{ $item->id_device }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteDeviceModal{{ $item->id_device }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>  
    <div class="card-body">
        {{ $device->links() }}
    </div>
</div>
