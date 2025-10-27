<?php

namespace Database\Seeders;

use App\Models\Satker;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    public function run()
    {
        $satkers = [
            ['kode' => 'SAT1', 'nama' => 'Satker OP', 'singkatan' => 'OP'],
            ['kode' => 'SAT2', 'nama' => 'SNVT PJSA', 'singkatan' => 'PJSA'],
            ['kode' => 'SAT3', 'nama' => 'SNVT PJPA', 'singkatan' => 'PJPA'],
            ['kode' => 'SAT4', 'nama' => 'Satker Balai', 'singkatan' => 'BALAI'],
            ['kode' => 'SAT5', 'nama' => 'SNVT Bendungan', 'singkatan' => 'BEND'],
        ];

        foreach ($satkers as $satker) {
            Satker::updateOrCreate(['kode' => $satker['kode']], $satker);
        }
    }
}
