@extends('layouts.app')

@section('title', 'Progress Kegiatan BBWS Serayu Opak')

@section('content')


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard BBWS Serayu Opak</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .print-full-width {
                width: 100% !important;
            }
        }
        .dashboard-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            height: 8px;
            border-radius: 4px;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8fafc;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .bg-success-light {
            background-color: #dcfce7;
            color: #166534;
        }
        .bg-warning-light {
            background-color: #fef3c7;
            color: #92400e;
        }
        .bg-danger-light {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-water text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">BBWS SERAYU OPAK</h1>
                        <p class="text-gray-600">Balai Besar Wilayah Sungai Serayu Opak</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Info -->
        <div class="dashboard-card p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">PROGRES KEGIATAN BBWS SERAYU OPAK TA.2025</h2>
                    <p class="text-gray-600"><i class="fas fa-database mr-2"></i>Sumber : ie-Monitoring</p>
                </div>
                <div>
                    <p class="text-gray-600"><i class="fas fa-calendar mr-2"></i>Data : 26 September 2025</p>
                    <p class="text-gray-600"><i class="fas fa-clock mr-2"></i>Pukul 16.00 WIB</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-blue-800 font-semibold"><i class="fas fa-trophy mr-2"></i>Peringkat Ke 9 dari 15 BBWS Seluruh Indonesia</p>
                </div>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="dashboard-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Keuangan</h3>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Progress terhadap PAGU</span>
                    <span class="font-bold text-blue-600">41.09%</span>
                </div>
                <div class="progress-bar mb-4" style="width: 41.09%"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Setelah Efisiensi</span>
                    <span class="font-bold text-green-600">46.95%</span>
                </div>
                <div class="progress-bar" style="width: 46.95%; background: linear-gradient(90deg, #10b981, #059669)"></div>
            </div>

            <div class="dashboard-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Fisik</h3>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Progress terhadap PAGU</span>
                    <span class="font-bold text-blue-600">42.50%</span>
                </div>
                <div class="progress-bar mb-4" style="width: 42.50%"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Setelah Efisiensi</span>
                    <span class="font-bold text-green-600">48.56%</span>
                </div>
                <div class="progress-bar" style="width: 48.56%; background: linear-gradient(90deg, #10b981, #059669)"></div>
            </div>

            <div class="dashboard-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Anggaran</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total PAGU</span>
                        <span class="font-bold">Rp 1.363.380.406</span>
                    </div>
                    <div class="flex justify-between text-red-600">
                        <span>Diblokir</span>
                        <span class="font-bold">Rp 170.185.161 (12.48%)</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Tidak Diblokir</span>
                        <span class="font-bold">Rp 1.193.195.245 (87.52%)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
            <!-- Wilayah DIY -->
            <div class="dashboard-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">WILAYAH DIY</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-striped">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kabupaten</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Keu</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Fisik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 text-sm">1</td>
                                <td class="px-4 py-2 text-sm">Gunungkidul</td>
                                <td class="px-4 py-2 text-sm">1</td>
                                <td class="px-4 py-2 text-sm">1</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm">2</td>
                                <td class="px-4 py-2 text-sm">Bantul</td>
                                <td class="px-4 py-2 text-sm">1</td>
                                <td class="px-4 py-2 text-sm">1</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm">3</td>
                                <td class="px-4 py-2 text-sm">Kulon Progo</td>
                                <td class="px-4 py-2 text-sm">1</td>
                                <td class="px-4 py-2 text-sm">3</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-blue-50">
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-sm font-semibold">Jumlah</td>
                                <td class="px-4 py-2 text-sm font-semibold">100</td>
                                <td class="px-4 py-2 text-sm font-semibold">67.26</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Wilayah Jawa Tengah -->
            <div class="dashboard-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">WILAYAH JAWA TENGAH</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-striped">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kabupaten</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Total Lokasi</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Keu</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Fisik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $jawaTengah = [
                                    ['Wonosobo', 42],
                                    ['Banjarnegara', 13],
                                    ['Purbalingga', 19],
                                    ['Cilacap', 19],
                                    ['Banyumas', 116],
                                    ['Kebumen', 14],
                                    ['Purworejo', 9],
                                    ['Magelang', 46],
                                    ['Temanggung', 46]
                                ];
                            @endphp
                            @foreach($jawaTengah as $index => $item)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-sm">{{ $item[0] }}</td>
                                <td class="px-4 py-2 text-sm">{{ $item[1] }}</td>
                                <td class="px-4 py-2 text-sm">-</td>
                                <td class="px-4 py-2 text-sm">-</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-blue-50">
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-sm font-semibold">Jumlah</td>
                                <td class="px-4 py-2 text-sm font-semibold">324</td>
                                <td class="px-4 py-2 text-sm font-semibold">99.07</td>
                                <td class="px-4 py-2 text-sm font-semibold">64.81</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Progress per Satker -->
        <div class="dashboard-card p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Per Satuan Kerja</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-striped">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Satuan Kerja</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">PAGU (Rp.000)</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Blokir (Rp.000)</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Keu</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress Fisik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $satker = [
                                ['Satker OP', 260320529, 5427047, 63.70, 58.81],
                                ['SNVT PJSA', 352853058, 284030, 34.66, 38.78],
                                ['SNVT PJPA', 474924921, 52649994, 31.56, 31.99],
                                ['Satker Balai', 74865722, 29060515, 40.69, 40.81],
                                ['SNVT Bendungan', 200416176, 82763575, 45.77, 53.41]
                            ];
                        @endphp
                        @foreach($satker as $index => $item)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-sm font-medium">{{ $item[0] }}</td>
                            <td class="px-4 py-2 text-sm">{{ number_format($item[1], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-sm">{{ number_format($item[2], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center">
                                    <span class="mr-2">{{ $item[3] }}%</span>
                                    <div class="progress-bar" style="width: {{ $item[3] }}%"></div>
                                </div>
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center">
                                    <span class="mr-2">{{ $item[4] }}%</span>
                                    <div class="progress-bar" style="width: {{ $item[4] }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="dashboard-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Keterangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-700 mb-2"><span class="font-semibold">*</span> Pagu blokir</p>
                    <p class="text-gray-700"><span class="font-semibold">**</span> Masih terkendala ijin MYC dan penunjukan langsung</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 mb-2">Kegiatan P3-TGAI</h4>
                    <p class="text-yellow-700">Total 327 Lokasi di BBWS Serayu Opak TA.2025</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="text-center text-gray-600">
                <p>&copy; 2024 BBWS Serayu Opak. All rights reserved.</p>
                <p class="text-sm mt-1">Sistem Monitoring Terintegrasi</p>
            </div>
        </div>
    </footer>

    <script>
        // Add any interactive functionality here
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard BBWS Serayu Opak loaded');
        });
    </script>
</body>
</html>
