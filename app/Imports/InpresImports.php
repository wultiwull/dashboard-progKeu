<?php

namespace App\Imports;

use App\Models\Inpres;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InpresImports implements ToModel, WithStartRow
{
    protected $weeklyReportId;

    public function __construct($weeklyReportId)
    {
        $this->weeklyReportId = $weeklyReportId;
    }

    public function startRow(): int
    {
        return 5;
    }

    // private function parseNumeric($value)
    // {
    //     if (is_numeric($value)) return floatval($value);
    //     if (is_string($value)) {
    //         $cleaned = str_replace(['.', ','], ['', '.'], $value);
    //         $cleaned = preg_replace('/[^\d.]/', '', $cleaned);
    //         return $cleaned ? floatval($cleaned) : 0;
    //     }
    //     return 0;
    // }

    // private function parsePercentage($value)
    // {
    //     if (is_numeric($value)) return floatval($value);
    //     if (is_string($value)) {
    //         $cleaned = preg_replace('/[^\d,.]/', '', $value);
    //         $cleaned = str_replace(',', '.', $cleaned);
    //         return $cleaned ? floatval($cleaned) : 0;
    //     }
    //     return 0;
    // }

    public function model(array $row)
    {
        // skip row kosong
        if (empty($row[0])) return null;

        return new Inpres([
            'satker' => $row[0] ?? '',
            'jumlah_paket_2' => isset($row[1]) ? (string)$row[1] : '',
            'pagu_2' => isset($row[2]) ? (string)$row[2] : '',
            'prog_keu_2' => isset($row[3]) ? (string)$row[3] : '',
            'prog_fis_2' => isset($row[4]) ? (string)$row[4] : '',
            'tahap_2' => $row[5] ?? '',
            'jumlah_paket_3' => isset($row[6]) ? (string)$row[6] : '',
            'pagu_3' => isset($row[7]) ? (string)$row[7] : '',
            'prog_keu_3' => isset($row[8]) ? (string)$row[8] : '',
            'prog_fis_3' => isset($row[9]) ? (string)$row[9] : '',
            'tahap_3' => $row[10] ?? '',
        ]);
    }
}
