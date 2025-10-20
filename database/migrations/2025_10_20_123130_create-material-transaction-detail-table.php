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
        Schema::create('material_transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('material_transaction_id')->constrained('material_transactions')->onDelete('cascade');
            $table->uuid('material_id')->constrained('materials')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('total');
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
        Schema::dropIfExists('material_transaction_details');
    }
};
