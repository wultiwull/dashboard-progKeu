<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualInput;

class ManualInputController extends Controller
{
    public function index()
    {
        $latestInput = ManualInput::latest()->first(); //
        return view('dashboard.manual-input', compact('latestInput'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_anggaran' => 'required|numeric',
            'tanggal_laporan' => 'required|date',
            'inpres_progress_fisik' => 'nullable|numeric',
            'peringkat_bbws' => 'nullable|integer|min:1|max:15',
            'sda_prog_keu_total' => 'nullable|numeric',
            'sda_prog_fis_total' => 'nullable|numeric',
        ]);

        ManualInput::create($validated);
        return redirect()->route('dashboard.manual-input')
            ->with('success', 'Data manual dashboard berhasil disimpan!');
    }
}
