<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $table = 'tender';
    protected $guarded = ['id'];

    // protected $fillable = [
    //     'satker',

    // //     // SYC Baru
    //     'pkt_syc',
    //     'pagu_syc',

    //     // MYC Lanjutan
    //     'pkt_myc',
    //     'pagu_myc',

    // //     // e-Purchasing
    //     'pkt_purchasing',
    //     'pagu_purchasing',

    // //     // Pengadaan/Penunjukan Langsung
    //     'pkt_pengadaan',

    // //     // Seleksi/Tender
    //     'pkt_tender',
    //     'pagu_tender',

    // //     // Repeat Order
    //     'pkt_order',
    //     'pagu_order',

    // //     // Tender Cepat
    //     'pkt_tender_cepat',
    //     'pagu_tender_cepat',

    // //     // Belum Lelang
    //     'pkt_belum_lelang',
    //     'pagu_belum_lelang',

    // //     // Proses Lelang
    //     'pkt_proses_lelang',
    //     'pagu_proses_lelang',

    // //     // Gagal Lelang
    //     'pkt_gagal_lelang',
    //     'pagu_gagal_lelang',

    // //     // Terkontrak
    //     'pkt_terkontrak',
    //     'pagu_terkontrak',

    //     'nilai_terkontrak',
    //     'tahun_anggaran',
    //     'weekly_report_id'
    // ];
}
