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
        Schema::create('feed_purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('location_id')->constrained('locations');
            $table->string('supplier_name')->nullable();
            $table->date('purchase_date');
            $table->integer('total_amount')->default(0);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_purchases');
    }
};
