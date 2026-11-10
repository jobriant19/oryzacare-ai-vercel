<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analysis_images', function (Blueprint $table) {
            $table->string('name')->primary();   // sama dengan kolom analyses.image (uuid.ext)
            $table->string('mime', 50);
            $table->longText('data');            // isi foto dalam base64
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analysis_images');
    }
};
