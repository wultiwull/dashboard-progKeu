<?php

namespace App\Imports;

use App\Models\ProgressPpk;
use App\Models\Satker;
use App\Models\WeeklyReport;
use App\Models\AlokasiSatker;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProgressPpkImports implements ToModel, WithStartRow
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
        return 2; // Skip header row
    }

    public function model(array $row)
    {
        // Skip row kosong atau total
        if (empty($row[0]) || !is_numeric($row[0]) || $row[0] == 'Total') {
            return null;
        }

        $nomorPpk = (int) $row[0];
        $satkerid = $this->getSatkerIdByNomorPpk($nomorPpk);

        return new ProgressPpk([
            'nomor_ppk' => $nomorPpk,
            'nama_ppk' => $row[1] ?? null,
            'satker_id' => $satkerid,
            'pagu' => $this->parseNumeric($row[2] ?? 0),
            'blokir' => $this->parseNumeric($row[3] ?? 0),
            'pagu_efisiensi' => $this->parseNumeric($row[4] ?? 0),
            'progress_keu_pagu_total' => $this->parsePercentage($row[5] ?? 0),
            'progress_fis_pagu_total' => $this->parsePercentage($row[6] ?? 0),
            'progress_keu_pagu_efisiensi' => $this->parsePercentage($row[7] ?? 0),
            'progress_fis_pagu_efisiensi' => $this->parsePercentage($row[8] ?? 0),
            'prognosis_rp' => $this->parseNumeric($row[9] ?? 0),
            'prognosis_persen' => $this->parsePercentage($row[10] ?? 0),
            'tahun_anggaran' => $this->tahunAnggaran,
            'tanggal_progress' => now(),
            'weekly_report_id' => $this->weeklyReportId,
        ]);
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

    private function getSatkerIdByNomorPpk($nomorPpk)
    {
        if ($nomorPpk >= 1 && $nomorPpk <= 4) return 1;
        if ($nomorPpk >= 5 && $nomorPpk <= 7) return 2;
        if ($nomorPpk >= 8 && $nomorPpk <= 11) return 3;
        if ($nomorPpk >= 12 && $nomorPpk <= 15) return 4;
        if ($nomorPpk >= 16 && $nomorPpk <= 19) return 5;
        return null;
    }

    public static function afterImportHandler($tahunAnggaran)
    {
        $totPaguAll = ProgressPpk::sum('pagu');
        if ($totPaguAll <= 0) return;

        $mapping = [
            1 => [1, 4],
            2 => [5, 7],
            3 => [8, 11],
            4 => [12, 15],
            5 => [16, 19],
        ];

        foreach ($mapping as $satkerId => [$min, $max]) {
            $totalPagu = ProgressPpk::whereBetween('nomor_ppk', [$min, $max])->sum('pagu');
            $persen = $totPaguAll > 0 ? ($totalPagu / $totPaguAll) * 100 : 0;

            AlokasiSatker::updateOrCreate(
                ['satker_id' => $satkerId, 'tahun_anggaran' => $tahunAnggaran],
                ['pagu' => $totalPagu, 'persentase' => $persen]
            );
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                self::afterImportHandler($this->tahunAnggaran);
            },
        ];
    }


}

