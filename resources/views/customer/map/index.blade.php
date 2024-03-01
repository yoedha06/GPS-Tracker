@extends('layouts.customer')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@section('content')

<div id="main">
        <div id= "map"
            style= "height: 50%;
                    width: 100%;">
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function()
        {
            var mymap = L.map('map');

            mymap.locate({
                setView: true,
                maxZoom: 13
            });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(mymap);

                mymap.on('locationfound', function(e)
                {
                    var marker = L.marker(e.latlng).addTo(mymap);
                    marker.bindPopup('Aku Disini!').openPopup();
                });

                mymap.on('locationerror', function(e)
                {
                    console.log(e.message);
                });
        });
</script>
@endsection
