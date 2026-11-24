<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logistics_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('logistics_categories')->restrictOnDelete();
            $table->string('nama_item');
            $table->unsignedInteger('stok')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistics_items');
    }
};
