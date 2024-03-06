@extends('layouts.admin')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    #map {
        height: 90%;
    }
</style>

@section('content')
    <header>
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div id="main">
        <div class="form-group mb-3">
            <label class="form-label">Pilih User Dan Device</label>
            <form method="post" action="{{ route('locations.filter') }}">
                @csrf
                <select name="user_device" id="user_device" class="form-select input" onchange="this.form.submit()">
                    <option value="" disabled selected>Pilih</option>
                    @foreach ($users as $user)
                        @if ($user->role == 'customer')
                            @foreach ($devices as $device)
                                <option value="user_{{ $user->id }}_device_{{ $device->id }}">
                                    {{ $user->name }} - {{ $device->name }}
                                </option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </form>
        </div>

        <div id="map"></div>

        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- Include Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
            integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                // Initialize Select2
                $('#user_device').select2();
        
                var map = L.map('map').setView([0, 0], 2);
        
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
        
                // Function to update the map with filtered data
                function updateMap(filter) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("locations.filter") }}',
                        data: { filter: filter, _token: '{{ csrf_token() }}' },
                        dataType: 'json',
                        success: function (data) {
                            // Clear existing markers
                            map.eachLayer(function (layer) {
                                if (layer instanceof L.Marker) {
                                    map.removeLayer(layer);
                                }
                            });
        
                            // Add new markers based on filtered data
                            data.forEach(function (history) {
                                var marker = L.marker([history.latitude, history.longitude]).addTo(map);
                                var popupContent = "<center><b style='margin-top: 5px;'>Device:</b> " + history.device.name + "</center>" +
                                    "<b>Name cust:</b> " + history.user.name + "<br>" +
                                    "<b>Latlng:</b> " + history.latitude + ',' + history.longitude + "<br>" +
                                    "<b>PlatNo:</b> " + history.device.plat_nomor + "<br>" +
                                    "<b>Date:</b> " + history.date_time + "<br>" +
                                    "<img src='{{ asset('storage/') }}/" + history.device.photo + "' style='width: 199px; height: 115px;' >";
        
                                marker.bindPopup(popupContent);
                            });
        
                            // Update map bounds
                            var bounds = L.latLngBounds(data.map(function (history) {
                                return [history.latitude, history.longitude];
                            }));
                            map.fitBounds(bounds);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
        
                // Event listener for select change
                $('#user_device').on('select2:select', function (e) {
                    var filter = e.params.data.id;
                    updateMap(filter);
                });
        
                // Initial map setup
                @foreach ($devices as $device)
                    @if (
                        $device->latestHistory &&
                            $device->latestHistory->latitude !== null &&
                            $device->latestHistory->longitude !== null &&
                            $device->user)
                        var marker = L.marker([{{ $device->latestHistory->latitude }}, {{ $device->latestHistory->longitude }}])
                            .addTo(map);
                        var popupContent = "<center><b style='margin-top: 5px;'>Device:</b> {{ $device->name }}</center>" +
                            "<b>Name cust:</b> {{ $device->user->name }}<br>" +
                            "<b>Latlng:</b> {{ $device->latestHistory->latitude . ',' . $device->latestHistory->longitude }}<br>" +
                            "<b>PlatNo:</b> {{ $device->plat_nomor }}<br>" +
                            "<b>Date:</b> {{ $device->latestHistory->date_time }}<br>" +
                            "<img src='{{ asset('storage/' . $device->photo) }}' style='width: 199px; height: 115px;' >";
        
                        marker.bindPopup(popupContent);
                    @endif
                @endforeach
        
                var bounds = L.latLngBounds([
                    @foreach ($devices as $device)
                        @if ($device->latestHistory)
                            [{{ $device->latestHistory->latitude }}, {{ $device->latestHistory->longitude }}],
                        @endif
                    @endforeach
                ]);
                map.fitBounds(bounds);
            });
        </script>
        
    </div>
@endsection
