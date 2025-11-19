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
        Schema::create('feed_sales_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feed_sale_id')->nullable()->default(null)->constrained('feed_sales')->onDelete('set null');
            $table->foreignUuid('feed_purchase_id')->nullable()->default(null)->constrained('feed_purchases')->onDelete('set null');
            $table->foreignUuid('feed_id')->constrained('feeds')->onDelete('cascade');
            $table->integer('qty')->default(0);
            $table->integer('price_per_unit')->default(0);
            $table->integer('total',false,true)->default(0);
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
        Schema::dropIfExists('feed_sales_details');
    }
};
