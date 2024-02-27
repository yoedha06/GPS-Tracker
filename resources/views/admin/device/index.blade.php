@extends('layouts.admin')

@section('content')
    <div id="main">
        <div class="page-heading">
            <!-- ... (kode sebelumnya) ... -->
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

        <div class="mb-3">
            <label for="user_filter" class="form-label">Filter by User:</label>
            <select class="form-select" id="user_filter" name="user_filter">
                <option value="">Select Users</option>
                @foreach ($users as $user)
                    @if ($user->role === 'customer')
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn btn-primary mt-2" onclick="applyFilter()">
                <i class="fas fa-filter"></i> Lihat Semua Users
            </button>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Device User</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1" style="table-layout: auto">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Device</th>
                                <th>Serial Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($device as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->user)->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
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
        // Function to automatically apply filter when the user is selected
        function autoApplyFilter() {
            var userId = document.getElementById('user_filter').value;
            
            // Check if "All Users" is selected
            if (userId !== "") {
                window.location.href = '{{ route('admin.device.index') }}' + '?user=' + userId;
            } else {
                // If "All Users" is selected, trigger the filter immediately
                applyFilter();
            }
        }
    
        // Event listener for the dropdown change to trigger auto apply filter
        document.getElementById('user_filter').addEventListener('change', autoApplyFilter);
    
        // Function to apply the filter
        function applyFilter() {
            var userId = document.getElementById('user_filter').value;
            window.location.href = '{{ route('admin.device.index') }}' + '?user=' + userId;
        }
    
        // Event listener for "Select All Users" button
        document.getElementById('select_all_users').addEventListener('click', applyFilter);
        
    </script>
    
    
@endsection
