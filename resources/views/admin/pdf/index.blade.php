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
    <h1>History Report</h1>
    <p>Date: {{ $selectedDate }}</p>
    <p>{{ $selectedDevice->user->name ?? 'Unknown User' }} - {{ $selectedDevice->name ?? 'Unknown Device' }}</p>
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
