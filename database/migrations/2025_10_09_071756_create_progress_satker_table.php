<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('progress_satker', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('satker_id')->nullable();
        $table->year('tahun_anggaran')->nullable();
        $table->decimal('pagu', 20, 2)->nullable();
        $table->decimal('realisasi_keuangan', 20, 2)->nullable();
        $table->decimal('progres_fisik', 5, 2)->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_satker');
    }
};
