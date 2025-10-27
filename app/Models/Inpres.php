<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inpres extends Model
{
    use HasFactory;

    protected $table = 'inpres';

    protected $fillable = [
        'satker',
        'jumlah_paket_2',
        'pagu_2',
        'prog_keu_2',
        'prog_fis_2',
        'jumlah_paket_3',
        'pagu_3',
        'prog_keu_3',
        'prog_fis_3',
        'tahun_anggaran',
        'weekly_report_id'
    ];
}
