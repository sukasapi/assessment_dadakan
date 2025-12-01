<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriStudiKasus;

class KategoriStudiKasusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriStudiKasus::updateOrCreate(
            ['kode' => 'PQ'],
            [
                'nama' => 'PQ',
                'deskripsi' => 'Kategori PQ untuk studi kasus',
                'aktif' => true
            ]
        );

        KategoriStudiKasus::updateOrCreate(
            ['kode' => 'BQ'],
            [
                'nama' => 'BQ',
                'deskripsi' => 'Kategori BQ untuk studi kasus',
                'aktif' => true
            ]
        );
    }
}
