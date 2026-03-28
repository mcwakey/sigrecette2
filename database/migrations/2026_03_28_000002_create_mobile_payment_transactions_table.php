<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('taxpayer_id')->nullable()->constrained();
            $table->decimal('amount', 15, 2);
            $table->string('phone_number');
            $table->string('provider');
            $table->string('external_id')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedSmallInteger('verification_attempts')->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('provider_response')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();

            $table->index(['invoice_id', 'status']);
            $table->index(['status', 'expires_at']);
            $table->index('provider');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_payment_transactions');
    }
};
