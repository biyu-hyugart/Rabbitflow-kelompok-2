<?php

namespace Tests\Feature;

use App\Models\BreedingRecord;
use App\Models\Cage;
use App\Models\GrowthRecord;
use App\Models\LogisticsCategory;
use App\Models\LogisticsItem;
use App\Models\Rabbit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function owner(): User
    {
        return User::where('username', 'hendra_owner')->firstOrFail();
    }

    private function karyawan(): User
    {
        return User::where('username', 'budi_kandang')->firstOrFail();
    }

    public function test_rabbit_can_be_updated_with_another_available_cage(): void
    {
        $this->markTestSkipped('Modul Kelinci dikosongkan (Lena).');
        $owner = $this->owner();
        $rabbit = Rabbit::firstOrFail();
        $cage = Cage::where('id', '!=', $rabbit->cage_id)
            ->whereRaw('kapasitas > (select count(*) from rabbits where rabbits.cage_id = cages.id)')
            ->firstOrFail();

        $this->actingAs($owner)->put('/data-kelinci/' . $rabbit->id, [
            'nama_kelinci' => 'Snowy Update',
            'jenis_kelamin' => $rabbit->jenis_kelamin,
            'tgl_lahir' => $rabbit->tgl_lahir->toDateString(),
            'cage_id' => $cage->id,
            'status_kelinci' => 'Sehat',
        ])->assertRedirect(route('rabbits.index'));

        $this->assertDatabaseHas('rabbits', [
            'id' => $rabbit->id,
            'nama_kelinci' => 'Snowy Update',
            'cage_id' => $cage->id,
        ]);
    }

    public function test_growth_record_can_be_updated_and_deleted(): void
    {
        $karyawan = $this->karyawan();
        $record = GrowthRecord::firstOrFail();

        $this->actingAs($karyawan)->put('/pertumbuhan/' . $record->id, [
            'rabbit_id' => $record->rabbit_id,
            'tgl_pencatatan' => $record->tgl_pencatatan->toDateString(),
            'berat_badan' => 4.25,
            'panjang_badan' => 44.5,
            'status_kelinci' => 'Sehat',
            'kondisi_kesehatan' => 'Sangat Baik',
            'keterangan' => 'Hasil penimbangan ulang.',
        ])->assertRedirect(route('growth.index'));

        $this->assertDatabaseHas('growth_records', [
            'id' => $record->id,
            'berat_badan' => 4.25,
            'panjang_badan' => 44.5,
            'keterangan' => 'Hasil penimbangan ulang.',
        ]);

        $this->actingAs($karyawan)->delete('/pertumbuhan/' . $record->id)
            ->assertRedirect(route('growth.index'));

        $this->assertDatabaseMissing('growth_records', ['id' => $record->id]);
    }

    public function test_breeding_record_can_be_updated_and_deleted_by_owner(): void
    {
        $owner = $this->owner();
        $record = BreedingRecord::where('status_perkawinan', 'Direncanakan')->firstOrFail();

        $this->actingAs($owner)->put('/perkawinan/' . $record->id, [
            'male_rabbit_id' => $record->male_rabbit_id,
            'female_rabbit_id' => $record->female_rabbit_id,
            'tgl_rencana_kawin' => now()->addDays(3)->toDateString(),
            'status_perkawinan' => 'Terlaksana (Bunting)',
            'keterangan' => 'Realisasi jadwal diperbarui.',
        ])->assertRedirect(route('breeding.index'));

        $this->assertDatabaseHas('breeding_records', [
            'id' => $record->id,
            'status_perkawinan' => 'Terlaksana (Bunting)',
        ]);

        $this->actingAs($owner)->delete('/perkawinan/' . $record->id)
            ->assertRedirect(route('breeding.index'));

        $this->assertDatabaseMissing('breeding_records', ['id' => $record->id]);
    }

    public function test_breeding_validation_rejects_invalid_gender_and_same_rabbit(): void
    {
        $owner = $this->owner();
        $jantan = Rabbit::where('jenis_kelamin', 'Jantan')->firstOrFail();
        $betina = Rabbit::where('jenis_kelamin', 'Betina')->firstOrFail();

        $this->actingAs($owner)->post('/perkawinan', [
            'male_rabbit_id' => $betina->id,
            'female_rabbit_id' => $jantan->id,
            'tgl_rencana_kawin' => now()->addWeek()->toDateString(),
            'status_perkawinan' => 'Direncanakan',
        ])->assertSessionHasErrors(['male_rabbit_id', 'female_rabbit_id']);

        $this->actingAs($owner)->post('/perkawinan', [
            'male_rabbit_id' => $jantan->id,
            'female_rabbit_id' => $jantan->id,
            'tgl_rencana_kawin' => now()->addWeek()->toDateString(),
            'status_perkawinan' => 'Direncanakan',
        ])->assertSessionHasErrors('female_rabbit_id');
    }

    public function test_logistics_item_can_be_updated_and_category_can_be_created(): void
    {
        $this->markTestSkipped('Modul Logistik dikosongkan (Abelina).');
        $karyawan = $this->karyawan();
        $item = LogisticsItem::firstOrFail();
        $category = LogisticsCategory::firstOrFail();

        $this->actingAs($karyawan)->put('/logistik/item/' . $item->id, [
            'category_id' => $category->id,
            'nama_item' => 'Pelet Indukan Update',
            'stok' => 27,
            'status_aktif' => '1',
        ])->assertRedirect(route('logistics.index'));

        $this->assertDatabaseHas('logistics_items', [
            'id' => $item->id,
            'nama_item' => 'Pelet Indukan Update',
            'stok' => 27,
        ]);

        $this->actingAs($karyawan)->post('/logistik/kategori', [
            'nama_kategori' => 'Kategori Uji',
        ])->assertRedirect(route('logistics.index'));

        $this->assertDatabaseHas('logistics_categories', ['nama_kategori' => 'Kategori Uji']);
    }

    public function test_logistics_validation_rejects_empty_payload(): void
    {
        $this->markTestSkipped('Modul Logistik dikosongkan (Abelina).');
        $this->actingAs($this->karyawan())->post('/logistik/item', [])
            ->assertSessionHasErrors(['category_id', 'nama_item', 'stok', 'status_aktif']);

        $this->actingAs($this->karyawan())->post('/logistik/kategori', [])
            ->assertSessionHasErrors('nama_kategori');
    }

    public function test_employee_can_be_updated_and_reactivated_by_owner(): void
    {
        $this->markTestSkipped('Modul Karyawan dikosongkan (Lena).');
        $owner = $this->owner();
        $employee = User::where('username', 'dedi_aris')->firstOrFail();

        $this->actingAs($owner)->put('/akun-karyawan/' . $employee->id, [
            'nama_lengkap' => 'Dedi Arisandi Update',
            'username' => 'dedi_aris',
            'status_aktif' => '0',
        ])->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'nama_lengkap' => 'Dedi Arisandi Update',
            'status_aktif' => false,
        ]);

        $this->actingAs($owner)->patch('/akun-karyawan/' . $employee->id . '/aktifkan')
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('users', ['id' => $employee->id, 'status_aktif' => true]);
    }

    public function test_employee_validation_rejects_duplicate_and_uppercase_username(): void
    {
        $this->markTestSkipped('Modul Karyawan dikosongkan (Lena).');
        $owner = $this->owner();

        $this->actingAs($owner)->post('/akun-karyawan', [
            'nama_lengkap' => 'Karyawan Duplikat',
            'username' => 'budi_kandang',
            'password' => 'rahasia123',
            'status_aktif' => '1',
        ])->assertSessionHasErrors('username');

        $this->actingAs($owner)->post('/akun-karyawan', [
            'nama_lengkap' => 'Karyawan Uppercase',
            'username' => 'Budi_Baru',
            'password' => 'rahasia123',
            'status_aktif' => '1',
        ])->assertSessionHasErrors('username');

        $this->actingAs($owner)->post('/akun-karyawan', [
            'nama_lengkap' => 'Karyawan Password Pendek',
            'username' => 'karyawan_baru',
            'password' => 'pendek',
            'status_aktif' => '1',
        ])->assertSessionHasErrors('password');
    }

    public function test_required_validation_messages_use_indonesian(): void
    {
        $this->markTestSkipped('Menguji modul yang dikosongkan (Lena/Abelina).');
        $owner = $this->owner();

        $this->actingAs($owner)->post('/data-kelinci', [])
            ->assertSessionHasErrors(['nama_kelinci' => 'Nama atau tag kelinci wajib diisi.']);

        $this->actingAs($owner)->post('/pertumbuhan', [])
            ->assertSessionHasErrors(['rabbit_id' => 'Kelinci wajib dipilih.']);

        $this->actingAs($owner)->post('/perkawinan', [])
            ->assertSessionHasErrors(['male_rabbit_id' => 'Kelinci jantan wajib dipilih.']);

        $this->actingAs($owner)->post('/akun-karyawan', [])
            ->assertSessionHasErrors(['nama_lengkap' => 'Nama lengkap wajib diisi.']);
    }

    public function test_report_summary_follows_selected_report_type(): void
    {
        $this->markTestSkipped('Modul Laporan dikosongkan (Abelina).');
        $owner = $this->owner();

        $totalRabbits = Rabbit::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        $rabbitReport = $this->actingAs($owner)->get('/laporan?jenis=kelinci')->assertOk()->getContent();
        $this->assertStringContainsString('Ringkasan angka laporan', $rabbitReport);
        $this->assertStringContainsString('Total Kelinci', $rabbitReport);
        $this->assertStringContainsString($totalRabbits . ' ekor', $rabbitReport);

        foreach (['pertumbuhan', 'perkawinan', 'logistik'] as $jenis) {
            $content = $this->actingAs($owner)->get('/laporan?jenis=' . $jenis)->assertOk()->getContent();

            $this->assertStringContainsString('Ringkasan angka laporan', $content);

            $expected = match ($jenis) {
                'pertumbuhan' => 'Rata-rata Berat',
                'perkawinan' => 'Total Anakan',
                default => 'Total Stok',
            };

            $this->assertStringContainsString($expected, $content);
        }
    }
}
