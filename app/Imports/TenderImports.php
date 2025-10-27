<?php

namespace App\Imports;

use App\Models\Tender;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TenderImports implements ToModel, WithStartRow
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

    private function parseMoney($value)
    {
        if (empty($value)) return '0';
        $cleaned = str_replace('.', '', $value); // hapus titik ribuan
        $cleaned = str_replace(',', '.', $cleaned); // ubah koma desimal
        return (string)$cleaned;
    }

    private function parsePkt($value)
    {
        return (string)($value ?? '0'); // simpan angka/tanda * sebagai string
    }

    public function model(array $row)
    {
        return new Tender([
            'id' => $row[0],
            'satker' => $row[1] ?? '',
            'pkt_syc' => $this->parsePkt($row[2] ?? 0),
            'pagu_syc' => $this->parseMoney($row[3] ?? 0),
            'pkt_myc' => $this->parsePkt($row[4] ?? 0),
            'pagu_myc' => $this->parseMoney($row[5] ?? 0),
            'pkt_purchasing' => $this->parsePkt($row[6] ?? 0),
            'pagu_purchasing' => $this->parseMoney($row[7] ?? 0),
            'pkt_pengadaan' => $this->parsePkt($row[8] ?? 0),
            'pagu_pengadaan' => $this->parseMoney($row[9] ?? 0),
            'pkt_tender' => $this->parsePkt($row[10] ?? 0),
            'pagu_tender' => $this->parseMoney($row[11] ?? 0),
            'pkt_order' => $this->parsePkt($row[12] ?? 0),
            'pagu_order' => $this->parseMoney($row[13] ?? 0),
            'pkt_tender_cepat' => $this->parsePkt($row[14] ?? 0),
            'pagu_tender_cepat' => $this->parseMoney($row[15] ?? 0),
            'pkt_belum_lelang' => $this->parsePkt($row[16] ?? 0),
            'pagu_belum_lelang' => $this->parseMoney($row[17] ?? 0),
            'pkt_proses_lelang' => $this->parsePkt($row[18] ?? 0),
            'pagu_proses_lelang' => $this->parseMoney($row[19] ?? 0),
            'pkt_gagal_lelang' => $this->parsePkt($row[20] ?? 0),
            'pagu_gagal_lelang' => $this->parseMoney($row[21] ?? 0),
            'pkt_terkontrak' => $this->parsePkt($row[22] ?? 0),
            'pagu_terkontrak' => $this->parseMoney($row[23] ?? 0),
            'nilai_terkontrak' => $this->parseMoney($row[24] ?? 0),
        ]);
    }

}
