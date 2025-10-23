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
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'hpp_id')) {
                $table->ulid('hpp_id')->nullable()->after('document_number');
                $table->foreign('hpp_id')->references('id')->on('hpps')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'hpp_id')) {
                $table->dropForeign(['hpp_id']);
                $table->dropColumn('hpp_id');
            }
        });
    }
};


