<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void
{
Schema::create('inpres', function (Blueprint $table) {
    $table->id();

    //satker
    $table->string('satker')->nullable();
    $table->string('parent_satker')->nullable();

    //tahap II
    $table->string('jumlah_paket_2')->nullable();
    $table->string('pagu_2')->nullable();
    $table->string('prog_keu_2', 50)->nullable();
    $table->string('prog_fis_2', 50)->nullable();
    $table->string('tahap_2')->nullable();

    //Tahap III
    $table->string('jumlah_paket_3')->nullable();
    $table->string('pagu_3')->nullable();
    $table->string('prog_keu_3', 50)->nullable();
    $table->string('prog_fis_3', 50)->nullable();
    $table->string('tahap_3')->nullable();

    //relasi weekly report
    $table->foreignId('weekly_report_id')
        ->nullable()
        ->constrained('weekly_reports')
        ->onDelete('cascade');

    //info tambahan 
    $table->year('tahun_anggaran')->nullable();
            $table->date('tanggal_laporan')->nullable();
    $table->timestamps();
});

}

public function down(): void
{
Schema::dropIfExists('inpres');
}
};
