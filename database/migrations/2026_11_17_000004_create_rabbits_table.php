<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rabbits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cage_id')->constrained('cages')->restrictOnDelete();
            $table->string('nama_kelinci');
            $table->string('jenis_kelamin', 10);
            $table->date('tgl_lahir');
            $table->string('status_kelinci', 20)->default('Sehat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rabbits');
    }
};
