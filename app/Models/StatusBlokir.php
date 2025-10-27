<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusBlokir extends Model
{
    use HasFactory;
    protected $table = "status_blokir";
    
    protected $fillable = [
        'status', 'jumlah', 'persentase', 'tahun_anggaran'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'persentase' => 'decimal:2',
    ];
}
