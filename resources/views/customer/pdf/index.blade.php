<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
     <div>
        <div>
            {{-- <div><img src="assets/img/rapot.png" alt=""
                    style="width: 100px; height: 130px; position:absolute; margin-left: 40px; margin-top: 20px"></div> --}}
            <div style="margin-left: 100px; text-align: center">
                <h4>PEMERINTAH DAERAH PROVINSI JAWA BARAT <br> DINAS PENDIDIKAN </h4>
                <h2 style="line-height: 0px">SMK NEGERI 11 BANDUNG</h2>
                <p style="line-height: 0px; font-size: 14px">Bisinis Manajemen - Teknologi Informasi dan Komunikasi </p>
                <p style="line-height: 0px">Jl Budhi Cilember (022) 6652442 Fax. (022) 6613508 Bandung 40175</p>
                <p style="line-height: 0px">NPSN: 20219175 NSS: 34.1.02.60.03.001</p>
                <p style="line-height: 0px">http://smkn11bdg.net &nbsp;&nbsp; E-mail:smkn11bdg@gmail.com</p>
            </div>
            <hr style="width: 700px; height: 3px; background-color: black; margin-top:1px; position:absolute">
            <hr style="width: 700px; background-color: black; position:absolute">
        </div>
        <br><br>
    </div>
    <h1>History Report</h1>
    <p>Date: {{ $selectedDate }}</p>
    <p>Device: {{ $selectedDevice ?? 'All Devices' }}</p>
    <p>Chart Type: {{ $selectedChart }}</p>

    <br>

    <h2>History Data</h2>
    <table>
        <thead>
            <tr>
                <th>Date Time</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historyData as $data)
                <tr>
                    <td>{{ $data->date_time }}</td>
                    <td>{{ $data->count ?? 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Chart Data</h2>
    <table>
        <thead>
            <tr>
                <th>Date Time</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chartData as $data)
                <tr>
                    <td>{{ $data['date_time'] }}</td>
                    <td>{{ $data['count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


