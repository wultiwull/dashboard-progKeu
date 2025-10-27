<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('dashboard_manual_inputs', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_anggaran')->nullable();
            $table->date('tanggal_laporan')->nullable();

            // Data tambahan manual dashboard
            $table->decimal('inpres_progress_fisik', 5, 2)->nullable(); // progress fisik Inpres (%)
            $table->integer('peringkat_bbws')->nullable(); // peringkat BBWS SO
            $table->decimal('sda_progress_keu_total', 5, 2)->nullable(); // SDA Keuangan (%)
            $table->decimal('sda_progress_fis_total', 5, 2)->nullable(); // SDA Fisik (%)

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_manual_inputs');
    }
};
