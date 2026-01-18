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
        Schema::create('milk_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->constrained('locations');
            $table->date('sale_date');
            $table->integer('qty')->default(0);
            $table->integer('price_per_liter')->default(0);
            $table->integer('total', false, true)->default(0);
            $table->text('remark')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_sales');
    }
};
