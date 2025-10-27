<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressSatker extends Model
{
    use HasFactory;

    protected $table = 'progress_satker'; // sesuaikan dengan nama tabel kamu

    protected $fillable = [
        'satker_id',
        'nama_satker',
        'tahun_anggaran',
        'pagu_total',
        'blokir_total',
        'pagu_efisiensi_total',
        'progress_keu_total',
        'progress_fis_total',
        'tanggal_progress'
    ];
}
