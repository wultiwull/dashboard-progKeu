<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProgressPpk;
use App\Models\WeeklyReport;
use App\Models\ProgressSatker;
use App\Models\P3TGAI;
use App\Models\Inpres;
use App\Models\Tender;
use App\Imports\ProgressSatkerImport;
use App\Imports\P3TGAIImport;
use App\Imports\InpresImport;
use App\Imports\TenderImport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Show upload form
     */
    public function showUploadForm()
    {
        return view('progress.upload');
    }

    /**
     * Process Excel upload
     */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('excel_file');
            $uploadDate = Carbon::now();

            // Cek apakah hari ini Jumat?
            $isFriday = ($uploadDate->dayOfWeek == Carbon::FRIDAY);
            $weeklyReport = null;

            if ($isFriday) {
                // Buat weekly report record
                $weeklyReport = WeeklyReport::create([
                    'report_date' => $uploadDate->format('Y-m-d'),
                    'report_type' => 'weekly',
                    'file_name' => 'Rekap_Progress_' . $uploadDate->format('Y-m-d') . '.pdf',
                    'file_path' => '',
                    'is_published' => true
                ]);
            }

            // Import data dengan weekly_report_id (jika Jumat)
            $weeklyReportId = $isFriday ? $weeklyReport->id : null;

            // Import masing-masing sheet
            Excel::import(new ProgressSatkerImport($weeklyReportId), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            Excel::import(new P3TGAIImport($weeklyReportId), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            Excel::import(new InpresImport($weeklyReportId), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            Excel::import(new TenderImport($weeklyReportId), $file, null, \Maatwebsite\Excel\Excel::XLSX);

            // Jika Jumat, generate PDF
            if ($isFriday && $weeklyReport) {
                $this->generateWeeklyPDF($weeklyReport);
            }

            return redirect()->back()->with(
                'success',
                $isFriday ? 'Data berhasil diupload dan rekap mingguan telah dibuat!'
                    : 'Data berhasil diupload!'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Page list rekap (yang diakses dari GISO)
     */
    public function listRekap()
    {
        $weeklyReports = WeeklyReport::where('is_published', true)
            ->orderBy('report_date', 'desc')
            ->get();

        return view('progress.list-rekap', compact('weeklyReports'));
    }

    /**
     * Download rekap PDF berdasarkan tanggal Jumat
     */
    public function downloadRekap($reportDate)
    {
        $weeklyReport = WeeklyReport::where('report_date', $reportDate)->firstOrFail();

        if (!Storage::exists($weeklyReport->file_path)) {
            // Generate PDF jika belum ada
            $this->generateWeeklyPDF($weeklyReport);
        }

        return response()->download(storage_path('app/' . $weeklyReport->file_path));
    }

    /**
     * View rekap dalam HTML (tampilan bagus)
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

        $pdf = PDF::loadView('progress.pdf-template', $data)
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

    // public function uploadExcel(Request $request)
    // {
    //     // Validasi
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls|max:10240'
    //     ]);

    //     try {
    //         $file = $request->file('excel_file');
    //         $uploadDate = now();

    //         \Log::info('Upload started', [
    //             'file_name' => $file->getClientOriginalName(),
    //             'file_size' => $file->getSize()
    //         ]);

    //         // SELALU BUAT WEEKLY REPORT SETIAP UPLOAD (tidak hanya Jumat)
    //         $weeklyReport = WeeklyReport::create([
    //             'report_date' => $uploadDate->format('Y-m-d'),
    //             'report_type' => 'weekly',
    //             'file_name' => 'Rekap_Progress_' . $uploadDate->format('Y-m-d') . '.pdf',
    //             'file_path' => '',
    //             'is_published' => true
    //         ]);

    //         // Import data dengan weekly_report_id
    //         Excel::import(new ProgressSatkerImport($weeklyReport->id), $file);
    //         Excel::import(new P3TGAIImport($weeklyReport->id), $file);
    //         Excel::import(new InpresImport($weeklyReport->id), $file);
    //         Excel::import(new TenderImport($weeklyReport->id), $file);

    //         // SELALU GENERATE PDF REKAP SETIAP UPLOAD
    //         $pdfPath = $this->generateWeeklyPDF($weeklyReport);

    //         $message = 'Data berhasil diupload! Rekap PDF otomatis digenerate dan siap didownload.';

    //         \Log::info('Upload successful', [
    //             'message' => $message,
    //             'pdf_path' => $pdfPath,
    //             'report_id' => $weeklyReport->id
    //         ]);

    //         return redirect()->route('dashboard')->with('success', $message);
    //     } catch (\Exception $e) {
    //         \Log::error('Upload failed', [
    //             'error' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine()
    //         ]);

    //         return redirect()->route('dashboard')->with(
    //             'error',
    //             'Upload gagal: ' . $e->getMessage()
    //         );
    //     }
    // }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet 1: Progres_perSatker
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Progres_perSatker');

        $progressHeaders = [
            [
                'No',
                'Satuan Kerja',
                'PAGU (Rp.000)',
                'Blokir (Rp.000)',
                'Pagu Setelah Efisiensi (Rp.000)',
                'Progres Terhadap PAGU Total (%)',
                '',
                'Progres Terhadap PAGU Setelah Efisiensi (%)',
                '',
                'Prognosis',
                '',
                'Realisasi Keu',
                'Realisasi Fis'
            ],
            ['', '', '', '', '', 'Keu', 'Fis', 'Keu', 'Fis', 'Rp (Ribu)', '%', '', '']
        ];

        $sheet1->fromArray($progressHeaders, null, 'A1');
        $sheet1->mergeCells('F1:G1');
        $sheet1->mergeCells('H1:I1');
        $sheet1->mergeCells('J1:K1');

        // Sample data
        $sampleData = [
            [1, 'Satker OP', 260320529, 5427047, '=C3-D3', 63.70, 58.81, '=L3/E3*100', '=M3/E3*100', 254800299, 99.96, 165811380, '=G3*C3/100'],
            [2, 'SNVT PJSA', 352853058, 284030, '=C4-D4', 34.66, 38.78, '=L4/E4*100', '=M4/E4*100', 348670346, 98.89, 122291239, '=G4*C4/100'],
            [3, 'SNVT PJPA', 474924921, 52649994, '=C5-D5', 31.56, 31.99, '=L5/E5*100', '=M5/E5*100', 421073902, 99.72, 149899560, '=G5*C5/100'],
        ];

        $sheet1->fromArray($sampleData, null, 'A3');

        // Styling
        $sheet1->getStyle('A1:M2')->getFont()->setBold(true);
        $sheet1->getStyle('A1:M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E6E6FA');
        $sheet1->getStyle('A1:M10')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Sheet 2: P3TGAI
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('P3TGAI');

        $p3tgaiHeaders = [
            ['No', 'Kabupaten', 'Total Jumlah Lokasi', 'Progres (%)', '', 'No', 'Kabupaten', 'Total Jumlah Lokasi', 'Progres (%)', ''],
            ['', '', '', 'Keu', 'Fisik', '', '', '', 'Keu', 'Fisik']
        ];

        $sheet2->fromArray($p3tgaiHeaders, null, 'A1');
        $sheet2->mergeCells('A1:E1');
        $sheet2->mergeCells('F1:J1');
        $sheet2->setCellValue('A3', 'A');
        $sheet2->setCellValue('A4', 'WILAYAH DIY');
        $sheet2->setCellValue('F3', 'B');
        $sheet2->setCellValue('F4', 'WILAYAH JAWA TENGAH');

        // Sample data DIY
        $sampleDIY = [
            [1, 'Gunungkidul', 1, 100, 67.26],
            [2, 'Bantul', 1, '', ''],
            [3, 'Kulon Progo', 1, '', '']
        ];

        $sheet2->fromArray($sampleDIY, null, 'A5');

        // Sample data Jawa Tengah
        $sampleJateng = [
            [1, 'Wonosobo', 42, 99.07, 64.81],
            [2, 'Banjarnegara', 13, '', ''],
            [3, 'Purbalingga', 19, '', ''],
            [4, 'Cilacap', 19, '', ''],
            [5, 'Banyumas', 116, '', ''],
            [6, 'Kebumen', 14, '', ''],
            [7, 'Purworejo', 9, '', ''],
            [8, 'Magelang', 46, '', ''],
            [9, 'Temanggung', 46, '', '']
        ];

        $sheet2->fromArray($sampleJateng, null, 'F5');

        // Styling
        $sheet2->getStyle('A1:J2')->getFont()->setBold(true);
        $sheet2->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFFACD');
        $sheet2->getStyle('F1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E0FFFF');
        $sheet2->getStyle('A3:J20')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Sheet 3: Inpres
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Inpres');

        $inpresHeaders = [
            ['No', 'Kode', 'Kegiatan/KRO/RO/Paket', 'Target Vol', 'Satuan', 'Lokasi', 'Jenis Paket', 'Metode Pemilihan', 'Sumber Dana', 'Pagu (Rp Ribu)', 'Realisasi (Rp Ribu)', 'Blokir (Rp Ribu)', 'Pengembalian (Rp Ribu)', 'Keu (%)', 'Fisik (%)']
        ];

        $sheet3->fromArray($inpresHeaders, null, 'A1');

        // Sample data INPRES
        $sampleInpres = [
            [1, '7691.RBS.005.100.B', 'Konsultan Teknis Balai Swasembada Pangan SNVT PJPA Serayu Opak Paket II', 1, 'Dokumen', 'KAB. SEMARANG', 'Jasa Konsultansi', 'Penunjukan Langsung', 'RPM', 1158290, 231414, 0, 0, 19.98, 10.2],
            [2, '7691.RBS.005.105.B', 'Rehabilitasi Jaringan Utama DI Kewenangan Pemerintah Daerah di BBWS Serayu Opak Paket II', 14.01, 'Kilometer', 'KAB. SEMARANG', 'Pekerjaan Konstruksi', 'Penunjukan Langsung', 'RPM', 23165791, 4604216, 0, 0, 19.88, 0.09],
        ];

        $sheet3->fromArray($sampleInpres, null, 'A2');

        // Styling
        $sheet3->getStyle('A1:O1')->getFont()->setBold(true);
        $sheet3->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F0FFF0');
        $sheet3->getStyle('A1:O10')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Sheet 4: Tender
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Tender');

        $tenderHeaders = [
            ['No', 'Satker', 'SYC dan MYC Baru', '', 'MYC Lanjutan', '', 'Pelaksanaan Pengadaan Barang dan Jasa', '', '', '', '', '', '', '', 'Belum Lelang', '', 'Proses Lelang', '', 'Gagal Lelang', '', 'Terkontrak', '', 'NILAI KONTRAK (Rp Ribu)'],
            ['', '', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'e-Purchasing', '', 'Pengadaan/Penunjukan Langsung', '', 'Seleksi/Tender', '', 'Repeat Order', '', 'Tender Cepat', '', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)'],
            ['', '', '', '', '', '', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', 'PKT', 'PAGU DIPA (Rp Ribu)', '', '', '', '', '', '', '']
        ];

        $sheet4->fromArray($tenderHeaders, null, 'A1');
        $sheet4->mergeCells('A1:A3');
        $sheet4->mergeCells('B1:B3');
        $sheet4->mergeCells('C1:D1');
        $sheet4->mergeCells('E1:F1');
        $sheet4->mergeCells('G1:O1');
        $sheet4->mergeCells('P1:Q1');
        $sheet4->mergeCells('R1:S1');
        $sheet4->mergeCells('T1:U1');
        $sheet4->mergeCells('V1:W1');
        $sheet4->mergeCells('X1:X3');

        // Sample data Tender
        $sampleTender = [
            [1, 'Satker OP', 11, 46903247, 0, 0, 10, 18714570, 1, 28188677, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 11, 46903247, 45483713],
            [2, 'SNVT PJSA', 11, 350973058, 0, 0, 0, 0, 0, 0, 6, 109148397, 0, 0, 5, 241824661, 2, 48150000, 2, 97750000, 7, 205073058, 196216385],
        ];

        $sheet4->fromArray($sampleTender, null, 'A4');

        // Styling
        $sheet4->getStyle('A1:X3')->getFont()->setBold(true);
        $sheet4->getStyle('A1:X3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
        $sheet4->getStyle('A1:X10')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Set column widths
        foreach (range('A', 'X') as $column) {
            $sheet4->getColumnDimension($column)->setAutoSize(true);
        }

        // Save file
        $fileName = 'template-progress-lengkap-' . date('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/templates/' . $fileName);

        // Ensure directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
