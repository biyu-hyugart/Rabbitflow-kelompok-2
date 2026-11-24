<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeding_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('male_rabbit_id')->constrained('rabbits')->cascadeOnDelete();
            $table->foreignId('female_rabbit_id')->constrained('rabbits')->cascadeOnDelete();
            $table->date('tgl_rencana_kawin');
            $table->date('tgl_realisasi_kawin')->nullable();
            $table->string('status_perkawinan', 30)->default('Direncanakan');
            $table->unsignedTinyInteger('jumlah_anakan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tgl_rencana_kawin', 'status_perkawinan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeding_records');
    }
};
