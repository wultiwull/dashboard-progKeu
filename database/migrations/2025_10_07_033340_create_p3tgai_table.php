<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p3tgai', function (Blueprint $table) {
            $table->id();
            $table->string('kabupaten')->nullable();
            $table->string('wilayah')->nullable();
            $table->integer('jumlah_lokasi')->nullable();
            $table->decimal('prog_keu', 10, 2)->nullable();
            $table->decimal('prog_fis', 10, 2)->nullable();

            $table->foreignId('weekly_report_id')
                ->nullable()
                ->constrained('weekly_reports')
                ->onDelete('cascade');
                
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p3tgai');
    }
};

