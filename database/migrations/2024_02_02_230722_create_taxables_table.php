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
        Schema::create('taxables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tariff');
            $table->string('tariff_type')->default('FIXED');
            $table->string('unit')->nullable();
            $table->string('unit_type')->nullable();
            $table->string('modality')->nullable();
            $table->string('periodicity')->nullable();
            $table->string('penalty')->nullable();
            $table->string('penalty_type')->nullable()->default('FIXED');;
            $table->string('status')->default('ACTIVE');
            $table->unsignedBigInteger('tax_label_id')->nullable();
            $table->foreign('tax_label_id')->references('id')->on('tax_labels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxables');
    }
};
