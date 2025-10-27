<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date', 'report_type', 'file_name', 'file_path', 'is_published'
    ];

    protected $casts = [
        'report_date' => 'date',
        'is_published' => 'boolean'
    ];

    // Relationships
    public function progressSatkers()
    {
        return $this->hasMany(ProgressSatker::class);
    }

    public function p3tgai()
    {
        return $this->hasMany(P3TGAI::class);
    }

    public function inpres()
    {
        return $this->hasMany(Inpres::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
