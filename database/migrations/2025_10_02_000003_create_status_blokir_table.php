<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('status_blokir', function (Blueprint $table) {
            $table->id();
            $table->string('status'); // Blokir, Tidak Blokir
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->year('tahun_anggaran');
            $table->timestamps();

            $table->unique(['status', 'tahun_anggaran']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_blokir');
    }
};
