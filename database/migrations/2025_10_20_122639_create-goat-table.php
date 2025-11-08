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
            $table->uuid('location_id')->constrained('locations')->onDelete('cascade');
            $table->uuid('cage_id')->constrained('cages')->onDelete('cascade');
            $table->uuid('breed_id')->constrained('breeds')->onDelete('cascade');
            $table->uuid('mother_id')->nullable()->constrained('goats')->onDelete('set null');
            $table->uuid('father_id')->nullable()->constrained('goats')->onDelete('set null');
            $table->string('origin')->nullable();
            $table->string('female_condition')->nullable();
            $table->string('color')->nullable();
            $table->string('gender')->nullable();
            $table->date('date')->nullable();
            $table->integer('price')->nullable();
            $table->boolean('is_breeder');
            $table->boolean('is_qurbani')->default(false);
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('goats');
    }
};
