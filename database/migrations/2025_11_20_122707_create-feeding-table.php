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
        Schema::create('feeding', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cage_id')->constrained('cages')->onDelete('cascade');
            $table->foreignUuid('feed_location_id')->constrained('feed_locations')->onDelete('cascade');
            $table->integer('qty');
            $table->date('date');
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
        Schema::dropIfExists('feeding');
    }
};
