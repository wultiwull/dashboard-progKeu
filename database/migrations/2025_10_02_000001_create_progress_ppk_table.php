<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_ppk', function (Blueprint $table) {
            $table->id();

            // Identitas PPK
            $table->integer('nomor_ppk');
            $table->string('nama_ppk');
            $table->unsignedBigInteger('satker_id')->nullable();

            // Nilai-nilai keuangan
            $table->decimal('pagu', 15, 2)->default(0);
            $table->decimal('blokir', 15, 2)->default(0);
            $table->decimal('pagu_efisiensi', 15, 2)->default(0);

            // Progress
            $table->decimal('progress_keu_pagu_total', 5, 2)->default(0);
            $table->decimal('progress_fis_pagu_total', 5, 2)->default(0);
            $table->decimal('progress_keu_pagu_efisiensi', 5, 2)->default(0);
            $table->decimal('progress_fis_pagu_efisiensi', 5, 2)->default(0);

            // Prognosis
            $table->decimal('prognosis_rp', 15, 2)->default(0);
            $table->decimal('prognosis_persen', 5, 2)->default(0);

            //realisasi
            $table->decimal('realisasi_keu', 20, 2)->default(0)->after('pagu_efisiensi');
            $table->decimal('realisasi_fis', 20, 2)->default(0)->after('realisasi_keu');
            
            // Informasi laporan
            $table->date('tanggal_progress')->nullable();
            $table->year('tahun_anggaran')->nullable();

            // Relasi
            $table->foreignId('weekly_report_id')
                ->nullable()
                ->constrained('weekly_reports')
                ->onDelete('cascade');

            $table->timestamps();

            // Hindari duplikasi per PPK per tahun
            $table->unique(['nomor_ppk', 'tahun_anggaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_ppk');
    }
};
