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
        Schema::create('hpp_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('hpp_id');
            $table->ulidMorphs('item'); // item type ini adalah 
            $table->ulid('estimation_item_id')->nullable();
            $table->text('description');
            $table->string('tkdn_classification');
            $table->decimal('volume', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->integer('duration')->nullable();
            $table->string('duration_unit')->nullable();
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();

            $table->foreign('hpp_id')->references('id')->on('hpps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpp_items');
    }
};
