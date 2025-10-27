<?php

namespace App\Imports;

use App\Models\ProgressSatker;
use App\Models\Satker;
use App\Models\ProgressPpk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class ProgressSatkerImports implements ToModel, WithStartRow
{
    protected $weeklyReportId;
    protected $uploadDate;

    public function __construct($weeklyReportId)
    {
        $this->weeklyReportId = $weeklyReportId;
        $this->uploadDate = Carbon::now();
    }

    public function startRow(): int
    {
        return 3; // Mulai dari row 3 (skip header)
    }

    public function model(array $row)
    {
        // Skip header atau row kosong
        if (empty($row[1]) || $row[1] === 'Satuan Kerja' || $row[1] === 'Total') {
            return null;
        }

        return new ProgressSatker([
            'satuan_kerja' => $row[1] ?? null,
            'pagu' => $this->parseNumber($row[2] ?? 0),
            'blokir' => $this->parseNumber($row[3] ?? 0),
            'pagu_setelah_efisiensi' => $this->parseNumber($row[4] ?? 0),
            'progres_keu_pagu_total' => $this->parseDecimal($row[5] ?? 0),
            'progres_fis_pagu_total' => $this->parseDecimal($row[6] ?? 0),
            'progres_keu_pagu_efisiensi' => $this->parseDecimal($row[7] ?? 0),
            'progres_fis_pagu_efisiensi' => $this->parseDecimal($row[8] ?? 0),
            'prognosis_rp' => $this->parseNumber($row[9] ?? 0),
            'prognosis_persen' => $this->parseDecimal($row[10] ?? 0),
            'realisasi_keu' => $this->parseNumber($row[11] ?? 0),
            'realisasi_fis' => $this->parseNumber($row[12] ?? 0),
            'upload_date' => $this->uploadDate,
        ]);
    }

    private function parseNumber($value)
    {
        if (is_numeric($value)) return $value;
        return (float) preg_replace('/[^0-9]/', '', $value);
    }

    private function parseDecimal($value)
    {
        if (is_numeric($value)) return $value;
        return (float) str_replace(',', '.', preg_replace('/[^0-9,.]/', '', $value));
    }
}
