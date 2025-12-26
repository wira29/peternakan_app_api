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
        Schema::create('feed_purchase_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feed_purchase_id')->constrained('feed_purchases');
            $table->foreignUuid('feed_location_id')->constrained('feed_locations');
            $table->integer('qty')->default(0);
            $table->integer('price_per_unit')->default(0);
            $table->integer('total',false,true)->default(0);
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
        Schema::dropIfExists('table');
    }
};
