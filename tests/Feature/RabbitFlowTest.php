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

class RabbitFlowTest extends TestCase
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

    public function test_login_page_and_authentication_work(): void
    {
        $this->get('/login')->assertOk();

        $this->post('/login', [
            'username' => 'hendra_owner',
            'password' => 'rahasia123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_inactive_account_cannot_login(): void
    {
        $this->post('/login', [
            'username' => 'dedi_aris',
            'password' => 'karyawan123',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/data-kelinci')->assertRedirect(route('login'));
    }

    public function test_owner_can_open_all_pages(): void
    {
        $owner = $this->owner();

        foreach (['/dashboard', '/akun-karyawan', '/data-kandang', '/data-kelinci', '/pertumbuhan', '/perkawinan', '/logistik', '/laporan'] as $url) {
            $this->actingAs($owner)->get($url)->assertOk();
        }
    }

    public function test_karyawan_access_is_limited_by_role(): void
    {
        $karyawan = $this->karyawan();

        $this->actingAs($karyawan)->get('/akun-karyawan')->assertForbidden();
        $this->actingAs($karyawan)->get('/data-kandang')->assertForbidden();

        $this->actingAs($karyawan)->get('/dashboard')->assertOk();
        $this->actingAs($karyawan)->get('/data-kelinci')->assertOk();
        $this->actingAs($karyawan)->get('/pertumbuhan')->assertOk();
        $this->actingAs($karyawan)->get('/perkawinan')->assertOk();
        $this->actingAs($karyawan)->get('/logistik')->assertOk();
        $this->actingAs($karyawan)->get('/laporan')->assertOk();

        $this->actingAs($karyawan)->post('/perkawinan', [])->assertForbidden();
    }

    public function test_owner_can_manage_cages(): void
    {
        $this->markTestSkipped('Modul Kandang dikosongkan (Lena).');
        $owner = $this->owner();

        $this->actingAs($owner)->post('/data-kandang', [
            'lokasi_blok' => 'Blok Uji - Sekat 99',
            'kapasitas' => 5,
        ])->assertRedirect(route('cages.index'));

        $cage = Cage::where('lokasi_blok', 'Blok Uji - Sekat 99')->firstOrFail();

        $this->actingAs($owner)->put('/data-kandang/' . $cage->id, [
            'lokasi_blok' => 'Blok Uji - Sekat 98',
            'kapasitas' => 6,
        ])->assertRedirect(route('cages.index'));

        $this->assertDatabaseHas('cages', ['id' => $cage->id, 'kapasitas' => 6]);

        $this->actingAs($owner)->delete('/data-kandang/' . $cage->id)->assertRedirect(route('cages.index'));
        $this->assertDatabaseMissing('cages', ['id' => $cage->id]);
    }

    public function test_owner_can_create_and_update_rabbit_status(): void
    {
        $this->markTestSkipped('Modul Kelinci dikosongkan (Lena).');
        $owner = $this->owner();
        $cage = Cage::whereRaw('kapasitas > (select count(*) from rabbits where rabbits.cage_id = cages.id)')->firstOrFail();

        $this->actingAs($owner)->post('/data-kelinci', [
            'nama_kelinci' => 'Kelinci Uji',
            'jenis_kelamin' => 'Jantan',
            'tgl_lahir' => '2025-01-01',
            'cage_id' => $cage->id,
            'status_kelinci' => 'Sehat',
        ])->assertRedirect(route('rabbits.index'));

        $rabbit = Rabbit::where('nama_kelinci', 'Kelinci Uji')->firstOrFail();

        $this->actingAs($owner)->patch('/data-kelinci/' . $rabbit->id . '/status', [
            'status_kelinci' => 'Karantina',
        ])->assertRedirect(route('rabbits.index'));

        $this->assertDatabaseHas('rabbits', ['id' => $rabbit->id, 'status_kelinci' => 'Karantina']);

        $this->actingAs($owner)->delete('/data-kelinci/' . $rabbit->id)->assertRedirect(route('rabbits.index'));
        $this->assertDatabaseMissing('rabbits', ['id' => $rabbit->id]);
    }

    public function test_rabbit_validation_rejects_cage_over_capacity(): void
    {
        $this->markTestSkipped('Modul Kelinci dikosongkan (Lena).');
        $owner = $this->owner();
        $rabbit = Rabbit::firstOrFail();
        $fullCage = $rabbit->cage;

        while ($fullCage->rabbits()->count() < $fullCage->kapasitas) {
            Rabbit::create([
                'cage_id' => $fullCage->id,
                'nama_kelinci' => 'Pengisi ' . uniqid(),
                'jenis_kelamin' => 'Jantan',
                'tgl_lahir' => '2025-02-02',
                'status_kelinci' => 'Sehat',
            ]);
        }

        $this->actingAs($owner)->post('/data-kelinci', [
            'nama_kelinci' => 'Melebihi Kapasitas',
            'jenis_kelamin' => 'Betina',
            'tgl_lahir' => '2025-01-01',
            'cage_id' => $fullCage->id,
            'status_kelinci' => 'Sehat',
        ])->assertSessionHasErrors('cage_id');
    }

    public function test_karyawan_can_manage_rabbits_and_growth(): void
    {
        $this->markTestSkipped('Modul Kelinci dikosongkan (Lena).');
        $karyawan = $this->karyawan();
        $cage = Cage::whereRaw('kapasitas > (select count(*) from rabbits where rabbits.cage_id = cages.id)')->firstOrFail();

        $this->actingAs($karyawan)->post('/data-kelinci', [
            'nama_kelinci' => 'Kelinci Karyawan',
            'jenis_kelamin' => 'Betina',
            'tgl_lahir' => '2025-03-03',
            'cage_id' => $cage->id,
            'status_kelinci' => 'Sehat',
        ])->assertRedirect(route('rabbits.index'));

        $rabbit = Rabbit::where('nama_kelinci', 'Kelinci Karyawan')->firstOrFail();

        $this->actingAs($karyawan)->patch('/data-kelinci/' . $rabbit->id . '/status', [
            'status_kelinci' => 'Karantina',
        ])->assertRedirect(route('rabbits.index'));

        $this->actingAs($karyawan)->post('/pertumbuhan', [
            'rabbit_id' => $rabbit->id,
            'tgl_pencatatan' => now()->toDateString(),
            'berat_badan' => 2.5,
            'panjang_badan' => 38,
            'status_kelinci' => 'Karantina',
            'kondisi_kesehatan' => 'Baik',
            'keterangan' => 'Pencatatan oleh karyawan.',
        ])->assertRedirect(route('growth.index'));

        $this->assertDatabaseHas('rabbits', ['id' => $rabbit->id, 'status_kelinci' => 'Karantina']);
        $this->assertDatabaseHas('growth_records', ['rabbit_id' => $rabbit->id, 'berat_badan' => 2.5]);
    }

    public function test_owner_can_record_growth(): void
    {
        $owner = $this->owner();
        $rabbit = Rabbit::firstOrFail();

        $this->actingAs($owner)->post('/pertumbuhan', [
            'rabbit_id' => $rabbit->id,
            'tgl_pencatatan' => now()->toDateString(),
            'berat_badan' => 3.75,
            'panjang_badan' => 41.5,
            'status_kelinci' => 'Sehat',
            'kondisi_kesehatan' => 'Baik',
            'keterangan' => 'Uji pencatatan.',
        ])->assertRedirect(route('growth.index'));

        $this->assertDatabaseHas('growth_records', ['rabbit_id' => $rabbit->id, 'berat_badan' => 3.75]);
    }

    public function test_owner_can_schedule_breeding_and_karyawan_cannot(): void
    {
        $owner = $this->owner();
        $male = Rabbit::where('jenis_kelamin', 'Jantan')->firstOrFail();
        $female = Rabbit::where('jenis_kelamin', 'Betina')->firstOrFail();

        $payload = [
            'male_rabbit_id' => $male->id,
            'female_rabbit_id' => $female->id,
            'tgl_rencana_kawin' => now()->addWeek()->toDateString(),
            'status_perkawinan' => 'Direncanakan',
        ];

        $this->actingAs($owner)->post('/perkawinan', $payload)->assertRedirect(route('breeding.index'));
        $this->assertDatabaseHas('breeding_records', ['male_rabbit_id' => $male->id, 'female_rabbit_id' => $female->id]);

        $this->actingAs($this->karyawan())->post('/perkawinan', $payload)->assertForbidden();
    }

    public function test_logistics_is_managed_by_karyawan_only(): void
    {
        $this->markTestSkipped('Modul Logistik dikosongkan (Abelina).');
        $karyawan = $this->karyawan();
        $owner = $this->owner();
        $category = LogisticsCategory::firstOrFail();

        $this->actingAs($karyawan)->post('/logistik/item', [
            'category_id' => $category->id,
            'nama_item' => 'Item Uji Logistik',
            'stok' => 10,
            'status_aktif' => '1',
        ])->assertRedirect(route('logistics.index'));

        $item = LogisticsItem::where('nama_item', 'Item Uji Logistik')->firstOrFail();

        $this->actingAs($owner)->post('/logistik/item', [
            'category_id' => $category->id,
            'nama_item' => 'Item Ditolak',
            'stok' => 1,
            'status_aktif' => '1',
        ])->assertForbidden();

        $this->actingAs($karyawan)->patch('/logistik/item/' . $item->id . '/nonaktifkan')
            ->assertRedirect(route('logistics.index'));

        $this->assertDatabaseHas('logistics_items', ['id' => $item->id, 'status_aktif' => false]);
    }

    public function test_all_report_types_render_with_real_data(): void
    {
        $this->markTestSkipped('Modul Laporan dikosongkan (Abelina).');
        $owner = $this->owner();

        foreach (['kelinci', 'pertumbuhan', 'perkawinan', 'logistik'] as $jenis) {
            $this->actingAs($owner)->get('/laporan?jenis=' . $jenis)->assertOk();
        }
    }

    public function test_report_access_differs_by_role(): void
    {
        $this->markTestSkipped('Modul Laporan dikosongkan (Abelina).');
        $owner = $this->owner();
        $karyawan = $this->karyawan();

        $ownerPage = $this->actingAs($owner)->get('/laporan')->assertOk()->getContent();
        $this->assertStringContainsString('Laporan Data Kelinci', $ownerPage);
        $this->assertStringContainsString('Laporan Lengkap', $ownerPage);

        $karyawanPage = $this->actingAs($karyawan)->get('/laporan')->assertOk()->getContent();
        $this->assertStringNotContainsString('Laporan Data Kelinci', $karyawanPage);
        $this->assertStringContainsString('Laporan Pertumbuhan', $karyawanPage);
        $this->assertStringContainsString('Laporan Operasional', $karyawanPage);

        $this->actingAs($karyawan)->get('/laporan?jenis=kelinci')->assertOk();
        $this->assertStringNotContainsString(
            'Laporan Data Kelinci',
            $this->actingAs($karyawan)->get('/laporan?jenis=kelinci')->getContent()
        );
    }

    public function test_employee_account_can_be_created_and_deactivated_by_owner(): void
    {
        $this->markTestSkipped('Modul Karyawan dikosongkan (Lena).');
        $owner = $this->owner();

        $this->actingAs($owner)->post('/akun-karyawan', [
            'nama_lengkap' => 'Karyawan Uji',
            'username' => 'karyawan_uji',
            'password' => 'rahasia123',
            'status_aktif' => '1',
        ])->assertRedirect(route('employees.index'));

        $employee = User::where('username', 'karyawan_uji')->firstOrFail();
        $this->assertTrue($employee->isKaryawan());

        $this->actingAs($owner)->patch('/akun-karyawan/' . $employee->id . '/nonaktifkan')
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('users', ['id' => $employee->id, 'status_aktif' => false]);
    }

    public function test_owner_account_is_protected_from_employee_page_actions(): void
    {
        $this->markTestSkipped('Modul Karyawan dikosongkan (Lena).');
        $owner = $this->owner();

        $this->actingAs($owner)->patch('/akun-karyawan/' . $owner->id . '/nonaktifkan')->assertForbidden();
    }
}
