<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_delapan_tabel_utama_tersedia(): void
    {
        $tables = [
            'roles',
            'users',
            'cages',
            'rabbits',
            'growth_records',
            'breeding_records',
            'logistics_categories',
            'logistics_items',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Tabel {$table} tidak ditemukan. Periksa migration yang sesuai dengan ERD."
            );
        }
    }
}
