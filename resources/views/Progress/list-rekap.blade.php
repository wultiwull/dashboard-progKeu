@php
if (!isset($weeklyReports) || !is_iterable($weeklyReports)) {
$weeklyReports = collect();
}
@endphp


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Rekapan Progress - BBWS Serayu Opak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .main-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .main-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .main-header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 10px;
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }

        .reports-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .reports-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .reports-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .report-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .report-card-header {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 15px 20px;
            border-bottom: none;
        }

        .report-date {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .report-meta {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .report-card-body {
            padding: 20px;
            background: #f8f9fa;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 8px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        .btn-download {
            background: linear-gradient(135deg, #27ae60, #229954);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .btn-view {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .navigation-buttons {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
        }

        .btn-navigation {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
            transition: all 0.3s ease;
        }

        .btn-navigation:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.6);
        }

        .badge-new {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    @php
    if (!isset($weeklyReports) || !is_iterable($weeklyReports)) {
    $weeklyReports = collect();
    }
    @endphp

    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Rekapan Progress - BBWS Serayu Opak</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            /* (keep your existing styles here) */
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
        </style>
    </head>

<body>
    <div class="container py-5">
        <!-- Header Section -->
        <div class="header-section">
            <div class="main-header">
                <div class="logo-container">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h1>REKAPAN PROGRESS MINGGUAN</h1>
                <div class="subtitle">
                    BBWS Serayu Opak - Sistem Monitoring Keuangan
                </div>
            </div>

            <div class="p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="text-dark mb-3">
                            <i class="fas fa-download me-2"></i>Daftar Rekapan Tersedia
                        </h3>
                        <p class="text-muted mb-0">
                            Pilih rekap progress mingguan yang ingin Anda lihat atau download.
                            Data diupdate setiap hari Jumat secara otomatis.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="bg-light rounded p-3">
                            <small class="text-muted d-block">Total Rekapan</small>
                            <h4 class="text-primary mb-0">{{ $weeklyReports->count() }}</h4>
                            <small class="text-muted">tersedia untuk download</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="reports-container">
            <div class="reports-header">
                <h2><i class="fas fa-file-alt me-2"></i>Rekapan Progress Mingguan</h2>
            </div>

            <!-- <div class="p-4">

                @if($weeklyReports->count() > 0)
                <div class="row">
                    @foreach($weeklyReports as $report)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="report-card">
                            <div class="report-card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="report-date mb-0">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ \Carbon\Carbon::parse($report->report_date)->format('d F Y') }}
                                    </h5>
                                    @if($loop->first)
                                    <span class="badge badge-new">TERBARU</span>
                                    @endif
                                </div>
                                <div class="report-meta mt-1">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $report->created_at->diffForHumans() }}
                                </div>
                            </div> -->
            <div class="row">
                @forelse($weeklyReports as $report)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="report-date mb-0">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ \Carbon\Carbon::parse($report->report_date)->format('d F Y') }}
                                </h5>
                                @if($loop->first)
                                <span class="badge badge-new">TERBARU</span>
                                @endif
                            </div>
                            <div class="report-meta mt-1">
                                <i class="fas fa-clock me-1"></i>
                                {{ $report->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="report-card-body">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $report->progress_satkers_count ?? 'N/A' }}</div>
                                    <div class="stat-label">Satker</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $report->p3tgai_count ?? 'N/A' }}</div>
                                    <div class="stat-label">P3TGAI</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $report->inpres_count ?? 'N/A' }}</div>
                                    <div class="stat-label">INPRES</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">{{ $report->tenders_count ?? 'N/A' }}</div>
                                    <div class="stat-label">Tender</div>
                                </div>
                            </div>

                            <!-- <div class="d-grid gap-2">
                                <a href="{{ route('progress.view-rekap', $report->report_date) }}"
                                    target="_blank" class="btn btn-view">
                                    <i class="fas fa-eye me-2"></i>View Online
                                </a>
                                <a href="{{ route('progress.rekap.download', $report->report_date) }}"
                                    class="btn btn-download">
                                    <i class="fas fa-download me-2"></i>Download PDF
                                </a> -->
                            </div>

                            <div class="card-actions d-flex justify-content-center gap-2">
                                <a href="{{ route('rekap.view') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i> View Rekap
                                </a>
                                <a href="{{ route('rekap.download.pdf', $reportDate ?? null) }}" class="btn btn-danger btn-lg" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i> Download PDF
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3 class="text-muted">Belum Ada Rekapan</h3>
                    <p class="text-muted mb-4">
                        Belum ada rekap progress mingguan yang tersedia.
                        Rekap otomatis dibuat setiap hari Jumat setelah upload data.
                    </p>
                    <a href="{{ route('progress.upload') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-upload me-2"></i>Upload Data Pertama
                    </a>
                </div>
                @endforelse
            </div>

            <div class="report-card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ $report->progress_satkers_count ?? 'N/A' }}</div>
                        <div class="stat-label">Satker</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $report->p3tgai_count ?? 'N/A' }}</div>
                        <div class="stat-label">P3TGAI</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $report->inpres_count ?? 'N/A' }}</div>
                        <div class="stat-label">INPRES</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $report->tenders_count ?? 'N/A' }}</div>
                        <div class="stat-label">Tender</div>
                    </div>
                </div>

                <!-- <div class="d-grid gap-2">
                    <a href="{{ route('progress.view-rekap', $report->report_date) }}"
                        target="_blank" class="btn btn-view">
                        <i class="fas fa-eye me-2"></i>View Online
                    </a>
                    <a href="{{ route('progress.rekap.download', $report->report_date) }}"
                        class="btn btn-download">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a> -->
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3 class="text-muted">Belum Ada Rekapan</h3>
        <p class="text-muted mb-4">
            Belum ada rekap progress mingguan yang tersedia.
            Rekap otomatis dibuat setiap hari Jumat setelah upload data.
        </p>
        <a href="{{ route('progress.upload') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-upload me-2"></i>Upload Data Pertama
        </a>
    </div>
    @endif

    </div>
    </div>

    <!-- Info Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                <ul class="mb-0">
                    <li>Rekap progress mingguan otomatis digenerate setiap <strong>Hari Jumat</strong></li>
                    <li>Gunakan <strong>View Rekap</strong> untuk melihat laporan di browser</li>
                    <li>Gunakan <strong>Download PDF</strong> untuk menyimpan file PDF</li>
                    <li>Data bersumber dari sistem ie-Monitoring BBWS Serayu Opak</li>
                </ul>
            </div>
        </div>
    </div>

    </div>

    <!-- Navigation Buttons -->
    <div class="navigation-buttons">
        <a href="{{ route('dashboard') }}" class="btn btn-navigation">
            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
        </a>
        <a href="{{ route('progress.upload') }}" class="btn btn-navigation ms-2">
            <i class="fas fa-upload me-2"></i>Upload Data
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi untuk card
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.report-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>
