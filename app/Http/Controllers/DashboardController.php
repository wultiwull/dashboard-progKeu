<?php

namespace App\Http\Controllers;

use App\Services\ProgressCalculator;
use App\Models\ProgressPpk;
use App\Models\Satker;
use App\Models\SumberDana;
use App\Models\StatusBlokir;
use App\Models\AlokasiSatker;
use App\Models\WeeklyReport;
use App\Models\P3TGAI;
use App\Models\Inpres;
use App\Models\Tender;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $progressCalculator;

    public function __construct(ProgressCalculator $progressCalculator)
    {
        $this->progressCalculator = $progressCalculator;
    }

    public function index()
    {
        try {

            $tanggal = null;
            $bulan = null;
            $tahunAnggaran = null;


            $latestTanggal = WeeklyReport::max('report_date');

            // Kalau tidak ada weekly report, ambil tanggal terbaru dari progress_ppk
            if (!$latestTanggal) {
                $latestTanggal = ProgressPpk::max('tanggal_laporan');
            }

            // Ambil tahun dari tanggal yang valid
            $tahunAnggaran = $latestTanggal ? date('Y', strtotime($latestTanggal)) : date('Y');

            // Query progress PPK
            $progressPpk = ProgressPpk::with('satker')
                ->when($tahunAnggaran, fn($q) => $q->where('tahun_anggaran', $tahunAnggaran))
                ->when($latestTanggal, fn($q) => $q->whereDate('tanggal_laporan', $latestTanggal))
                ->get() ?? collect();

            // Ambil tanggal laporan terakhir dari WeeklyReport
            // $latestTanggal = $reportDate ?? WeeklyReport::max('report_date');
            // $tahunAnggaran = $latestTanggal ? date('Y', strtotime($latestTanggal)) : date('Y');
            // $bulan = $latestTanggal ? date('m', strtotime($latestTanggal)) : date('m');
            // $tanggal = $latestTanggal ? date('d', strtotime($latestTanggal)) : date('d');

            // Kalau WeeklyReport kosong, inisialisasi array kosong
            $rekapData = $latestTanggal ? WeeklyReport::where('report_date', $latestTanggal)->get(): collect();

            // === 🔹 1. Data utama untuk Card Summary (Grand Total)
            $grandTotal = $this->progressCalculator->getGrandTotal($tahunAnggaran) ?? [
                'total_pagu' => 0,
                'total_blokir' => 0,
                'total_pagu_efisiensi' => 0,
                'progress_keu_pagu_total' => 0,
                'progress_fis_pagu_total' => 0,
                'prognosis_persen' => 0
            ];

            // === 🔹 2. Tabel Progress per PPK
            $progressPpk = ProgressPpk::with('satker')
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tanggal_progress', $latestTanggal) // 🔁 sesuaikan dengan kolom di DB
                ->get() ?? collect();

            // === 🔹 3. Progress per Satker (pakai helper service)
            $progressSatker = collect($this->progressCalculator->getAllSatkerProgress($tahunAnggaran) ?? []);

            // === 🔹 4. Sumber Dana, Status Blokir, dan Alokasi Satker
            $sumberDana = SumberDana::where('tahun_anggaran', $tahunAnggaran)->get() ?? collect();
            $statusBlokir = StatusBlokir::where('tahun_anggaran', $tahunAnggaran)->get() ?? collect();
            $alokasiSatker = AlokasiSatker::with('satker')
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('tanggal_laporan', $latestTanggal)
                ->get() ?? collect();

            // === 🔹 5. Data P3TGAI
            $p3tgai = P3TGAI::where('tahun_anggaran', $tahunAnggaran)
                ->where('tanggal_laporan', $latestTanggal)
                ->select('kabupaten', 'wilayah', 'jumlah_lokasi', 'prog_keu', 'prog_fis')
                ->get() ?? collect();

            // === 🔹 6. Data INPRES
            $inpres = Inpres::where('tahun_anggaran', $tahunAnggaran)
                ->where('tanggal_laporan', $latestTanggal)
                ->get() ?? collect();

            // === 🔹 7. Data Tender
            $tender = Tender::where('tahun_anggaran', $tahunAnggaran)
                ->where('tanggal_laporan', $latestTanggal)
                ->get() ?? collect();

            // === 🔹 8. Laporan terbaru dan 3 laporan terakhir
            $latestReport = WeeklyReport::where('is_published', true)->latest()->first();
            $recentReports = WeeklyReport::where('is_published', true)
                ->orderBy('report_date', 'desc')
                ->take(3)
                ->get() ?? collect();
        } catch (\Exception $e) {
            // kalau error, fallback biar gak crash
            // \Log::error('DashboardController@index error: ' . $e->getMessage());

            $latestTanggal = null;
            $tahunAnggaran = date('Y');
            $bulan = date('m');
            $rekapData = collect();
            $progressPpk = collect();
            $progressSatker = collect();
            $sumberDana = collect();
            $statusBlokir = collect();
            $alokasiSatker = collect();
            $p3tgai = collect();
            $inpres = collect();
            $tender = collect();
            $latestReport = null;
            $recentReports = collect();
            $grandTotal = [
                'pagu' => 0,
                'total_pagu' => 0,
                'total_blokir' => 0,
                'total_pagu_efisiensi' => 0,
                'progress_keu_pagu_total' => 0,
                'progress_fis_pagu_total' => 0,
                'prognosis_persen' => 0
            ];
        }

        // === 🔹 Return ke view dashboard utama
        return view('dashboard.dashboard', compact(
            'grandTotal',
            'progressPpk',
            'progressSatker',
            'sumberDana',
            'statusBlokir',
            'alokasiSatker',
            'tahunAnggaran',
            'latestReport',
            'recentReports',
            'rekapData',
            'latestTanggal',
            'bulan',
            'tanggal'
        ))->with([
            'p3tgaiData' => $p3tgai,
            'inpresData' => $inpres,
            'tenderData' => $tender,
        ]);
    }
}
