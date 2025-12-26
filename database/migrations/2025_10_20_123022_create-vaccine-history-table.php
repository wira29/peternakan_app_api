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
        Schema::create('vaccine_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('goat_code');
            $table->foreignUuid('vaccine_id')->constrained('vaccines')->onDelete('cascade');
            $table->date('date');
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('goat_code')->references('code')->on('goats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_histories');
    }
};
