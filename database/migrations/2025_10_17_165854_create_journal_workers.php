<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal_workers', function (Blueprint $table) {
            $table->id();
            $table->text('nama_pekerjaan');
            $table->text('spesifikasi_or_kualifikasi');
            $table->string('negara_asal');
            $table->string('satuan_or_durasi')->nullable();
            $table->double('volume')->nullable();
            $table->double('satuan_harga');
            $table->double('tkdn');
            $table->double('classification_tkdn');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_workers');
    }
};
