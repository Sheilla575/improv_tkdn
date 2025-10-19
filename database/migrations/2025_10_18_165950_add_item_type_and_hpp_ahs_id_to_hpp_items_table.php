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
        Schema::table('hpp_items', function (Blueprint $table) {
            $table->string('item_type')->nullable()->after('estimation_item_id');
            $table->ulid('hpp_ahs_id')->nullable()->after('hpp_id');
            $table->string('name_ahs')->nullable()->after('description');
            $table->decimal('koefisien', 10, 4)->nullable()->after('unit');
            $table->decimal('jumlah', 10, 2)->nullable()->after('koefisien');
            
            $table->foreign('hpp_ahs_id')->references('id')->on('hpp_ahs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hpp_items', function (Blueprint $table) {
            $table->dropForeign(['hpp_ahs_id']);
            $table->dropColumn(['item_type', 'hpp_ahs_id', 'name_ahs', 'koefisien', 'jumlah']);
        });
    }
};
