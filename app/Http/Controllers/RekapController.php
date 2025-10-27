<?php

namespace App\Http\Controllers;

use App\Models\AlokasiSatker;
use Illuminate\Http\Request;
use App\Models\ProgressPpk;
use App\Models\P3TGAI;
use App\Models\Inpres;
use App\Models\Tender;
use App\Models\WeeklyReport;
use App\Models\SumberDana;
use App\Models\StatusBlokir;

use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    /**
     * Halaman daftar rekap mingguan
     */
    public function index()
    {
        $recentReports = WeeklyReport::latest('report_date')->get();
        return view('progress.list-rekap', compact('recentReports'));
    }

    public function viewRekap()
    {
        // Ambil semua data progress
        $progressData = ProgressPpk::select(
            'nama_ppk as satuan_kerja',
            'pagu_efektif',
            'progress_keuangan',
            'progress_fisik'
        )->get();

        // Buat variable dummy untuk tanggal terakhir update
        $lastUpdate = now();

        // Kirim data ke view
        return view('Progress.view-rekap', [
            'progressData' => $progressData,
            'lastUpdate' => $lastUpdate
        ]);
    }

    public function show()
    {
        // Ambil semua data tanpa filter tanggal
        $ppk         = ProgressPpk::all();
        $p3tgai      = P3TGAI::all();
        $inpres      = Inpres::all();
        $tenders     = Tender::all();
        $sumber_dana = SumberDana::all();

        // Ambil 1 laporan mingguan terakhir (kalau ada)
        $weeklyReport = WeeklyReport::latest('report_date')->first();
        $reportDate   = $weeklyReport->report_date ?? now();

        // Ringkasan sederhana untuk header
        $summary = [
            'pagu_total'        => $ppk->sum('pagu_efektif') ?: $ppk->sum('pagu'),
            'progress_keuangan' => $ppk->avg('progres_keu') ?? 0,
            'progress_fisik'    => $ppk->avg('progres_fisik') ?? 0,
        ];
    }

    public function view($reportDate = null)
    {

        $latestTanggal = $reportDate ?? WeeklyReport::max('report_date');

        $ppk          = ProgressPpk::all();
        $p3tgai       = P3TGAI::all();
        $inpres       = Inpres::all();
        $tenders      = Tender::all();
        $sumber_dana  = SumberDana::all();
        $status_blokir = StatusBlokir::all();
        $alokasiSatker = AlokasiSatker::with('satker')->get();

        // Hitung total & Progress

        $total_pagu = $total_blokir = $total_pagu_efisiensi = $total_prognosis_rp = 0;
        $total_realisasi_keu = $total_realisasi_fis = 0;

        foreach ($ppk as $progress) {
            $pagu = is_numeric($progress->pagu) ? $progress->pagu : 0;
            $pagu_efisiensi = is_numeric($progress->pagu_efisiensi) ? $progress->pagu_efisiensi : 0;
            $blokir = is_numeric($progress->blokir) ? $progress->blokir : 0;
            $prognosis_rp = is_numeric($progress->prognosis_rp) ? $progress->prognosis_rp : 0;

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

        $summary = [
            'total_pagu' => $total_pagu,
            'total_pagu_efisiensi' => $total_pagu_efisiensi,
            'total_progress_keu_pagu_total' => $total_pagu > 0 ? ($total_realisasi_keu / $total_pagu) * 100 : 0,
            'total_progress_fis_pagu_total' => $total_pagu > 0 ? ($total_realisasi_fis / $total_pagu) * 100 : 0,
            'total_progress_keu_pagu_efisiensi' => $total_pagu_efisiensi > 0 ? ($total_realisasi_keu / $total_pagu_efisiensi) * 100 : 0,
            'total_progress_fis_pagu_efisiensi' => $total_pagu_efisiensi > 0 ? ($total_realisasi_fis / $total_pagu_efisiensi) * 100 : 0,
            'total_prognosis_rp' => $total_prognosis_rp,
            'total_prognosis_persen' => $total_pagu_efisiensi > 0 ? ($total_prognosis_rp / $total_pagu_efisiensi) * 100 : 0,
        ];

        return view('progress.view-rekap', compact(
            'ppk',
            'p3tgai',
            'inpres',
            'tenders',
            'sumber_dana',
            'status_blokir',
            'alokasiSatker',
            'latestTanggal',
            'reportDate',
            'summary',
        ));
    }

    public function downloadPdf($reportDate = null)
    {
        // Ambil tanggal laporan terbaru jika tidak ada parameter
        $latestTanggal = $reportDate ?? WeeklyReport::max('report_date');

        // 🔹 Ambil semua data terkait sesuai kebutuhan tampilan PDF
        $ppk           = ProgressPpk::all();
        $p3tgai        = P3TGAI::all();
        $inpres        = Inpres::all();
        $tenders       = Tender::all();
        $sumber_dana   = SumberDana::all();
        $status_blokir = StatusBlokir::all();
        $alokasiSatker = AlokasiSatker::all();


        // 🔹 Ringkasan total sederhana (opsional, kalau mau dipakai di header/footer)
        $summary = [
            'pagu_total'   => $ppk->sum('pagu'),
            'blokir_total' => $ppk->sum('blokir'),
            'tahun'        => now()->year,
            'tanggal'      => now()->format('d F Y'),
        ];

        // 🔹 Load PDF Template Baru
        $pdf = Pdf::loadView('pdf-template', compact(
            'ppk',
            'p3tgai',
            'inpres',
            'tenders',
            'sumber_dana',
            'status_blokir',
            'alokasiSatker',
            'summary',
            'latestTanggal'
        ))
            ->setPaper('a4', 'landscape') // landscape biar tabel lebar muat semua kolom
            ->setOption('dpi', 120)
            ->setOption('isHtml5ParserEnabled', true);

        // 🔹 Stream (preview di browser) atau Download (langsung unduh)
        return $pdf->download('Laporan kegiatan BBWS Serayu Opak' . $latestTanggal . '.pdf');
    }
}
