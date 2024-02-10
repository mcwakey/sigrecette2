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
        Schema::create('taxpayer_taxables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('seize');
            $table->string('location');
            $table->string('status')->default('ACTIVE');
            $table->unsignedBigInteger('taxpayer_id')->default(1);
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->unsignedBigInteger('taxable_id')->default(1);
            $table->foreign('taxable_id')->references('id')->on('taxables');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxpayer_taxables');
    }
};
