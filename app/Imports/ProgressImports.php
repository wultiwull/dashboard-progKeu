<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProgressImports implements WithMultipleSheets
{
    protected $weeklyReportId;
    protected $tahunAnggaran;

    public function __construct($weeklyReportId, $tahunAnggaran)
    {
        $this->weeklyReportId = $weeklyReportId;
        $this->tahunAnggaran = $tahunAnggaran;
    }

    public function sheets(): array
    {
        return [
            'PAGU_PPK' => new ProgressPpkImports($this->weeklyReportId, $this->tahunAnggaran),
            'P3TGAI' => new P3TgaiImports($this->weeklyReportId, $this->tahunAnggaran),
            'INPRES' => new InpresImports($this->weeklyReportId, $this->tahunAnggaran),
            'TENDER' => new TenderImports($this->weeklyReportId, $this->tahunAnggaran),

        ];

        Log::info('📊 Sheets diimport:', array_keys($sheets));
        return array_filter($sheets, fn($import, $sheetName) => $import, ARRAY_FILTER_USE_BOTH);
    }
}
