@extends('layouts.customer')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@section('content')
<div id="main">
    <div class="form-group ml-3">
        <label for="device-select">Select Device:</label>
        <select id="device-select" class="form-control" style="width: 150px;">
            <option value="" disabled selected>Select Device</option>
            @foreach($devices as $device)
                <option value="{{ $device->id_device }}">{{ $device->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="date-time-input ml-auto">
        <label for="start-date" class="date-label">
            Start Date:
            <div class="input-group">
                <input type="date" id="start-date" class="form-control">
                <div class="input-group-append">
                </div>
            </div>
        </label>
        <label for="end-date" class="date-label">
            End Date:
            <div class="input-group">
                <input type="date" id="end-date" class="form-control">
                <div class="input-group-append">
                </div>
            </div>
        </label>
    </div>
    <div id="map" style="height: 50%; width: 100%;"></div>
</div>
@endsection

<style>
    .date-time-input {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }
    .date-label {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }
    .date-label input[type="date"] {
        padding-right: 30px;
    }
    .date-label i.fas.fa-calendar {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        pointer-events: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var mymap = L.map('map');

        mymap.locate({
            setView: true,
            maxZoom: 13
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mymap);

        mymap.on('locationfound', function (e) {
            var marker = L.marker(e.latlng).addTo(mymap);
            marker.bindPopup('Anda Disini!').openPopup();
        });

        mymap.on('locationerror', function (e) {
            console.log(e.message);
        });
    });
</script>
<script>
    $(document).ready(function(){
    $("#device-select").select2({
        placeholder: "Select Device",
        ajax: {
            url: "{{ route('map.selectDevice') }}", // Perbaiki nama route sesuai dengan yang Anda tentukan di dalam route
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});


</script>
