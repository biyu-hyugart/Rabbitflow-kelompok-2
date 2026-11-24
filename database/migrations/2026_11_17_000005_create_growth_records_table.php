<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')->constrained('rabbits')->cascadeOnDelete();
            $table->date('tgl_pencatatan');
            $table->decimal('berat_badan', 6, 2);
            $table->decimal('panjang_badan', 5, 1);
            $table->string('status_kelinci', 20);
            $table->string('kondisi_kesehatan');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['rabbit_id', 'tgl_pencatatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_records');
    }
};
