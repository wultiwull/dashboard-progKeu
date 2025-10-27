@extends('layouts.app')

@section('title', 'Progress Kegiatan BBWS Serayu Opak')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-water"></i>
            <h1>BBWS Serayu Opak</h1>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}"
                    class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Progress</a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Keuangan</a></li>
            <li><a href="#"><i class="fas fa-hard-hat"></i> Fisik</a></li>

            <!-- Menu Download Rekap -->
            <li><a href="{{ route('progress.list-rekap') }}"
                    class="{{ request()->is('progress/rekap*') ? 'active' : '' }}">
                    <i class="fas fa-download"></i> Download Rekap
                </a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Dashboard Progress Kegiatan TA {{ $tahunAnggaran }}</h2>
            <div class="user-info">
                <div style="text-align: right;">
                    <div style="font-weight: 600; color: var(--dark);">BBWS Serayu Opak</div>
                    <small style="color: var(--secondary);">Sistem Monitoring Progress</small>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">Rp {{ number_format($grandTotal['pagu'], 0, ',', '.') }}</div>
                        <div class="stat-label">Total Pagu Anggaran</div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill primary" style="width: {{ $grandTotal['progress_keu_pagu_total'] ?? 0 }}%">
                    </div>
                </div>
                <small>{{ number_format($grandTotal['progress_keu_pagu_total'] ?? 0, 2) }} % M</small>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">{{ number_format($grandTotal['progress_keu_pagu_total'], 2) }}%</div>
                        <div class="stat-label">Progress Keuangan</div>
                    </div>
                    <div class="stat-icon success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill success"
                        style="width: {{ $grandTotal['progress_keu_pagu_total'] ?? 0, 2 }}%"></div>
                </div>
                <small>Terhadap Pagu Total</small>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value">{{ number_format($grandTotal['progress_fis_pagu_total'], 2) }}%</div>
                        <div class="stat-label">Progress Fisik</div>
                    </div>
                    <div class="stat-icon danger">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill danger" style="width: {{ $grandTotal['progress_fis_pagu_total'] }}%">
                    </div>
                </div>
                <small>Terhadap Pagu Total</small>
            </div>

            <!-- CARD BARU: Download Rekap -->
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="stat-header">
                    <div>
                        <div class="stat-value" style="color: white; font-size: 1.5rem;">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-label" style="color: rgba(255,255,255,0.9);">Download Rekap</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                </div>
                <div style="margin-top: 1rem;">
                    <a href="{{ route('progress.list-rekap') }}" class="btn"
                        style="background: white; color: #667eea; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: block; text-align: center;">
                        Lihat Rekapan Tersedia
                    </a>
                </div>
                <small style="color: rgba(255,255,255,0.8); margin-top: 0.5rem; display: block;">
                    {{ $recentReports->count() ?? 0 }} rekap tersedia
                </small>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="content-card">
            <div class="card-header">
                <h3>Upload Data Progress</h3>
                <div class="card-actions">
                    <button class="btn btn-outline" onclick="downloadTemplate()">
                        <i class="fas fa-download"></i> Download Template
                    </button>
                </div>
            </div>

            <!-- Upload Excel Section -->
            <div id="upload-tab">
                <form action="{{ route('progress.upload') }}" method="POST" enctype="multipart/form-data"
                    id="upload-form">
                    @csrf
                    <div class="form-row">
                        <!-- Tahun Anggaran -->
                        <div class="form-group">
                            <label class="form-label" for="tahun_anggaran">Tanggal Laporan</label>
                            <input type="date" class="form-control" id="tanggal_laporan" name="tanggal_laporan"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <!-- File Excel -->
                        <div class="form-group">
                            <label class="form-label" for="excel_file">File Excel</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                accept=".xlsx, .xls" required>
                            <div class="form-text">Format: .xlsx, .xls (Max 10MB)</div>
                        </div>
                    </div>

                    <!-- === Manual Input Tambahan === -->
                    <!-- <h5><i class="fas fa-clipboard-list me-2"></i>Input Manual Dashboard</h5> -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Progres Fisik INPRES (%)</label>
                            <input type="number" step="0.01" name="inpres_progress_fisik" class="form-control"
                                placeholder="Contoh: 85.4">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Peringkat BBWSSO</label>
                            <input type="number" name="peringkat_bbws" min="1" max="15" class="form-control"
                                placeholder="Contoh: 5">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Progress Keuangan SDA (%)</label>
                            <input type="number" step="0.01" name="sda_prog_keu_total" class="form-control"
                                placeholder="Contoh: 73.25">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Progress Fisik SDA (%)</label>
                            <input type="number" step="0.01" name="sda_prog_fis_total" class="form-control"
                                placeholder="Contoh: 68.7">
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary" id="upload-btn">
                        <i class="fas fa-upload"></i> Upload Data
                    </button>
                </form>
            </div>

            <!-- Info Setelah Upload -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        <!-- Section Download Rekap Terbaru -->
        @if($latestReport)
        <div class="content-card" style="border-left: 4px solid #27ae60;">
            <div class="card-header">
                <h3><i class="fas fa-file-pdf me-2 text-danger"></i>Rekap Progress Terbaru</h3>
                <div class="card-actions">
                    <span class="badge"
                        style="background: #27ae60; color: white; padding: 0.5rem 1rem; border-radius: 1rem;">
                        {{ \Carbon\Carbon::parse($latestReport->report_date)->format('d F Y') }}
                    </span>
                </div>
            </div>

            <div class="form-row" style="align-items: center;">
                <div class="form-group" style="flex: 2;">
                    <p style="margin: 0; color: var(--secondary);">
                        <strong>Rekap terbaru sudah siap!</strong> Data dari upload terakhir telah diproses dan rekap
                        PDF bisa langsung didownload.
                    </p>
                    <small class="text-muted">
                        File: {{ $latestReport->file_name }} |
                        Generated: {{ $latestReport->created_at->diffForHumans() }}
                    </small>
                </div>
                <div class="form-group" style="flex: 1; display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <a href="{{ route('progress.rekap.download', $latestReport->report_date) }}" class="btn btn-success"
                        style="text-decoration: none;">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- List Rekap Sebelumnya -->
        @if($recentReports->count() > 0)
        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-history me-2"></i>Rekap Sebelumnya</h3>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentReports as $report)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                            <td>{{ $report->file_name }}</td>
                            <td>
                                <span class="badge"
                                    style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 0.5rem;">
                                    Siap Download
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('progress.rekap.download', $report->report_date) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Progress per PPK Table -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress per PPK</h3>
                <div class="card-actions">
                    <button class="btn btn-outline" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PPK</th>
                            <th>Satker</th>
                            <th>Pagu (Rp)</th>
                            <th>Blokir (Rp)</th>
                            <th>Progress Keu (%)</th>
                            <th>Progress Fis (%)</th>
                            <th>Prognosis (%)</th>
                        </tr>
                    </thead>
                    <tbody id="ppk-table-body">
                        @forelse($progressPpk as $ppk)
                        <tr>
                            <td>{{ $ppk->nomor_ppk }}</td>
                            <td>{{ $ppk->nama_ppk }}</td>
                            <td>{{ $ppk->satker->singkatan ?? '-' }}</td>
                            <td>Rp {{ number_format($ppk->pagu, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($ppk->blokir, 0, ',', '.') }}</td>
                            <td>{{ number_format($ppk->progress_keu_pagu_total, 2) }}%</td>
                            <td>{{ number_format($ppk->progress_fis_pagu_total, 2) }}%</td>
                            <td>{{ number_format($ppk->prognosis_persen, 2) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 2rem; color: var(--secondary);">
                                <i class="fas fa-database"
                                    style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <br>
                                Belum ada data. Silakan upload file Excel.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Progress per Satker Table -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress per Satker</h3>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Satker</th>
                            <th>Pagu (Rp)</th>
                            <th>Progress Keu Total (%)</th>
                            <th>Progress Fis Total (%)</th>
                            <th>Progress Keu Efisiensi (%)</th>
                            <th>Progress Fis Efisiensi (%)</th>
                            <th>Prognosis (%)</th>
                        </tr>
                    </thead>
                    <tbody id="satker-table-body">
                        @forelse($progressSatker as $satker)
                        <tr>
                            <td>{{ $satker['nama_satker'] }}</td>
                            <td>Rp {{ number_format($satker['pagu'], 0, ',', '.') }}</td>
                            <td>{{ number_format($satker['progress_keu_pagu_total'], 2) }}%</td>
                            <td>{{ number_format($satker['progress_fis_pagu_total'], 2) }}%</td>
                            <td>{{ number_format($satker['progress_keu_pagu_efisiensi'], 2) }}%</td>
                            <td>{{ number_format($satker['progress_fis_pagu_efisiensi'], 2) }}%</td>
                            <td>{{ number_format($satker['prognosis_persen'], 2) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 2rem; color: var(--secondary);">
                                <i class="fas fa-calculator"
                                    style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <br>
                                Data akan terhitung otomatis setelah upload Excel.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARDS P3TGAI  -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress P3TGAI</h3>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kabupaten</th>
                            <th>Wilayah</th>
                            <th>Jumlah Lokasi</th>
                            <th>Progress Keuangan (%)</th>
                            <th>Progress Fisik (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($p3tgaiData as $data)
                        <tr>
                            <td>{{ $data->kabupaten ?? '-' }}</td>
                            <td>{{ $data->wilayah ?? '-' }}</td>
                            <td>{{ $data->jumlah_lokasi ?? 0 }}</td>
                            <td>{{ number_format($data->progress_keu ?? 0, 2) }}%</td>
                            <td>{{ number_format($data->progress_fis ?? 0, 2) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 2rem; color: var(--secondary);">
                                <i class="fas fa-database" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                Belum ada data P3TGAI.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARDS INPRES -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress INPRES</h3>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Satuan Kerja</th>
                            <th>Jumlah Paket Tahap II</th>
                            <th>Pagu Tahap II (Rp)</th>
                            <th>Progress Keu Tahap II(%)</th>
                            <th>Progress Fis Tahap II (%)</th>
                            <th>Jumlah Paket Tahap III</th>
                            <th>Pagu Tahap III (Rp)</th>
                            <th>Progress Keu Tahap III (%)</th>
                            <th>Progress Fis Tahap III (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inpresData as $item)
                        <tr>
                            <td>{{ $item->satker ?? '-' }}</td>

                            <!-- Tahap II -->
                            <td>{{ $item->jumlah_paket_2 ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_2 ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->prog_keu_2 ?? 0, 2) }}%</td>
                            <td>{{ number_format($item->prog_fis_2 ?? 0, 2) }}%</td>

                            <!-- Tahap III -->
                            <td>{{ $item->jumlah_paket_3 ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_3 ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->prog_keu_3 ?? 0, 2) }}%</td>
                            <td>{{ number_format($item->prog_fis_3 ?? 0, 2) }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 2rem; color: var(--secondary);">
                                <i class="fas fa-database" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                Belum ada data INPRES.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card Tender -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress TENDER</h3>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Satker</th>
                            <th>Pkt SYC dan MYC Baru</th>
                            <th>Pagu SYC dan MYC Baru</th>
                            <th>Pkt MYC Lanjutan</th>
                            <th>Pagu MYC Lanjutan</th>
                            <th>Pkt e-Purchasing</th>
                            <th>Pagu e-Purchasing</th>
                            <th>Pkt Pengadaan</th>
                            <th>Pagu Pengadaan</th>
                            <th>Pkt Tender</th>
                            <th>Pagu Tender</th>
                            <th>Pkt Repeat Order</th>
                            <th>Pagu Repeat Order</th>
                            <th>Pkt Tender Cepat</th>
                            <th>Pagu Tender Cepat</th>
                            <th>Pkt Belum Lelang</th>
                            <th>Pagu Belum Lelang</th>
                            <th>Pkt Proses Lelang</th>
                            <th>Pagu Proses Lelang</th>
                            <th>Pkt Gagal Lelang</th>
                            <th>Pkt Terkontrak</th>
                            <th>Pagu Terkontrak</th>
                            <th>Nilai Terkontrak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenderData as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->satker ?? '-' }}</td>

                            <td>{{ $item->pkt_syc ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_syc ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_myc ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_myc ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_purchasing ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_purchasing ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_pengadaan ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_pengadaan ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_tender ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_tender ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_order ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_order ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_tender_cepat ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_tender_cepat ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_belum_lelang ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_belum_lelang ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_proses_lelang ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_proses_lelang ?? 0, 0, ',', '.') }}</td>

                            <td>{{ $item->pkt_gagal_lelang ?? 0 }}</td>
                            <td>{{ $item->pkt_terkontrak ?? 0 }}</td>
                            <td>Rp {{ number_format($item->pagu_terkontrak ?? 0, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->nilai_terkontrak ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="24" class="text-center" style="padding: 2rem; color: var(--secondary);">
                                <i class="fas fa-database" style="font-size: 3rem; opacity: 0.5;"></i><br>
                                Belum ada data Tender.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sumber Dana & Status Blokir Cards -->
        <div class="form-row">
            <!-- Sumber Dana -->
            <div class="content-card" style="flex: 1;">
                <div class="card-header">
                    <h3>Sumber Dana</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sumber Dana</th>
                                <th>Jumlah (Rp)</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sumberDana as $dana)
                            <tr>
                                <td>{{ $dana->sumber_dana }}</td>
                                <td>Rp {{ number_format($dana->jumlah, 0, ',', '.') }}</td>
                                <td>{{ number_format($dana->persentase, 2) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 1rem; color: var(--secondary);">
                                    Data akan terhitung otomatis
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Status Blokir -->
            <div class="content-card" style="flex: 1;">
                <div class="card-header">
                    <h3>Status Blokir</h3>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Jumlah (Rp)</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusBlokir as $blokir)
                            <tr>
                                <td>{{ $blokir->status }}</td>
                                <td>Rp {{ number_format($blokir->jumlah, 0, ',', '.') }}</td>
                                <td>{{ number_format($blokir->persentase, 2) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 1rem; color: var(--secondary);">
                                    Data akan terhitung otomatis
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
</div>

<!-- Quick Action Button -->
<div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
    <a href="{{ route('progress.list-rekap') }}" class="btn"
        style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; border-radius: 50px; padding: 12px 20px; box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4); text-decoration: none;">
        <i class="fas fa-download me-2"></i>Download Rekap
    </a>
</div>
@endsection

@section('scripts')
<script>
    // Download Template
    function downloadTemplate() {
        window.location.href = "{{ route('progress.download-template') }}";
    }

    // Simple file validation
    document.getElementById('excel_file')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // MB
            const fileName = file.name.toLowerCase();

            if (!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls')) {
                alert('Error: File harus format Excel (.xlsx atau .xls)');
                e.target.value = '';
            } else if (fileSize > 10) {
                alert('Error: File terlalu besar. Maksimal 10MB');
                e.target.value = '';
            }
        }
    });
</script>
@endsection
