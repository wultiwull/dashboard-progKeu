<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P3TGAI extends Model
{
    use HasFactory;

    protected $table = 'p3tgai';
    protected $guarded = ['id'];

    // protected $fillable = [
    //     'kabupaten',
    //     'wilayah',
    //     'jumlah_lokasi',
    //     'prog_keu',
    //     'prog_fis',
    //     'tahun_anggaran',
    //     'weekly_report_id'
    // ];
}
