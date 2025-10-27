<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlokasiSatker extends Model
{
    use HasFactory;
    protected $table = "alokasi_satker";

    protected $fillable = [
        'satker_id', 'pagu', 'persentase', 'tahun_anggaran'
    ];

    protected $casts = [
        'pagu' => 'decimal:2',
        'persentase' => 'decimal:2',
    ];

    public function satker(): BelongsTo
    {
        return $this->belongsTo(Satker::class);
    }
}
