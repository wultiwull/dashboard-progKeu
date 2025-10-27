<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alokasi_satker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained();
            $table->decimal('pagu', 15, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->year('tahun_anggaran');
            $table->timestamps();

            $table->unique(['satker_id', 'tahun_anggaran']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alokasi_satker');
    }
};
