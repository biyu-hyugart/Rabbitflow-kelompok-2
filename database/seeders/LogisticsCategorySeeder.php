<?php

namespace Database\Seeders;

use App\Models\LogisticsCategory;
use Illuminate\Database\Seeder;

class LogisticsCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pakan', 'Vitamin', 'Obat', 'Peralatan'] as $namaKategori) {
            LogisticsCategory::firstOrCreate(['nama_kategori' => $namaKategori]);
        }
    }
}
