<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SesiPenilaianSeeder::class,
            PenilaianSeeder::class,
            ItemPenilaianSeeder::class,
            KategoriStudiKasusSeeder::class,
            AspekPenilaianStudiKasusSeeder::class,
            LevelPenilaianStudiKasusSeeder::class,
            PesertaSeeder::class,
            KemajuanPenilaianSeeder::class,
            JawabanSeeder::class,
        ]);
    }
}
