<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualInput extends Model
{
    use HasFactory;

    protected $table = 'dashboard_manual_inputs';

    protected $fillable = [
        'tahun_anggaran',
        'tanggal_laporan',
        'inpres_progress_fisik',
        'peringkat_bbws',
        'sda_prog_keu_total',
        'sda_prog_fis_total',
    ];
}
