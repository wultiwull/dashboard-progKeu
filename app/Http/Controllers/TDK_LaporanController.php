<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ppk; // model ppk
use App\Models\P3TGAI;
use App\Models\Inpres;
use App\Models\Tender;
use App\Models\SumberDana;

class LaporanController extends Controller
{
    public function pdfReport(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // ambil data (sesuaikan query/relasi sesuai kebutuhan)
        $ppk = Ppk::orderBy('nama_ppk')->get();
        $p3tgai = P3TGAI::orderBy('wilayah')->get();
        $inpres = Inpres::orderBy('satker')->get();
        $tenders = Tender::orderBy('satker')->get();
        $sumberDana = SumberDana::where('tahun_anggaran', $tahun)->get();

        // return view untuk HTML -> PDF (mis. Dompdf)
        return view('pdf-template', compact('ppk','p3tgai','inpres','tenders','sumberDana','tahun'));
    }
}
