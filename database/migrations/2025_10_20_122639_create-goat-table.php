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
        Schema::create('goats', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->foreignUuid('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignUuid('cage_id')->constrained('cages')->onDelete('cascade');
            $table->foreignUuid('breed_id')->constrained('breeds')->onDelete('cascade');
            $table->string('mother_id')->nullable();
            $table->string('father_id')->nullable();
            $table->string('origin')->nullable();
            $table->string('female_condition')->nullable();
            $table->string('color')->nullable();
            $table->string('gender')->nullable();
            $table->date('date')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('is_breeder')->default(false);
            $table->boolean('is_qurbani')->default(false);
            $table->string('remarks')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mother_id')->references('code')->on('goats')->onDelete('set null');
            $table->foreign('father_id')->references('code')->on('goats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goats');
    }
};
