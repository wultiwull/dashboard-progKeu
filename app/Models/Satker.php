<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satker extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];

    protected $fillable = ['kode', 'nama', 'singkatan'];

    public function progressPpk(): HasMany
    {
        return $this->hasMany(ProgressPpk::class);
    }
}
