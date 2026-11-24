<?php

namespace Database\Seeders;

use App\Models\BreedingRecord;
use App\Models\Cage;
use App\Models\GrowthRecord;
use App\Models\LogisticsCategory;
use App\Models\LogisticsItem;
use App\Models\Rabbit;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (Cage::query()->exists() || Rabbit::query()->exists()) {
            return;
        }

        $cages = collect([
            ['lokasi_blok' => 'Blok A - Sekat 01 (Indukan New Zealand)', 'kapasitas' => 8],
            ['lokasi_blok' => 'Blok A - Sekat 02 (Pejantan Rex)', 'kapasitas' => 6],
            ['lokasi_blok' => 'Blok B - Sekat 01 (Anakan Flemish Giant)', 'kapasitas' => 12],
            ['lokasi_blok' => 'Blok C - Sekat 01 (Karantina & Observasi)', 'kapasitas' => 4],
        ])->map(fn (array $cage) => Cage::create($cage));

        $rabbits = collect([
            ['cage_id' => $cages[0]->id, 'nama_kelinci' => 'Snowy', 'jenis_kelamin' => 'Betina', 'tgl_lahir' => '2024-01-14', 'status_kelinci' => 'Sehat'],
            ['cage_id' => $cages[1]->id, 'nama_kelinci' => 'Thumper', 'jenis_kelamin' => 'Jantan', 'tgl_lahir' => '2024-03-02', 'status_kelinci' => 'Sehat'],
            ['cage_id' => $cages[0]->id, 'nama_kelinci' => 'Luna', 'jenis_kelamin' => 'Betina', 'tgl_lahir' => '2024-05-18', 'status_kelinci' => 'Bunting'],
            ['cage_id' => $cages[3]->id, 'nama_kelinci' => 'Mimi', 'jenis_kelamin' => 'Betina', 'tgl_lahir' => '2024-08-05', 'status_kelinci' => 'Karantina'],
            ['cage_id' => $cages[1]->id, 'nama_kelinci' => 'Barong', 'jenis_kelamin' => 'Jantan', 'tgl_lahir' => '2024-02-10', 'status_kelinci' => 'Sehat'],
            ['cage_id' => $cages[1]->id, 'nama_kelinci' => 'Rocky', 'jenis_kelamin' => 'Jantan', 'tgl_lahir' => '2023-11-20', 'status_kelinci' => 'Sehat'],
            ['cage_id' => $cages[0]->id, 'nama_kelinci' => 'Bella', 'jenis_kelamin' => 'Betina', 'tgl_lahir' => '2023-12-05', 'status_kelinci' => 'Menyusui'],
        ])->map(fn (array $rabbit) => Rabbit::create($rabbit))->keyBy('nama_kelinci');

        $growth = [
            ['Snowy', -75, 2.90, 36.5, 'Sehat', 'Normal & Lincah', 'Nafsu makan pelet tinggi.'],
            ['Snowy', -45, 3.20, 38.0, 'Sehat', 'Sangat Baik', 'Konsumsi hay optimal.'],
            ['Snowy', -20, 3.55, 40.5, 'Sehat', 'Sangat Baik & Lincah', 'Bulu lebat, aktif.'],
            ['Snowy', -2, 3.85, 42.0, 'Sehat', 'Sangat Baik & Lincah', 'Nafsu makan pelet tinggi, konsumsi hay optimal.'],
            ['Thumper', -60, 3.60, 41.0, 'Sehat', 'Optimal', 'Pejantan aktif.'],
            ['Thumper', -30, 3.85, 43.0, 'Sehat', 'Optimal & Prima', 'Bobot stabil.'],
            ['Thumper', -3, 4.10, 45.0, 'Sehat', 'Optimal & Prima', 'Pejantan siap kawin. Bobot stabil 3 pekan.'],
            ['Luna', -50, 3.10, 38.0, 'Sehat', 'Baik', 'Indukan aktif.'],
            ['Luna', -25, 3.45, 40.0, 'Bunting', 'Baik', 'Mulai bunting.'],
            ['Luna', -4, 3.70, 41.0, 'Bunting', 'Baik', 'Perut mulai membesar.'],
            ['Mimi', -40, 2.40, 34.0, 'Karantina', 'Perlu perhatian', 'Observasi scabies.'],
            ['Mimi', -18, 2.50, 35.0, 'Karantina', 'Pemulihan', 'Injeksi dosis 1 selesai.'],
            ['Mimi', -5, 2.60, 36.0, 'Karantina', 'Pemulihan Scabies', 'Sudah injeksi wormectin dosis 1. Telinga membaik.'],
            ['Barong', -22, 3.90, 43.0, 'Sehat', 'Prima', 'Pejantan Rex.'],
            ['Barong', -6, 4.05, 44.0, 'Sehat', 'Prima', 'Siap dijadwalkan kawin.'],
            ['Bella', -28, 3.80, 42.0, 'Menyusui', 'Baik', 'Menyusui 7 anakan.'],
            ['Bella', -7, 3.90, 42.5, 'Menyusui', 'Baik', 'Anakan aktif menyusu.'],
            ['Rocky', -9, 4.20, 46.0, 'Sehat', 'Prima', 'Pejantan Flemish.'],
        ];

        foreach ($growth as [$nama, $hari, $berat, $panjang, $status, $kondisi, $keterangan]) {
            GrowthRecord::create([
                'rabbit_id' => $rabbits[$nama]->id,
                'tgl_pencatatan' => now()->addDays($hari)->toDateString(),
                'berat_badan' => $berat,
                'panjang_badan' => $panjang,
                'status_kelinci' => $status,
                'kondisi_kesehatan' => $kondisi,
                'keterangan' => $keterangan,
            ]);
        }

        BreedingRecord::create([
            'male_rabbit_id' => $rabbits['Barong']->id,
            'female_rabbit_id' => $rabbits['Luna']->id,
            'tgl_rencana_kawin' => now()->startOfWeek()->addDays(2)->toDateString(),
            'tgl_realisasi_kawin' => null,
            'status_perkawinan' => 'Direncanakan',
            'jumlah_anakan' => null,
            'keterangan' => 'Target silang ras New Zealand × Flemish untuk bobot potong.',
        ]);

        BreedingRecord::create([
            'male_rabbit_id' => $rabbits['Thumper']->id,
            'female_rabbit_id' => $rabbits['Snowy']->id,
            'tgl_rencana_kawin' => now()->subDays(38)->toDateString(),
            'tgl_realisasi_kawin' => now()->subDays(38)->toDateString(),
            'status_perkawinan' => 'Terlaksana (Bunting)',
            'jumlah_anakan' => null,
            'keterangan' => 'Perut mulai membesar, siapkan kotak sarang (nest box).',
        ]);

        BreedingRecord::create([
            'male_rabbit_id' => $rabbits['Rocky']->id,
            'female_rabbit_id' => $rabbits['Bella']->id,
            'tgl_rencana_kawin' => now()->subDays(77)->toDateString(),
            'tgl_realisasi_kawin' => now()->subDays(77)->toDateString(),
            'status_perkawinan' => 'Berhasil Melahirkan',
            'jumlah_anakan' => 7,
            'keterangan' => 'Semua anakan hidup normal, disapih usia 35 hari di Blok B.',
        ]);

        if (! LogisticsItem::query()->exists()) {
            $categories = LogisticsCategory::pluck('id', 'nama_kategori');

            $items = [
                ['category_id' => $categories['Pakan'], 'nama_item' => 'Pelet Guyofeed Rabbit Starter (25 kg)', 'stok' => 18, 'status_aktif' => true],
                ['category_id' => $categories['Pakan'], 'nama_item' => 'Hay Rumput Timothy Grade A (Import)', 'stok' => 3, 'status_aktif' => true],
                ['category_id' => $categories['Vitamin'], 'nama_item' => 'Vitamin B-Kompleks Cair (100 ml)', 'stok' => 8, 'status_aktif' => true],
                ['category_id' => $categories['Obat'], 'nama_item' => 'Wormectin Injeksi Scabies (50 ml)', 'stok' => 12, 'status_aktif' => true],
                ['category_id' => $categories['Peralatan'], 'nama_item' => 'Nipple Drinker Otomatis Kuningan 1/2"', 'stok' => 45, 'status_aktif' => true],
            ];

            foreach ($items as $item) {
                LogisticsItem::create($item);
            }
        }
    }
}
