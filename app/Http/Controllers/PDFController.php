<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\History;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    public function pdfcustomer(Request $request)
    {
        // Ambil data input dari request
        $selectedDate = $request->input('selected_date');
        $selectedDevice = $request->input('selected_device');
        $selectedChart = $request->input('selected_chart');

        // Dapatkan pengguna yang saat ini masuk
        $user = Auth::user();

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice, $user) {
                $query->where('name', $selectedDevice)->where('user_id', $user->id);
            });
        } else {
            // Jika tidak ada perangkat yang dipilih, tambahkan kondisi where untuk hanya menampilkan riwayat dari perangkat milik pengguna saat ini
            $query->whereHas('device', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Ambil data history sesuai dengan tanggal yang dipilih
        $historyData = $query->get();

        // Jika tidak ada data, tampilkan pesan kosong di PDF
        if ($historyData->isEmpty()) {
            $pdf = PDF::loadView('customer.pdf.index', [
                'historyData' => [],
                'chartData' => [],
                'selectedDate' => $selectedDate,
                'selectedDevice' => $selectedDevice,
                'selectedChart' => $selectedChart
            ]);

            return $pdf->stream('history.pdf');
        }

        // Persiapkan data untuk ditampilkan di chart berdasarkan pilihan chart yang dipilih
        $chartData = [];

        // Sesuaikan kueri untuk mengambil data berdasarkan jenis chart yang dipilih
        if ($selectedChart === 'speed') {
            $historyQuery = History::query()
                ->select('date_time', 'speeds as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'accuracy') {
            $historyQuery = History::query()
                ->select('date_time', 'accuracy as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'heading') {
            $historyQuery = History::query()
                ->select('date_time', 'heading as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'altitude_accuracy') {
            $historyQuery = History::query()
                ->select('date_time', 'altitude_acuracy as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'latitude') {
            $historyQuery = History::query()
                ->select('date_time', 'latitude as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'longitude') {
            $historyQuery = History::query()
                ->select('date_time', 'longitude as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } else {
            foreach ($historyData as $data) {
                $dateTime = is_string($data->date_time) ? new \DateTime($data->date_time) : $data->date_time;
                $date = $dateTime->format('Y-m-d H:i:s');
                $value = 1;

                if (isset($chartData[$date])) {
                    $chartData[$date]['count'] += $value;
                } else {
                    $chartData[$date] = [
                        'date_time' => $date,
                        'count' => $value
                    ];
                }
            }
        }

        // Ambil daftar perangkat yang tersedia untuk tanggal yang dipilih
        $deviceOptions = Device::whereExists(function ($query) use ($selectedDate) {
            $query->select(DB::raw(1))
                ->from('history')
                ->whereColumn('id_device', 'history.device_id')
                ->whereDate('history.date_time', $selectedDate);
        })
            ->where('user_id', $user->id)
            ->whereNotNull('name')
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();

        // Membuat PDF dengan data history
        $pdf = PDF::loadView('customer.pdf.index', compact('historyData', 'chartData', 'selectedDate', 'selectedDevice', 'selectedChart'));

        // Mengirimkan PDF ke browser
        return $pdf->stream('history.pdf');
    }


    public function pdfadmin(Request $request)
    {
        // Ambil data input dari request
        $selectedDate = $request->input('selected_date');
        $selectedDevice = $request->input('selected_device');
        $selectedChart = $request->input('selected_chart');

        // Dapatkan pengguna yang saat ini masuk
        $user = Auth::user();

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice, $user) {
                $query->where('name', $selectedDevice)->where('user_id', $user->id);
            });
        } else {
            // Jika tidak ada perangkat yang dipilih, tambahkan kondisi where untuk hanya menampilkan riwayat dari perangkat milik pengguna saat ini
            $query->whereHas('device', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Ambil data history sesuai dengan tanggal yang dipilih
        $historyData = $query->get();

        // Jika tidak ada data, tampilkan pesan kosong di PDF
        if ($historyData->isEmpty()) {
            $pdf = PDF::loadView('admin.pdf.index', [
                'historyData' => [],
                'chartData' => [],
                'selectedDate' => $selectedDate,
                'selectedDevice' => $selectedDevice,
                'selectedChart' => $selectedChart
            ]);

            return $pdf->stream('history.pdf');
        }

        // Persiapkan data untuk ditampilkan di chart berdasarkan pilihan chart yang dipilih
        $chartData = [];

        // Sesuaikan kueri untuk mengambil data berdasarkan jenis chart yang dipilih
        if ($selectedChart === 'speed') {
            $historyQuery = History::query()
                ->select('date_time', 'speeds as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'accuracy') {
            $historyQuery = History::query()
                ->select('date_time', 'accuracy as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'heading') {
            $historyQuery = History::query()
                ->select('date_time', 'heading as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'altitude_accuracy') {
            $historyQuery = History::query()
                ->select('date_time', 'altitude_accuracy as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'latitude') {
            $historyQuery = History::query()
                ->select('date_time', 'latitude as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'longitude') {
            $historyQuery = History::query()
                ->select('date_time', 'longitude as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } else {
            foreach ($historyData as $data) {
                $dateTime = is_string($data->date_time) ? new \DateTime($data->date_time) : $data->date_time;
                $date = $dateTime->format('Y-m-d H:i:s');
                $value = 1;

                if (isset($chartData[$date])) {
                    $chartData[$date]['count'] += $value;
                } else {
                    $chartData[$date] = [
                        'date_time' => $date,
                        'count' => $value
                    ];
                }
            }
        }

        // Ambil daftar perangkat yang tersedia untuk tanggal yang dipilih
        $deviceOptions = Device::whereExists(function ($query) use ($selectedDate) {
            $query->select(DB::raw(1))
                ->from('history')
                ->whereColumn('id_device', 'history.device_id')
                ->whereDate('history.date_time', $selectedDate);
        })
            ->where('user_id', $user->id)
            ->whereNotNull('name')
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();

        // Membuat PDF dengan data history
        $pdf = PDF::loadView('admin.pdf.index', compact('historyData', 'chartData', 'selectedDate', 'selectedDevice', 'selectedChart'));

        // Mengirimkan PDF ke browser
        return $pdf->stream('history.pdf');
    }
}
