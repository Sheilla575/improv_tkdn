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
        Schema::create('hpps', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->ulid('id')->primary();
            $table->string('code')->unique();
            $table->ulid('project_id');
            $table->decimal('sub_total_hpp', 15, 2)->default(0);
            $table->decimal('overhead_percentage', 5, 2)->default(8.00);
            $table->decimal('overhead_amount', 15, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(12.00);
            $table->decimal('margin_amount', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0); // sub_total summery total item di table hpp_items di data total_price
            $table->decimal('ppn_percentage', 5, 2)->default(11.00);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0); // grand_total = sub_grand_total_hpp_item + ppn_amount + overhead_amount + margin_amount
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpps');
    }
};
