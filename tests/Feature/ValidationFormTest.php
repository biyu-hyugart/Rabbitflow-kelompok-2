<?php

namespace Tests\Feature;

use App\Models\Cage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationFormTest extends TestCase
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

    public function test_create_validation_failure_keeps_input_and_flags_modal(): void
    {
        $this->markTestSkipped('Modul Kandang dikosongkan (Lena).');
        $response = $this->actingAs($this->owner())->post('/data-kandang', [
            '_token' => csrf_token(),
            '_method' => 'POST',
            '_modal' => 'cageModal',
            '_action' => 'http://localhost:8000/data-kandang',
            'lokasi_blok' => '',
            'kapasitas' => 999,
        ]);

        $response->assertSessionHasErrors(['lokasi_blok', 'kapasitas']);

        $content = $this->actingAs($this->owner())
            ->withSession($response->getSession()->all())
            ->get('/data-kandang')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('name="rf-open-modal" content="cageModal"', $content);
        $this->assertStringContainsString('window.rfValidationErrors', $content);
        $this->assertStringContainsString('Lokasi atau blok kandang wajib diisi.', $content);
        $this->assertStringContainsString('action="http://localhost:8000/data-kandang"', $content);
        $this->assertStringContainsString('value="POST" id="cageMethod"', $content);
        $this->assertStringContainsString('"kapasitas":999', $content);
    }

    public function test_edit_validation_failure_renders_update_action(): void
    {
        $this->markTestSkipped('Modul Kandang dikosongkan (Lena).');
        $cage = Cage::firstOrFail();

        $response = $this->actingAs($this->owner())->put('/data-kandang/' . $cage->id, [
            '_token' => csrf_token(),
            '_method' => 'PUT',
            '_modal' => 'cageModal',
            '_action' => 'http://localhost:8000/data-kandang/' . $cage->id,
            'lokasi_blok' => '',
            'kapasitas' => 0,
        ]);

        $response->assertSessionHasErrors(['lokasi_blok', 'kapasitas']);

        $content = $this->actingAs($this->owner())
            ->withSession($response->getSession()->all())
            ->get('/data-kandang')
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('name="rf-open-modal" content="cageModal"', $content);
        $this->assertStringContainsString('action="http://localhost:8000/data-kandang/' . $cage->id . '"', $content);
        $this->assertStringContainsString('value="PUT" id="cageMethod"', $content);
        $this->assertStringContainsString('Kapasitas minimal 1 ekor.', $content);
    }

    public function test_no_modal_error_renders_plain_flash_only(): void
    {
        $this->markTestSkipped('Modul Kandang dikosongkan (Lena).');
        $content = $this->actingAs($this->owner())->get('/data-kandang')->assertOk()->getContent();

        $this->assertStringNotContainsString('rf-open-modal', $content);
        $this->assertStringNotContainsString('window.rfValidationErrors', $content);
    }
}
