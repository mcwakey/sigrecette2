<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_code_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->string('code');
            $table->unsignedInteger('year');
            $table->double('amount_billed')->default(0);
            $table->double('amount_paid')->default(0);
            $table->double('remaining_amount')->default(0);
            $table->string('status')->default('OWING');
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamps();

            $table->unique(['invoice_id', 'code']);
            $table->index(['taxpayer_id', 'code', 'year']);
            $table->index(['taxpayer_id', 'year']);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('taxpayer_id')->references('id')->on('taxpayers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_code_balances');
    }
};
