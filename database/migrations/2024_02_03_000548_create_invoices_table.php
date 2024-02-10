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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('order_no');
            $table->string('nic');
            $table->string('status')->default('PENDING');
            $table->string('pay_status')->default('OWING');
            $table->double('amount');
            $table->unsignedBigInteger('taxpayer_id')->default(1);
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers');
            // $table->unsignedBigInteger('taxpayer_taxable_id')->default(1);
            // $table->foreign('taxpayer_taxable_id')->references('id')->on('taxpayer_taxables');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
