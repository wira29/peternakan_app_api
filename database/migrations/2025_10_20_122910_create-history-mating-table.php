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
        Schema::create('mating_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('female_code');
            $table->string('male_code');
            $table->uuid('mating_type_id')->constrained('mating_types')->onDelete('cascade');
            $table->uuid('mating_status_id')->constrained('mating_statuses')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('female_code')->references('code')->on('goats')->onDelete('cascade');
            $table->foreign('male_code')->references('code')->on('goats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
