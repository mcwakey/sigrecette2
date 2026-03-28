<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('payment_type');
            $table->string('phone_number')->nullable()->after('provider');
            $table->string('external_id')->nullable()->after('phone_number');
            $table->unsignedSmallInteger('verification_attempts')->default(0)->after('external_id');
            $table->timestamp('last_checked_at')->nullable()->after('verification_attempts');
            $table->timestamp('expires_at')->nullable()->after('last_checked_at');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'provider',
                'phone_number',
                'external_id',
                'verification_attempts',
                'last_checked_at',
                'expires_at',
            ]);
        });
    }
};
