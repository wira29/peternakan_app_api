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
            $table->string('female_id');
            $table->string('male_id');
            $table->string('mating_type');
            $table->string('mating_date')->nullable();
            $table->string('status');
            $table->string('remark')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            $table->foreignUuid('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('female_id')->references('code')->on('goats');
            $table->foreign('male_id')->references('code')->on('goats');
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
