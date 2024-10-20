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
            $table->id()->from(10001);
            $table->string('name');
            $table->string('seize');
            $table->string('location')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('authorisation')->default("NO");
            $table->string('auth_reference')->nullable();
            $table->string('status')->default('ACTIVE');
            $table->tinyInteger('billable')->default('0');
            $table->unsignedBigInteger('taxpayer_id')->nullable();
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            $table->unsignedBigInteger('taxable_id')->nullable();
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
