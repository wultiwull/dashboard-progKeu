<?php

namespace App\Http\Controllers;

use App\Services\ProgressCalculator;
use App\Models\ProgressPpk;
use App\Models\Satker;
use App\Models\WeeklyReport;
use App\Models\P3TGAI;
use App\Models\Inpres;
use App\Models\Tender;
use App\Models\AlokasiSatker;
use App\Models\SumberDana;
use App\Models\StatusBlokir;
use App\Models\ProgressSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\ProgressPpkImports;
use App\Imports\ProgressSatkerImports;
use App\Imports\P3TGAIImports;
use App\Imports\InpresImports;
use App\Imports\TenderImports;
use App\Imports\ProgressImports;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


use Carbon\Carbon;

class ImportController extends Controller
{
    protected $progressCalculator;

    public function __construct(ProgressCalculator $progressCalculator)
    {
            $this->progressCalculator = $progressCalculator;
    }

    // UPLOAD EXCEL DAN PROSES IMPORT
    public function uploadExcel(Request $request)
    {
            $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
            'tahun_anggaran' => 'required|integer'
        ]);

        DB::beginTransaction();

        try {

            $file = $request->file('excel_file');
            $tahunAnggaran = $request->input('tahun_anggaran');
            $uploadDate = now();

            Log::info('Upload started', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            // Buat weekly report
            $weeklyReport= WeeklyReport::updateOrCreate(
                ['report_date' => $uploadDate->format('Y-m-d')],
                ['report_type' => 'weekly',
                'file_name' => 'Rekap_Progress_' . $uploadDate->format('Y-m-d') . '.pdf',
                'file_path' => '',
                'is_published' => true
                ]
            );

            Excel::import(new \App\Imports\ProgressImports($weeklyReport->id, $tahunAnggaran), $file);
            // // Import data dari berbagai sheet
            // // Excel::import(new ProgressPpkImports($weeklyReport, $tahunAnggaran), $file);
            // Excel::import(new ProgressSatkerImports($weeklyReport->id), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            // Excel::import(new P3TGAIImports($weeklyReport->id), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            // Excel::import(new InpresImports($weeklyReport->id), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            // Excel::import(new TenderImports($weeklyReport->id), $file, null, \Maatwebsite\Excel\Excel::XLSX); ;

            // Update semua data calculated (setelah semua data berhasil diimport)
            $this->progressCalculator->updateSumberDana($tahunAnggaran);
            $this->progressCalculator->updateStatusBlokir($tahunAnggaran);
            $this->progressCalculator->updateAlokasiSatker($tahunAnggaran);

            // Generate PDF
            $pdfPath = $this->generateWeeklyPDF($weeklyReport);

            DB::commit();

            Log::info('Upload successful', [
                'report_id' => $weeklyReport->id,
                'pdf_path' => $pdfPath,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Data berhasil diupload dan PDF berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('dashboard')->with(
                'error',
                'Upload gagal: ' . $e->getMessage()
            );
        }
    }

    /**
     * List rekap mingguan yang sudah diupload
     */
    public function listRekap()
    {
        $weeklyReports = WeeklyReport::where('is_published', true)
            ->orderBy('report_date', 'desc')
            ->get();

        return view('progress.list-rekap', compact('weeklyReports'));
    }

    /**
     * Download rekap PDF berdasarkan tanggal
     */
    public function downloadRekap($reportDate)
    {
        $weeklyReport = WeeklyReport::where('report_date', $reportDate)->firstOrFail();

        if (!Storage::exists($weeklyReport->file_path)) {
            $this->generateWeeklyPDF($weeklyReport);
        }

        return response()->download(storage_path('app/' . $weeklyReport->file_path));
    }

    /**
     * View rekap dalam HTML
     */
    public function viewRekap($reportDate)
    {
        $weeklyReport = WeeklyReport::where('report_date', $reportDate)->firstOrFail();

        $data = [
            'ppk' => ProgressPpk::where('weekly_report_id', $weeklyReport->id)->get(),
            'p3tgai' => P3TGAI::where('weekly_report_id', $weeklyReport->id)->get(),
            'inpres' => Inpres::where('weekly_report_id', $weeklyReport->id)->get(),
            'tenders' => Tender::where('weekly_report_id', $weeklyReport->id)->get(),
            'reportDate' => $weeklyReport->report_date,
            'weeklyReport' => $weeklyReport
        ];

        return view('progress.view-rekap', $data);
    }

    /**
     * Generate PDF untuk rekap mingguan
     */
    private function generateWeeklyPDF($weeklyReport)
    {
        $data = [
            'ppk' => ProgressPpk::where('weekly_report_id', $weeklyReport->id)->get(),
            'p3tgai' => P3TGAI::where('weekly_report_id', $weeklyReport->id)->get(),
            'inpres' => Inpres::where('weekly_report_id', $weeklyReport->id)->get(),
            'tenders' => Tender::where('weekly_report_id', $weeklyReport->id)->get(),
            'reportDate' => $weeklyReport->report_date,
            'weeklyReport' => $weeklyReport
        ];

        $pdf = FacadePdf::loadView('progress.pdf-template', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        $fileName = 'Rekap_Progress_' . $weeklyReport->report_date . '.pdf';
        $filePath = 'reports/' . $fileName;

        // Ensure directory exists
        if (!Storage::exists('reports')) {
            Storage::makeDirectory('reports');
        }

        // Save PDF ke storage
        Storage::put($filePath, $pdf->output());

        // Update file_path di database
        $weeklyReport->update([
            'file_path' => $filePath,
            'file_name' => $fileName
        ]);

        return $filePath;
    }
}




