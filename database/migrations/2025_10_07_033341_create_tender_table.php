<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('tender', function (Blueprint $table) {
            $table->id();
            $table->string('satker')->nullable();

            $table->string('pkt_syc')->nullable();
            $table->string('pagu_syc')->nullable();

            $table->string('pkt_myc')->nullable();
            $table->string('pagu_myc')->nullable();

            $table->string('pkt_purchasing')->nullable();
            $table->string('pagu_purchasing')->nullable();

            $table->string('pkt_pengadaan')->nullable();
            $table->string('pagu_pengadaan')->nullable();

            $table->string('pkt_tender')->nullable();
            $table->string('pagu_tender')->nullable();

            $table->string('pkt_order')->nullable();
            $table->string('pagu_order')->nullable();

            $table->string('pkt_tender_cepat')->nullable();
            $table->string('pagu_tender_cepat')->nullable();

            $table->string('pkt_belum_lelang')->nullable();
            $table->string('pagu_belum_lelang')->nullable();

            $table->string('pkt_proses_lelang')->nullable();
            $table->string('pagu_proses_lelang')->nullable();

            $table->string('pkt_gagal_lelang')->nullable();
            $table->string('pagu_gagal_lelang')->nullable();

            $table->string('pkt_terkontrak')->nullable();
            $table->string('pagu_terkontrak')->nullable();

            $table->string('nilai_terkontrak')->nullable();

            $table->string('tahun_anggaran')->nullable();

            $table->foreignId('weekly_report_id')
                ->nullable()
                ->constrained('weekly_reports')
                ->onDelete('cascade');

            $table->timestamps();

            $table->index(['tahun_anggaran', 'weekly_report_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender');
    }
};
