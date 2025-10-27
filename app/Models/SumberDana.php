<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    use HasFactory;
    protected $table = 'sumber_dana';
    protected $fillable = [
        'sumber_dana', 'jumlah', 'persentase', 'tahun_anggaran'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'persentase' => 'decimal:2',
    ];
}
