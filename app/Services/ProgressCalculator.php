<?php

namespace App\Services;

use App\Models\ProgressPpk;
use App\Models\SumberDana;
use App\Models\StatusBlokir;
use App\Models\AlokasiSatker;
use App\Models\Satker;

class ProgressCalculator
{
    // Definisikan grouping PPK per Satker
    private $satkerGroups = [
        1 => [1, 4],     // Satker OP: PPK 1-4
        2 => [5, 7],     // SNVT PJSA: PPK 5-7
        3 => [8, 11],    // SNVT PJPA: PPK 8-11
        4 => [12, 15],   // Satker Balai: PPK 12-15
        5 => [16, 19],   // SNVT Bendungan: PPK 16-19
    ];

    public function calculateProgressSatker($satkerId, $tahunAnggaran)
    {
        $range = $this->satkerGroups[$satkerId] ?? null;

        if (!$range) {
            return null;
        }

        $progressPpk = ProgressPpk::whereBetween('nomor_ppk', $range)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->get();

        if ($progressPpk->isEmpty()) {
            return null;
        }

        return $this->calculateSatkerTotals($progressPpk);
    }

    private function calculateSatkerTotals($progressPpk)
    {
        $totalPagu = $progressPpk->sum('pagu');
        $totalPaguEfisiensi = $progressPpk->sum('pagu_efisiensi');

        // Hitung progress rata-rata berbobot
        $progressKeuTotal = $progressPpk->sum(function($item) {
            return $item->pagu * $item->progress_keu_pagu_total / 100;
        }) / max($totalPagu, 1);

        $progressFisTotal = $progressPpk->sum(function($item) {
            return $item->pagu * $item->progress_fis_pagu_total / 100;
        }) / max($totalPagu, 1);

        $progressKeuEfisiensi = $progressPpk->sum(function($item) {
            return $item->pagu_efisiensi * $item->progress_keu_pagu_efisiensi / 100;
        }) / max($totalPaguEfisiensi, 1);

        $progressFisEfisiensi = $progressPpk->sum(function($item) {
            return $item->pagu_efisiensi * $item->progress_fis_pagu_efisiensi / 100;
        }) / max($totalPaguEfisiensi, 1);

        return [
            'pagu' => $totalPagu,
            'blokir' => $progressPpk->sum('blokir'),
            'pagu_efisiensi' => $totalPaguEfisiensi,
            'progress_keu_pagu_total' => round($progressKeuTotal, 2),
            'progress_fis_pagu_total' => round($progressFisTotal, 2),
            'progress_keu_pagu_efisiensi' => round($progressKeuEfisiensi, 2),
            'progress_fis_pagu_efisiensi' => round($progressFisEfisiensi, 2),
            'prognosis_rp' => $progressPpk->sum('prognosis_rp'),
            'prognosis_persen' => round(($progressPpk->sum('prognosis_rp') / max($totalPaguEfisiensi, 1)) * 100, 2),
            'jumlah_ppk' => $progressPpk->count(),
            'detail_ppk' => $progressPpk->pluck('nama_ppk')
        ];
    }

    public function getAllSatkerProgress($tahunAnggaran)
    {
        $results = [];

        foreach ($this->satkerGroups as $satkerId => $range) {
            $satker = Satker::find($satkerId);
            $progress = $this->calculateProgressSatker($satkerId, $tahunAnggaran);

            if ($progress && $satker) {
                $results[] = array_merge([
                    'satker_id' => $satker->id,
                    'nama_satker' => $satker->nama,
                    'singkatan_satker' => $satker->singkatan,
                ], $progress);
            }
        }

        return $results;
    }

    public function getGrandTotal($tahunAnggaran)
    {
        $allProgress = ProgressPpk::where('tahun_anggaran', $tahunAnggaran)->get();

        if ($allProgress->isEmpty()) {
            return null;
        }

        return $this->calculateSatkerTotals($allProgress);
    }

    public function calculateTotals($tahunAnggaran)
    {
        $progressPpk = ProgressPpk::where('tahun_anggaran', $tahunAnggaran)->get();

        $totals = [
            'total_pagu' => $progressPpk->sum('pagu'),
            'total_blokir' => $progressPpk->sum('blokir'),
            'total_pagu_efisiensi' => $progressPpk->sum('pagu_efisiensi'),
            'total_prognosis' => $progressPpk->sum('prognosis_rp'),
        ];

        return $totals;
    }

    public function updateSumberDana($tahunAnggaran)
    {
        $totals = $this->calculateTotals($tahunAnggaran);
        $totalPagu = $totals['total_pagu'];

        // Data dari kamu: RPM 85.52%, SBSN 14.48%, PHLN 0%
        $sumberDana = [
            ['sumber_dana' => 'RPM', 'persentase' => 85.52],
            ['sumber_dana' => 'SBSN', 'persentase' => 14.48],
            ['sumber_dana' => 'PHLN', 'persentase' => 0.00],
        ];

        foreach ($sumberDana as $data) {
            SumberDana::updateOrCreate(
                [
                    'sumber_dana' => $data['sumber_dana'],
                    'tahun_anggaran' => $tahunAnggaran
                ],
                [
                    'jumlah' => ($data['persentase'] / 100) * $totalPagu,
                    'persentase' => $data['persentase']
                ]
            );
        }
    }

    public function updateStatusBlokir($tahunAnggaran)
    {
        $totals = $this->calculateTotals($tahunAnggaran);
        $totalPagu = $totals['total_pagu'];

        $statusBlokir = [
            ['status' => 'Blokir', 'persentase' => 12.48],
            ['status' => 'Tidak Blokir', 'persentase' => 87.52],
        ];

        foreach ($statusBlokir as $data) {
            StatusBlokir::updateOrCreate(
                [
                    'status' => $data['status'],
                    'tahun_anggaran' => $tahunAnggaran
                ],
                [
                    'jumlah' => ($data['persentase'] / 100) * $totalPagu,
                    'persentase' => $data['persentase']
                ]
            );
        }
    }

    public function updateAlokasiSatker($tahunAnggaran)
    {
        $satkers = Satker::all();
        $totals = $this->calculateTotals($tahunAnggaran);
        $totalPagu = $totals['total_pagu'];

        foreach ($satkers as $satker) {
            $progressSatker = $this->calculateProgressSatker($satker->id, $tahunAnggaran);

            if ($progressSatker) {
                $persentase = ($progressSatker['pagu'] / max($totalPagu, 1)) * 100;

                AlokasiSatker::updateOrCreate(
                    [
                        'satker_id' => $satker->id,
                        'tahun_anggaran' => $tahunAnggaran
                    ],
                    [
                        'pagu' => $progressSatker['pagu'],
                        'persentase' => round($persentase, 2)
                    ]
                );
            }
        }
    }
}
