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
        Schema::create('blend_transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('blend_transaction_id')->constrained('blend_transactions')->onDelete('cascade');
            $table->foreignUuid('material_id')->constrained('materials')->onDelete('cascade');
            $table->integer('qty');
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
        Schema::dropIfExists('blend_transaction_details');
    }
};
