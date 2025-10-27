<?php

namespace App\Imports;

use App\Models\P3TGAI;
use App\Models\WeeklyReport;
use App\Models\Satker;
use App\Models\ProgressPpk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class P3TGAIImports implements ToModel, WithStartRow
{
    protected $weeklyReportId;
    protected $tahunAnggaran;

    public function __construct($weeklyReportId = null, $tahunAnggaran = null)
    {
        $this->weeklyReportId = $weeklyReportId;
        $this->tahunAnggaran = $tahunAnggaran;
    }

    public function startRow(): int
    {
        return 4;
    }

    private function parseNumeric($value)
    {
        if (is_numeric($value)) return floatval($value);
        if (is_string($value)) {
            $cleaned = str_replace(['.', ','], ['', '.'], $value);
            $cleaned = preg_replace('/[^\d.]/', '', $cleaned);
            return $cleaned ? floatval($cleaned) : 0;
        }
        return 0;
    }

    private function parsePercentage($value)
    {
        if (is_numeric($value)) return floatval($value);
        if (is_string($value)) {
            $cleaned = preg_replace('/[^\d,.]/', '', $value);
            $cleaned = str_replace(',', '.', $cleaned);
            return $cleaned ? floatval($cleaned) : 0;
        }
        return 0;
    }
    public function model(array $row)
    {
        if (empty($row[0]) && empty($row[1])) {
            return null;
        }

        return new P3TGAI([
            'id' => $row[0],
            'kabupaten' => $row[1] ?? '',
            'wilayah' => $row[2] ?? '',
            'jumlah_lokasi' => $this->parseNumeric($row[3] ?? 0),
            'prog_keu' => $this->parsePercentage($row[4] ?? 0),
            'prog_fis' => $this->parsePercentage($row[5] ?? 0),
            'tahun_anggaran' => $this->tahunAnggaran,
        ]);
    }
}
