<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPpk extends Model
{
    use HasFactory;

    protected $table = 'progress_ppk';

    protected $fillable = [
        'nomor_ppk', 'nama_ppk', 'satker_id', 'pagu', 'blokir',
        'pagu_efisiensi', 'realiasasi_keu','realiasasi_fis', 'progress_keu_pagu_total',
        'progress_fis_pagu_total', 'progress_keu_pagu_efisiensi',
        'progress_fis_pagu_efisiensi', 'prognosis_rp',
        'prognosis_persen', 'tanggal_progress', 'tahun_anggaran'
    ];

    protected $casts = [
        'pagu' => 'decimal:2',
        'blokir' => 'decimal:2',
        'pagu_efisiensi' => 'decimal:2',
        'realiasasi_keu' => 'decimal:2',
        'realiasasi_fis' => 'decimal:2',
        'progress_keu_pagu_total' => 'decimal:2',
        'progress_fis_pagu_total' => 'decimal:2',
        'progress_keu_pagu_efisiensi' => 'decimal:2',
        'progress_fis_pagu_efisiensi' => 'decimal:2',
        'prognosis_rp' => 'decimal:2',
        'prognosis_persen' => 'decimal:2',
        'tanggal_progress' => 'date',
    ];

    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }

    // Scope untuk grouping per Satker
    public function scopeBySatkerRange($query, $start, $end)
    {
        return $query->whereBetween('nomor_ppk', [$start, $end]);
    }

    /**
     * Helper method untuk mendapatkan satker_id berdasarkan nomor_ppk
     */
    public static function getSatkerIdByNomorPpk($nomorPpk)
    {
        return match(intval($nomorPpk)) {
            1, 2, 3, 4 => 1, // Satker OP
            5, 6, 7 => 2,    // SNVT PJSA
            8, 9, 10, 11 => 3, // SNVT PJPA
            12, 13, 14, 15 => 4, // Satker Balai
            16, 17, 18, 19 => 5, // SNVT Bendungan
            default => 1
        };
    }

    // Accessor untuk format Rupiah
    public function getPaguFormattedAttribute()
    {
        return 'Rp ' . number_format($this->pagu, 0, ',', '.');
    }

    public function getBlokirFormattedAttribute()
    {
        return 'Rp ' . number_format($this->blokir, 0, ',', '.');
    }

    public function getPaguEfisiensiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->pagu_efisiensi, 0, ',', '.');
    }

    public function getPrognosisRpFormattedAttribute()
    {
        return 'Rp ' . number_format($this->prognosis_rp, 0, ',', '.');
    }
}
