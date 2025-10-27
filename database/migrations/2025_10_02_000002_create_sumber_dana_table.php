<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sumber_dana', function (Blueprint $table) {
            $table->id();
            $table->string('sumber_dana'); // RPM, SBSN, PHLN
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->year('tahun_anggaran');
            $table->timestamps();

            $table->unique(['sumber_dana', 'tahun_anggaran']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sumber_dana');
    }
};
