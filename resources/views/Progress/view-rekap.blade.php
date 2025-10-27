<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Progress - {{ $reportDate }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .report-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .report-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .report-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .logo {
            position: absolute;
            left: 25px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 32px;
        }

        .section-title {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 20px;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            border-left: 5px solid #e74c3c;
        }

        .table-custom {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }

        .table-custom thead th {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            border: none;
            font-weight: 600;
            padding: 12px 8px;
            font-size: 12px;
        }

        .table-custom tbody td {
            padding: 10px 8px;
            font-size: 11px;
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9fa;
        }

        .progress-bar-container {
            background: #ecf0f1;
            border-radius: 8px;
            width: 100px;
            height: 25px;
            overflow: hidden;
            /* position: relative; */
            /* display: inline-block; */
            margin: 0 auto;
        }

        /* Warna progress keuangan */
        .progress-bar.bg-success {
            background-color: #e7dc46ff !important;
            width: 100px;
            height: 25px;
        }

        /* Warna progress fisik */
        .progress-bar.bg-info {
            background-color: #7899ecff !important;
        }

        .progress-bar {
            width: 100px;
            height: 25px;
            border-radius: 10px;
            transition: width 0.3s ease;
            position: relative;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            font-weight: bold;
            color: #2c3e50;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }

        .number-badge {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .action-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .btn-print {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6);
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #3498db;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }

            .report-container {
                box-shadow: none !important;
                margin: 0 !important;
            }

            .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header -->
                <div class="report-container">
                    <div class="report-header">
                        <div class="logo">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h1>PROGRES KEGIATAN BBWS SERAYU OPAK TA.2025</h1>
                        <div class="subtitle">
                            <i class="fas fa-database"></i> Sumber : Ie-Monitoring |
                            <i class="fas fa-calendar"></i> Data :
                            {{ \Carbon\Carbon::parse($reportDate)->format('d F Y') }} Pukul 16.00 WIB
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <!-- <div class="row p-3">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $ppk->count() }}</div>
                                <div class="stat-label">Satuan Kerja</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $p3tgai->count() }}</div>
                                <div class="stat-label">Lokasi P3TGAI</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $inpres->count() }}</div>
                                <div class="stat-label">Paket INPRES</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-number">{{ $tenders->count() }}</div>
                                <div class="stat-label">Tender</div>
                            </div>
                        </div>
                    </div> -->

                    <!-- === Progress & Pagu Balai Section === -->
                    <div class="content-card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="fas fa-chart-line me-2"></i>Progress Balai & Pagu Total</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2">PAGU</th>
                                            <th colspan="2">PROGRES BALAI TERHADAP PAGU TOTAL (%)</th>
                                            <th colspan="2">PROGRES BALAI TERHADAP PAGU EFISIENSI (%)</th>
                                            <th colspan="2">PROGRES SDA TERHADAP PAGU TOTAL (%)</th>
                                        </tr>
                                        <tr>
                                            <th>Keu</th>
                                            <th>Fis</th>
                                            <th>Keu</th>
                                            <th>Fis</th>
                                            <th>Keu</th>
                                            <th>Fis</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <!-- <td><strong>Rp {{ number_format($total_pagu ?? 0, 0, ',', '.') }}</strong></td> -->

                                            <!-- Progress Balai Terhadap Pagu Total -->
                                            <!-- <td>{{ number_format($total_progress_keu_pagu_total ?? 0, 2, ',', '.') }}%</td>
                                            <td>{{ number_format($total_progress_fis_pagu_total ?? 0, 2, ',', '.') }}%</td> -->

                                            <!-- Progress Balai Terhadap Pagu Efisiensi -->
                                            <!-- <td>{{ number_format($total_progress_keu_pagu_efisiensi ?? 0, 2, ',', '.') }}%</td>
                                            <td>{{ number_format($total_progress_fis_pagu_efisiensi ?? 0, 2, ',', '.') }}%</td> -->

                                            <!-- Progress SDA terhadap Pagu Total (Manual Input) -->
                                            <!-- <td>{{ number_format($manualInput->sda_prog_keu_total ?? 0, 2, ',', '.') }}%</td>
                                            <td>{{ number_format($manualInput->sda_prog_fis_total ?? 0, 2, ',', '.') }}%</td></div>-->
                                            <td>{{ number_format($summary['total_pagu'], 0, ',', '.') }}</td>
                                            <td>{{ number_format($summary['total_progress_keu_pagu_total'], 2, ',', '.') }}%</td>
                                            <td>{{ number_format($summary['total_progress_fis_pagu_total'], 2, ',', '.') }}%</td>
                                            <td>{{ number_format($summary['total_progress_keu_pagu_efisiensi'], 2, ',', '.') }}%</td>
                                            <td>{{ number_format($summary['total_progress_fis_pagu_efisiensi'], 2, ',', '.') }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>
                                        Nilai persentase dihitung berdasarkan perbandingan antara realisasi terhadap total pagu dan pagu efisiensi.
                                        Kolom “SDA terhadap Pagu Total” diambil dari input manual dashboard.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- === Rincian Pagu: Sumber Dana, Blokir, Alokasi ===
                    <div class="row mt-4">
                        <!-- Sumber Dana -->
                    <!-- <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <strong><i class="fas fa-wallet me-2"></i>Sumber Dana</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Sumber Dana</th>
                                                <th>Nilai (Rp)</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sumber_dana as $s)
                                            <tr>
                                                <td>{{ $s->nama_sumber ?? '-' }}</td>
                                                <td>Rp {{ number_format($s->nilai ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($s->persentase ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                    <!-- </div> -->

                    <!-- Status Blokir -->
                    <!-- <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <strong><i class="fas fa-ban me-2"></i>Status Blokir Anggaran</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Status</th>
                                                <th>Nilai (Rp)</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($status_blokir as $b)
                                            <tr>
                                                <td>{{ $b->status ?? '-' }}</td>
                                                <td>Rp {{ number_format($b->jumlah ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($b->persentase ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->

                    <!-- Alokasi Anggaran Per Satker
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <strong><i class="fas fa-building me-2"></i>Alokasi Anggaran per Satker</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Satker</th>
                                                <th>Nilai (Rp)</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($alokasiSatker as $a)
                                            <tr>
                                                <td>{{ $a->satker->singkatan ?? '-' }}</td>
                                                <td>Rp {{ number_format($a->nilai ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($a->persentase ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> -->


                    <!-- Progress per PPK -->
                    <h4 class="section-title">
                        <i class="fas fa-building me-2"></i>Progress Keuangan per PPK Berdasarkan PAGU Efektif
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Satuan Kerja</th>
                                    <th>PAGU (Rp.000)</th>
                                    <th>Blokir (Rp.000)</th>
                                    <th>Pagu Setelah Efisiensi (Rp.000)</th>
                                    <th>Progres Terhadap PAGU Total(Prog.Keu) </th>
                                    <th>Progres Terhadap PAGU Total(Prog.Fis)</th>
                                    <th>Progres Terhadap PAGU Setelah Efisiensi(Prog.Keu)</th>
                                    <th>Progres Terhadap PAGU Setelah Efisiensi(Prog.Fis)</th>
                                    <th>Prognosis (Rp)</th>
                                    <th>Prognosis (%)</th>
                                </tr>
                            </thead>
                            <!-- @php
                            $total_pagu = $total_blokir = $total_pagu_efisiensi = $total_prognosis_rp = 0;
                            $total_realisasi_keu = $total_realisasi_fis = 0;

                            foreach ($ppk as $progress) {
                                $pagu = is_numeric($progress->pagu) ? $progress->pagu : 0;
                                $pagu_efisiensi = is_numeric($progress->pagu_efisiensi) ? $progress->pagu_efisiensi : 0;
                                $blokir = is_numeric($progress->blokir) ? $progress->blokir : 0;
                                $prognosis_rp = is_numeric($progress->prognosis_rp) ? $progress->prognosis_rp : 0;

                                // progress (%)
                                $progress_keu_pagu_efisiensi = is_numeric($progress->progress_keu_pagu_efisiensi) ? $progress->progress_keu_pagu_efisiensi : 0;
                                $progress_fis_pagu_total = is_numeric($progress->progress_fis_pagu_total) ? $progress->progress_fis_pagu_total : 0;

                                // A = realisasi keuangan (Rp)
                                $realisasi_keu = $pagu_efisiensi * $progress_keu_pagu_efisiensi / 100;
                                // B = realisasi fisik (Rp)
                                $realisasi_fis = $pagu * $progress_fis_pagu_total / 100;

                                // total kumulatif
                                $total_pagu += $pagu;
                                $total_pagu_efisiensi += $pagu_efisiensi;
                                $total_blokir += $blokir;
                                $total_prognosis_rp += $prognosis_rp;
                                $total_realisasi_keu += $realisasi_keu;
                                $total_realisasi_fis += $realisasi_fis;
                            }
                            $total_progress_keu_pagu_total = $total_pagu > 0 ? ($total_realisasi_keu / $total_pagu) * 100 : 0;
                            $total_progress_fis_pagu_total = $total_pagu > 0 ? ($total_realisasi_fis / $total_pagu) * 100 : 0;

                            $total_progress_keu_pagu_efisiensi = $total_pagu_efisiensi > 0 ? ($total_realisasi_keu / $total_pagu_efisiensi) * 100 : 0;
                            $total_progress_fis_pagu_efisiensi = $total_pagu_efisiensi > 0 ? ($total_realisasi_fis / $total_pagu_efisiensi) * 100 : 0;

                            $total_prognosis_persen = $total_pagu_efisiensi > 0 ? ($total_prognosis_rp / $total_pagu_efisiensi) * 100 : 0;
                            @endphp -->

                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="2">TOTAL</td>
                                    <!-- <td>{{ number_format($total_pagu, 0, ',', '.') }}</td>
                                    <td>{{ number_format($total_blokir, 0, ',', '.') }}</td>
                                    <td>{{ number_format($total_pagu_efisiensi, 0, ',', '.') }}</td>
                                    <td>{{ number_format($total_progress_keu_pagu_total, 2, ',', '.') }}%</td>
                                    <td>{{ number_format($total_progress_fis_pagu_total, 2, ',', '.') }}%</td>
                                    <td>{{ number_format($total_progress_keu_pagu_efisiensi, 2, ',', '.') }}%</td>
                                    <td>{{ number_format($total_progress_fis_pagu_efisiensi, 2, ',', '.') }}%</td>
                                    <td>{{ number_format($total_prognosis_rp, 0, ',', '.') }}</td>
                                    <td>{{ number_format($total_prognosis_persen, 2, ',', '.') }}%</td> -->

                                    <td>{{ number_format($summary['total_pagu'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($total_blokir, 0, ',', '.') }}</td>
                                    <td>{{ number_format($summary['total_pagu_efisiensi'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($summary['total_progress_keu_pagu_total'], 2, ',', '.') }}%</td>
                                    <td>{{ number_format($summary['total_progress_fis_pagu_total'], 2, ',', '.') }}%</td>
                                    <td>{{ number_format($summary['total_progress_keu_pagu_efisiensi'], 2, ',', '.') }}%</td>
                                    <td>{{ number_format($summary['total_progress_fis_pagu_efisiensi'], 2, ',', '.') }}%</td>
                                    <td>{{ number_format($summary['total_prognosis_rp'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($summary['total_prognosis_persen'], 2, ',', '.') }}%</td>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($ppk as $index => $progress)
                                <tr>
                                    <td>
                                        <div class="number-badge">{{ $index + 1 }}</div>
                                    </td>
                                    <td class="fw fs-6">{{ $progress->nama_ppk }}</td>
                                    <td class="fw fs-6">{{ number_format($progress->pagu, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ number_format($progress->blokir, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ number_format($progress->pagu_efisiensi, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ $progress->progress_keu_pagu_total }}%</td>
                                    <td class="fw fs-6">{{ $progress->progress_fis_pagu_total }}%</td>
                                    <td class="fw fs-6">{{ $progress->progress_keu_pagu_efisiensi }}%</td>
                                    <td class="fw fs-6">{{ $progress->progress_fis_pagu_efisiensi }}%</td>
                                    <td class="fw fs-6">{{ number_format($progress->prognosis_rp, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ $progress->prognosis_persen }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- P3TGAI -->
                    <h4 class="section-title mt-4">
                        <i class="fas fa-map-marker-alt me-2"></i>Kegiatan P3-TGAI di BBWS Serayu Opak TA.2025
                    </h4>
                    <div class="row p-3">

                        @php
                        $p3tgaiDIY = $p3tgai->where('wilayah', 'DIY');
                        $p3tgaiJateng = $p3tgai->where('wilayah', 'Jawa Tengah');

                        // hitung total DIY
                        $totalLokasiDIY = $p3tgaiDIY->sum('jumlah_lokasi');
                        $totalLokasiJateng = $p3tgaiJateng->sum('jumlah_lokasi');
                        @endphp

                        <!-- DIY -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <strong><i class="fas fa-map-pin me-2"></i>WILAYAH DIY</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kabupaten</th>
                                                <th>Total Lokasi</th>
                                                <th>Prog Keu</th>
                                                <th>Prog Fis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p3tgaiDIY as $p3)
                                            <tr>
                                                <td class="fw-bold">{{ $p3->kabupaten }}</td>
                                                <td class="fw fs-6">{{ $p3->jumlah_lokasi }}</td>
                                                <td class="fw fs-6">{{ $p3->prog_keu }}%</td>
                                                <td class="fw fs-6">{{ $p3->prog_fis }}%</td>
                                            </tr>
                                            @endforeach
                                            <tr class="fw-bold bg-light">
                                                <td class="text-start ps-3">TOTAL</td>
                                                <td>{{ $totalLokasiDIY }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Jawa Tengah -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <strong><i class="fas fa-map-pin me-2"></i>WILAYAH JAWA TENGAH</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Kabupaten</th>
                                                <th>Total Lokasi</th>
                                                <th>Prog Keu</th>
                                                <th>Prog Fis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p3tgaiJateng as $p3)
                                            <tr>
                                                <td class="fw-bold">{{ $p3->kabupaten }}</td>
                                                <td class="fw fs-6">{{ $p3->jumlah_lokasi }}</td>
                                                <td class="fw fs-6">{{ $p3->prog_keu }}%</td>
                                                <td class="fw fs-6">{{ $p3->prog_fis }}%</td>
                                            </tr>
                                            @endforeach
                                            <tr class="fw-bold bg-light">
                                                <td class="text-start ps-3">TOTAL</td>
                                                <td>{{ $totalLokasiJateng }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INPRES -->
                    @if($inpres->count() > 0)
                    <h4 class="section-title mt-4">
                        <i class="fas fa-tasks me-2"></i>Progres Pelaksanaan INPRES No. 2 Tahun 2025
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Satuan kerja</th>
                                    <th>Jumlah Paket Tahap 2</th>
                                    <th>Pagu Tahap 2 (Rp)</th>
                                    <th>Progres Keu Tahap 2 (%)</th>
                                    <th>Progres Fis Tahap 2 (%)</th>
                                    <th>Tahap 2</th>
                                    <th>Jumlah Paket Tahap 3</th>
                                    <th>Pagu Tahap 3 (Rp)</th>
                                    <th>Progres Keu Tahap 3 (%)</th>
                                    <th>Progres Fis Tahap 3 (%)</th>
                                    <th>Tahap 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inpres as $item)
                                <tr>
                                    <td class="fw fs-6">{{ $item->satker }}</td>
                                    <td class="fw fs-6">{{ $item->jumlah_paket_2 }}</td>
                                    <td class="fw fs-6">
                                        {{ is_numeric($item->pagu_2) ? number_format($item->pagu_2, 0, ',', '.') : $item->pagu_2 }}
                                    <td class="fw fs-6">{{ $item->prog_keu_2 }}</td>
                                    <td class="fw fs-6">{{ $item->prog_fis_2 }}</td>
                                    <td class="fw fs-6">{{ $item->tahap_2 }}</td>

                                    <td class="fw fs-6">{{ $item->jumlah_paket_3 }}</td>
                                    <td class="fw fs-6">
                                        {{ is_numeric($item->pagu_3) ? number_format($item->pagu_3, 0, ',', '.') : $item->pagu_3 }}
                                    </td>
                                    <td class="fw fs-6">{{ $item->prog_keu_3 }}</td>
                                    <td class="fw fs-6">{{ $item->prog_fis_3 }}</td>
                                    <td class="fw fs-6">{{ $item->tahap_3 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @php
                            // Fungsi untuk bersihkan angka dari Excel (titik, koma, *, spasi)
                            function cleanNum($val) {
                            if (is_null($val)) return 0;
                            // hilangkan simbol non angka, kecuali koma & titik
                            $val = preg_replace('/[^0-9.,-]/', '', trim($val));
                            // ubah koma jadi titik untuk float
                            $val = str_replace(',', '.', $val);
                            return is_numeric($val) ? (float)$val : 0;
                            }

                            $snvt = $inpres->firstWhere('satker', 'SNVT PJPA Serayu Opak');
                            $opsda = $inpres->firstWhere('satker', 'Satker OP SDA Serayu Opak');

                            // Hitung total jumlah paket
                            $totalPaket2 = $inpres->sum(fn($i) => cleanNum($i->jumlah_paket_2 ?? 0));
                            $totalPaket3 = $inpres->sum(fn($i) => cleanNum($i->jumlah_paket_3 ?? 0));

                            // Ambil nilai pagu & progres masing-masing
                            $pagu2_snvt = cleanNum($snvt->pagu_2 ?? 0);
                            $pagu2_opsda = cleanNum($opsda->pagu_2 ?? 0);
                            $pagu3_snvt = cleanNum($snvt->pagu_3 ?? 0);
                            $pagu3_opsda = cleanNum($opsda->pagu_3 ?? 0);

                            $prog_keu_2_snvt = cleanNum($snvt->prog_keu_2 ?? 0);
                            $prog_keu_2_opsda = cleanNum($opsda->prog_keu_2 ?? 0);
                            $prog_fis_2_snvt = cleanNum($snvt->prog_fis_2 ?? 0);
                            $prog_fis_2_opsda = cleanNum($opsda->prog_fis_2 ?? 0);

                            $prog_keu_3_snvt = cleanNum($snvt->prog_keu_3 ?? 0);
                            $prog_keu_3_opsda = cleanNum($opsda->prog_keu_3 ?? 0);
                            $prog_fis_3_snvt = cleanNum($snvt->prog_fis_3 ?? 0);
                            $prog_fis_3_opsda = cleanNum($opsda->prog_fis_3 ?? 0);

                            // Total pagu tahap 2 & 3
                            $totalPagu2 = $pagu2_snvt + $pagu2_opsda;
                            $totalPagu3 = $pagu3_snvt + $pagu3_opsda;

                            // === Hitung total progres Keuangan ===
                            $totalProgKeu2 = $totalPagu2 > 0
                            ? ((($prog_keu_2_snvt * $pagu2_snvt / 100) + ($prog_keu_2_opsda * $pagu2_opsda / 100)) / $totalPagu2 * 100)
                            : 0;

                            $totalProgKeu3 = $totalPagu3 > 0
                            ? ((($prog_keu_3_snvt * $pagu3_snvt / 100) + ($prog_keu_3_opsda * $pagu3_opsda / 100)) / $totalPagu3 * 100)
                            : 0;

                            // === Hitung total progres Fisik ===
                            $totalProgFis2 = $totalPagu2 > 0
                            ? ((($prog_fis_2_snvt * $pagu2_snvt / 100) + ($prog_fis_2_opsda * $pagu2_opsda / 100)) / $totalPagu2 * 100)
                            : 0;

                            $totalProgFis3 = $totalPagu3 > 0
                            ? ((($prog_fis_3_snvt * $pagu3_snvt / 100) + ($prog_fis_3_opsda * $pagu3_opsda / 100)) / $totalPagu3 * 100)
                            : 0;
                            @endphp
                            <tfoot>
                                <tr style="background: #f8fafc; font-weight: bold;">
                                    <td class="text-center">TOTAL</td>
                                    <td>{{ $totalPaket2 }}</td>
                                    <td>{{ number_format($totalPagu2, 0, ',', '.') }}</td>
                                    <td>{{ number_format($totalProgKeu2, 2) }}%</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{ $totalPaket3 }}</td>
                                    <td>{{ number_format($totalPagu3, 0, ',', '.') }}</td>
                                    <td>{{ number_format($totalProgKeu3, 2) }}%</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif

                    <!-- Tender -->
                    @if($tenders->count() > 0)
                    <h4 class="section-title mt-4">
                        <i class="fas fa-gavel me-2"></i>Pelaksanaan Tender Paket Pekerjaan TA. 2025
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Satuan Kerja</th>
                                    <th>PKT SYC & MYC Baru</th>
                                    <th>PAGU SYC & MYC Baru</th>
                                    <th>PKT MYC Lanjutan</th>
                                    <th>PAGU MYC Lanjutan </th>
                                    <th>PKT Tender</th>
                                    <th>PAGU Tender</th>
                                    <th>PKT Repeat Order</th>
                                    <th>PAGU Repeat Order</th>
                                    <th>PKT Tender Cepat</th>
                                    <th>PAGU Tender Cepat</th>
                                    <th>PKT Belum Lelang</th>
                                    <th>PAGU Belum Lelang</th>
                                    <th>PKT Proses Lelang</th>
                                    <th>PAGU Proses Lelang</th>
                                    <th>PKT Gagal Lelang</th>
                                    <th>PAGU Gagal Lelang</th>
                                    <th>PKT Terkontrak</th>
                                    <th>PAGU Terkontrak</th>
                                    <th>Nilai Kontrak Terkontrak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tenders as $tender)
                                <tr>
                                    <td>{{ $tender->satker }}</td>
                                    <td>{{ $tender->pkt_syc }}</td>
                                    <td>{{ is_numeric($tender->pagu_syc) ? number_format($tender->pagu_syc, 0, ',', '.') : $tender->pagu_syc }}
                                    </td>
                                    <td>{{ $tender->pkt_myc }}</td>
                                    <td>{{ is_numeric($tender->pagu_myc) ? number_format($tender->pagu_myc, 0, ',', '.') : $tender->pagu_myc }}
                                    </td>
                                    <td>{{ $tender->pkt_purchasing }}</td>
                                    <td>{{ is_numeric($tender->pagu_purchasing) ? number_format($tender->pagu_purchasing, 0, ',', '.') : $tender->pagu_purchasing }}
                                    </td>
                                    <td>{{ $tender->pkt_order }}</td>
                                    <td>{{ is_numeric($tender->pagu_order) ? number_format($tender->pagu_order, 0, ',', '.') : $tender->pagu_order }}
                                    </td>
                                    <td>{{ $tender->pkt_tender_cepat }}</td>
                                    <td>{{ is_numeric($tender->pagu_tender_cepat) ? number_format($tender->pagu_tender_cepat, 0, ',', '.') : $tender->pagu_tender_cepat }}
                                    </td>
                                    <td>{{ $tender->pkt_belum_lelang }}</td>
                                    <td>{{ is_numeric($tender->pagu_belum_lelang) ? number_format($tender->pagu_belum_lelang, 0, ',', '.') : $tender->pagu_belum_lelang }}
                                    </td>
                                    <td>{{ $tender->pkt_proses_lelang }}</td>
                                    <td>{{ is_numeric($tender->pagu_proses_lelang) ? number_format($tender->pagu_proses_lelang, 0, ',', '.') : $tender->pagu_proses_lelang }}
                                    </td>
                                    <td>{{ $tender->pkt_gagal_lelang }}</td>
                                    <td>{{ is_numeric($tender->pagu_gagal_lelang) ? number_format($tender->pagu_gagal_lelang, 0, ',', '.') : $tender->pagu_gagal_lelang }}
                                    </td>
                                    <td>{{ $tender->pkt_terkontrak }}</td>
                                    <td>{{ is_numeric($tender->pagu_terkontrak) ? number_format($tender->pagu_terkontrak, 0, ',', '.') : $tender->pagu_terkontrak }}
                                    </td>
                                    <td>{{ is_numeric($tender->nilai_terkontrak) ? number_format($tender->nilai_terkontrak, 0, ',', '.') : $tender->nilai_terkontrak }}
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- STATUS BLOKIR ANGGARAN -->
                    <h4 class="section-title mt-4">
                        <i class="fas fa-lock me-2"></i>Status Blokir Anggaran
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Nilai (Rp)</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalBlokir = $status_blokir->sum('jumlah');
                                $totalPersenBlokir = $status_blokir->sum('persentase');
                                @endphp

                                @foreach($status_blokir as $blokir)
                                <tr>
                                    <td class="fw fs-6">{{ $blokir->status }}</td>
                                    <td class="fw fs-6">Rp {{ number_format($blokir->jumlah, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ number_format($blokir->persentase, 2) }}%</td>
                                </tr>
                                @endforeach

                                <tr class="fw-bold table-light">
                                    <td>Total</td>
                                    <td>Rp {{ number_format($totalBlokir, 0, ',', '.') }}</td>
                                    <td>{{ number_format($totalPersenBlokir, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ALOKASI ANGGARAN PER SATKER -->
                    <h4 class="section-title mt-4">
                        <i class="fas fa-building me-2"></i>Alokasi Anggaran Per Satker
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Satker</th>
                                    <th>Nilai (Rp)</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalPaguSatker = $alokasiSatker->sum('pagu');
                                $totalPersenSatker = $alokasiSatker->sum('persentase');
                                @endphp

                                @foreach($alokasiSatker as $alok)
                                <tr>
                                    <td class="fw fs-6">{{ $alok->satker->nama ?? '-' }}</td>
                                    <td class="fw fs-6">Rp {{ number_format($alok->pagu, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ number_format($alok->persentase, 2) }}%</td>
                                </tr>
                                @endforeach

                                <tr class="fw-bold table-light">
                                    <td>Total</td>
                                    <td>Rp {{ number_format($totalPaguSatker, 0, ',', '.') }}</td>
                                    <td>{{ number_format($totalPersenSatker, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!--   <div class="row mt-4">
                        <!-- Sumber Dana
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <strong><i class="fas fa-wallet me-2"></i>Sumber Dana</strong>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Sumber Dana</th>
                                                <th>Nilai (Rp)</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sumber_dana as $s)
                                            <tr>
                                                <td>{{ $s->nama_sumber ?? '-' }}</td>
                                                <td>Rp {{ number_format($s->nilai ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($s->persentase ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->

                    <!-- SUMBER DANA -->
                    <h4 class="section-title mt-4">
                        <i class="fas fa-wallet me-2"></i>Sumber Dana
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Sumber Dana</th>
                                    <th>Nilai (Rp)</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                // Hitung total nilai dan persen sumber dana
                                $totalSumberDana = $sumber_dana->sum('jumlah');
                                $totalPersenSumberDana = $sumber_dana->sum('persentase');
                                @endphp

                                @foreach($sumber_dana as $s)
                                <tr>
                                    <td class="fw fs-6">{{ $s->sumber_dana ?? '-' }}</td>
                                    <td class="fw fs-6">Rp {{ number_format($s->jumlah ?? 0, 0, ',', '.') }}</td>
                                    <td class="fw fs-6">{{ number_format($s->persentase ?? 0, 2) }}%</td>
                                </tr>
                                @endforeach

                                <!-- Baris total -->
                                <tr class="fw-bold table-light">
                                    <td>Total</td>
                                    <td>Rp {{ number_format($totalSumberDana, 0, ',', '.') }}</td>
                                    <td>{{ number_format($totalPersenSumberDana, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <!-- Footer -->
                    <div class="row p-4 bg-light border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                Terakhir update: {{ now()->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                Generated by BBWS Serayu Opak Monitoring System
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi progress bars
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });

        // Keyboard shortcut untuk print (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>

</html>
