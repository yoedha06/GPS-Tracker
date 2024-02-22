@extends('layouts.customer')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Project with Leaflet</title>
    <!-- Menggunakan Leaflet dari CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* public/css/custom.css */
        body {
            margin: 0;
            padding: 0;
            display: flex; /* Add display:flex to create a flex container */
        }

        .sidebar {
            width: 20%; /* Set the width of the sidebar */
            background-color: #f1f1f1;
            padding: 30px;
        }

        .content-container {
            flex: 1; /* Set flex property to 1 to make it take the remaining space */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #map {
            height: 715px;
            width: 200%;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <!-- Add your sidebar content here -->
        <h2>Sidebar</h2>
        <p>This is a sidebar.</p>
    </div>

    <div class="content-container">
        <div id="map"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var mymap = L.map('map').setView([51.505, -0.09], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mymap);

            L.marker([51.5, -0.09]).addTo(mymap)
                .bindPopup('Hello, this is a Leaflet map!')
                .openPopup();
        });
    </script>
</body>

</html>
@endsection
