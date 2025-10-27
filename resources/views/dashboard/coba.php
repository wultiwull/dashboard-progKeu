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
            <li><a href="{{ route('dashboard') }}" class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> Progress</a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Keuangan</a></li>
            <li><a href="#"><i class="fas fa-hard-hat"></i> Fisik</a></li>

            <!-- MENU BARU: Download Rekap -->
            <li><a href="{{ route('progress.rekap.list') }}" class="{{ request()->is('progress/rekap*') ? 'active' : '' }}">
                    <i class="fas fa-download"></i> Download Rekap
                </a></li>

            <li><a href="{{ route('progress.upload') }}" class="{{ request()->is('progress/upload') ? 'active' : '' }}">
                    <i class="fas fa-upload"></i> Upload Data
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
                    <div class="progress-fill primary" style="width: 84.98%"></div>
                </div>
                <small>84.98% RPM</small>
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
                    <div class="progress-fill success" style="width: {{ $grandTotal['progress_keu_pagu_total'] }}%"></div>
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
                    <div class="progress-fill danger" style="width: {{ $grandTotal['progress_fis_pagu_total'] }}%"></div>
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
                    <a href="{{ route('progress.rekap.list') }}"
                        class="btn"
                        style="background: white; color: #667eea; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: block; text-align: center;">
                        Lihat Rekapan Tersedia
                    </a>
                </div>
                <small style="color: rgba(255,255,255,0.8); margin-top: 0.5rem; display: block;">
                    {{ $recentReports->count() ?? 0 }} rekap tersedia <!-- PERBAIKAN: ganti $weeklyReports dengan $recentReports -->
                </small>
            </div>

            <!-- Section Rekap Terbaru -->
            @if($latestReport)
            <div class="content-card" style="border-left: 4px solid #27ae60;">
                <div class="card-header">
                    <h3><i class="fas fa-clock me-2 text-success"></i>Rekap Progress Terbaru</h3>
                    <div class="card-actions">
                        <span class="badge" style="background: #27ae60; color: white; padding: 0.5rem 1rem; border-radius: 1rem;">
                            {{ \Carbon\Carbon::parse($latestReport->report_date)->format('d F Y') }}
                        </span>
                    </div>
                </div>

                <div class="form-row" style="align-items: center;">
                    <div class="form-group" style="flex: 2;">
                        <p style="margin: 0; color: var(--secondary);">
                            Rekap progress mingguan terakhir tersedia. Anda dapat melihat online atau mendownload file PDF.
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('progress.rekap.view', $latestReport->report_date) }}"
                            target="_blank"
                            class="btn btn-outline"
                            style="text-decoration: none;">
                            <i class="fas fa-eye me-2"></i>View Online
                        </a>
                        <a href="{{ route('progress.rekap.download', $latestReport->report_date) }}"
                            class="btn btn-primary"
                            style="text-decoration: none;">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upload Section -->
            <div class="content-card">
                <div class="card-header">
                    <h3>Upload Data Progress</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline">
                            <i class="fas fa-question-circle"></i> Panduan
                        </button>
                    </div>
                </div>

                <!-- =================== Upload Excel Section============================ -->
                <form id="upload-form"
                    action="{{ route('imports.progress_ppk_import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="form-horizontal"
                    accept=".xlsx, .xls">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="tahun_anggaran">Tahun Anggaran</label>
                            <select class="form-control" id="tahun_anggaran" name="tahun_anggaran" required>
                                <option value="2025" selected>2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="file">File Excel</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls" required>
                        </div>
                    </div>

                    <div id="loading" style="display:none; margin-top:10px;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang memproses file, mohon tunggu...
                    </div>

                    <div id="upload-result" style="margin-top:10px;"></div>
                    <button type="submit" class="btn btn-primary" id="upload-btn">
                        <i class="fas fa-upload"></i> Upload & Proses Data
                    </button>

                </form>

                <div class="resource-links" style="margin-top: 1.5rem;">
                    <a href="{{ route('progress.download-template') }}" class="resource-link" id="download-template">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                    <a href="#" class="resource-link">
                        <i class="fas fa-book"></i> Panduan Upload
                    </a>
                    <a href="#" class="resource-link">
                        <i class="fas fa-eye"></i> Contoh Format
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress per PPK Table -->
        <div class="content-card">
            <div class="card-header">
                <h3>Progress per PPK</h3>
                <div class="card-actions">
                    <button class="btn btn-outline" id="refresh-ppk">
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
                                <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
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
                                <i class="fas fa-calculator" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <br>
                                Data akan terhitung otomatis setelah upload Excel.
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
</div>
<div class="form-row">

    <!-- CARDS P3TGAI -->
    <div class="content-card">
        <div class="card-header">
            <h3>P3TGAI</h3>
            <div class="card-actions">
                <button class="btn btn-outline" id="refresh-p3tgai">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kabupaten</th>
                        <th>Wilayah</th>
                        <th>Jumlah Lokasi</th>
                        <th>Progress Keu (%)</th>
                        <th>Progress Fis (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($progressPpk as $index => $ppk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ppk->kabupaten }}</td>
                        <td>{{ $ppk->wilayah }}</td>
                        <td>{{ number_format($ppk->jumlah_lokasi, 0, ',', '.') }}</td>
                        <td>{{ number_format($ppk->prog_keu, 2) }}%</td>
                        <td>{{ number_format($ppk->prog_fis, 2) }}%</td>
                    </tr>
                    @empty
                    <td colspan="6" class="text-center" style="padding: 2rem; color: var(--secondary);">
                        <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                        Belum ada data P3TGAI.
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforelse

    <!-- CARDS INPRES -->
    <div class="content-card">
        <div class="card-header">
            <h3>INPRES</h3>
            <div class="card-actions">
                <button class="btn btn-outline" id="refresh-inpres">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Satker</th>
                        <th>Jumlah Paket Tahap 2</th>
                        <th>Pagu Tahap 2</th>
                        <th>Keu 2 (%)</th>
                        <th>Fisik 2 (%)</th>
                        <th>Jumlah Paket Tahap 3</th>
                        <th>Pagu Tahap 3</th>
                        <th>Keu 3 (%)</th>
                        <th>Fisik 3 (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inpres as $index => $ppk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ppk->satker }}</td>
                        <td>{{ $ppk->jumlah_paket_2 }}</td>
                        <td>Rp {{ number_format($ppk->pagu_2, 0, ',', '.') }}</td>
                        <td>{{ number_format($ppk->prog_keu_2, 2) }}%</td>
                        <td>{{ number_format($data->prog_fis_2, 2) }}%</td>
                        <td>{{ $data->jumlah_paket_3 }}</td>
                        <td>Rp {{ number_format($data->pagu_3, 0, ',', '.') }}</td>
                        <td>{{ number_format($data->prog_keu_3, 2) }}%</td>
                        <td>{{ number_format($data->prog_fis_3, 2) }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 2rem; color: var(--secondary);">
                            <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                            Belum ada data INPRES.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CARDS TENDER -->
    <div class="content-card">
        <div class="card-header">
            <h3>Tender</h3>
            <div class="card-actions">
                <button class="btn btn-outline" id="refresh-tender">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Satker</th>
                        <th>Jumlah Paket</th>
                        <th>Total Pagu (Rp)</th>
                        <th>Nilai Terkontrak (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tender as $index => $ppk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->satker }}</td>
                        <td>{{ number_format($data->total_paket, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($data->total_pagu, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($data->nilai_terkontrak, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 2rem; color: var(--secondary);">
                            <i class="fas fa-database" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                            Belum ada data Tender.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Action Button -->
    <div style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
        <div class="btn" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; border-radius: 50px; padding: 12px 20px; box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);">
            <i class="fas fa-download me-2"></i>
            <a href="{{ route('progress.rekap.list') }}" style="color: white; text-decoration: none;">
                Download Rekap
            </a>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            const form = document.getElementById('upload-form');
            const uploadBtn = document.getElementById('upload-btn');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    try {
                        const response = await fetch('{{ route("imports.progress_ppk_import") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });

                        // Cek apakah response JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 500));
                            throw new Error('Server mengembalikan response tidak valid. Silakan coba lagi.');
                        }

                        const result = await response.json();

                        if (result.success) {
                            showAlert(result.message, 'success');
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            const errorMsg = result.debug ?
                                `${result.message}\n\nDebug: ${result.debug.file}:${result.debug.line}` :
                                result.message;
                            showAlert(errorMsg, 'error');
                        }

                    } catch (error) {
                        console.error('Upload error:', error);
                        showAlert('Terjadi kesalahan: ' + error.message, 'error');
                    } finally {
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload & Proses Data';
                    }
                });
            }

            // Tombol refresh manual (jika ada)
            document.getElementById('refresh-ppk')?.addEventListener('click', () => window.location.reload());

            // Format Rupiah helper
            function formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Alert helper
            function showAlert(message, type = 'success') {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            z-index: 1000;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        `;
                alertDiv.textContent = message;
                document.body.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 4000);
            }
        });
        document.getElementById('file').addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Pilih file Excel...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });

        // Contoh tambahan: alert sukses upload
        @if(session('success'))
        alert("{{ session('success') }}");
        @endif
    </script>

    @endsection


